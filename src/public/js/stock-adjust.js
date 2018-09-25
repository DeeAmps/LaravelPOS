(function($){
    const CREATE_REASON_MODAL= $('.create-reason-modal')
    const REASON_SELECT = $('#adjustment-description');
    let _TOKEN = $('meta[name="csrf-token"]').attr('content');
    $(document).ready(()=> {
        console.log('adjust')

        $('#adjust-search-product-form').on('submit', (event)=> {
            event.preventDefault();
            let filter = $('#product-search-input').val() || '';
            searchProduct(filter, (result)=> {
                let products = result.products || [];
                handleSearchResult(products);
            })
        });

        $(document).on('click', '.search-result-item', (event)=> {
            let entry = $(event.target).closest('tr');
            let product = entry.data('product_data');
            appendToAdjustmentTable(product);
        })

        $(document).on('click', '.stock-entry-remove-btn', (event)=> {
            $(event.target).closest('tr').remove();
        });


        $(document).on('change keyup', '.new-quantity', (event)=> {
            let entry = $(event.target).closest('tr');
            let newQuantity = Number($(event.target).val());
            let oldQUantity = Number(entry.find('.old-quantity').text())
            let quantity = (newQuantity - oldQUantity).toFixed(2);
            let defaultStock = entry.data('default_sku_data') || {};
            let costPrice = defaultStock.stock.cost_price || 0;
            let sellingPrice = defaultStock.stock.selling_price || 0;
            let balance = 'GHC'+(((costPrice)*quantity)/100).toFixed(2);
            console.log(balance)
            entry.find('.difference-quantity').val(quantity);
            $('#adjust-balance').val(balance);
        })

        $(document).on('click', '#adjust-btn', (event)=> {
            console.log(getStockAdjustData());
            let isconfirm = confirm('Are you sure you want to adjust stock?');
            if(isconfirm) {
                let data = getStockAdjustData() || {};
                createStockAdjustment(data, (result)=> {
                    console.log(result);
                    if(result.code == 0) {
                        Notifier.success('styock adjustment successful')
                        location.reload(true);
                    }
                })
            }
        })

        $(document).on('click', '#create-reason', (event)=> {
            CREATE_REASON_MODAL.modal('show');
        })
        
        $('#create-reason-form').on('submit', (event)=> {
            event.preventDefault();
            let data = {};
            data.label = $('#create-reason-name').val() || '';
            data.description = $('#create-reason-description').val() || '';
            createReason(data, (result)=> {
                if(result.code == 0) {
                    let reasons = result.reasons || [];
                    REASON_SELECT.empty();
                    let options = App.createSelect(reasons);
                    REASON_SELECT.append(options);
                    CREATE_REASON_MODAL.modal('hide');
                }
            })
        })
    })



    function createReason(data, then) {
        let url = `${App.ROOT_URL}/inventory/adjust-stock/reason`;
        $.ajax({
            type:'post',
            url:url,
            dataType:'json',
            data:JSON.stringify(data),
            constentType:'application/json; charset=utf-8',
            contentType:'application/json; charset=utf-8',
            headers: {
                'CSRF-TOKEN': _TOKEN,
                'X-CSRF-TOKEN': _TOKEN,
                'XSRF-TOKEN': _TOKEN
            }
        })
        .done((result)=> {
            then(result);
        })
        .fail(App.handleRequestFailure);
    }


    function appendToAdjustmentTable(product_data) {
        let base = $('#adjustment-tbody');
        let product = product_data || {};
        name = product.name ||'';
        let pieceSku = (product.stock_units || []).filter((stock)=> {
                                                                return stock.id == 1;
                                                            })[0] || {};
        let stockQuantity = pieceSku.stock.stock_quantity;
        $row = $(`<tr></td>`).html(`<td>${name}</td>
                                    <td class="product-id hidden">${product.id}</td>                                
                                    <td class="old-quantity">${stockQuantity}</td>
                                    <th class="sku-id hidden">${pieceSku.id}</td>
                                    <td><input type="number" class="form-control new-quantity"/></td>
                                    <td><input type="number" class="form-control difference-quantity" readonly/></td>
                                    <td><button class="btn btn-danger stock-entry-remove-btn">X</button></td>`).addClass('adjust-entry');
        $row.data('default_sku_data', pieceSku);                                
        base.append($row);                                                                            
    }


    function handleSearchResult(products_list) {
        $('#product-search-result-tbody').empty();
        let products = products_list || [];
        if(products.length==0) {
            Notifier.info('No products found for this search');
        } else {
            products.forEach((product)=> {
                appendToSearchResults(product);
            });
        }
    }


    function appendToSearchResults(product) {
        let base = $('#product-search-result-tbody');
        let name = product.name;
        $row = $(`<tr></tr>`).html(`<td>${name}</td>`).addClass('search-result-item');
        $row.data('product_data', product);
        base.append($row);
    }


    function searchProduct(filter, then) {
        let url = `${App.ROOT_URL}/inventory/search-product-json?filter=${filter}`;
        $.ajax({
            type:'get',
            url:url,
            dataType:'json',
            constentType:'application/json; charset=utf-8',
            contentType:'application/json; charset=utf-8',
            headers: {
                'CSRF-TOKEN': _TOKEN,
                'X-CSRF-TOKEN': _TOKEN,
                'XSRF-TOKEN': _TOKEN
            }
        })
        .done((result)=> {
            then(result);
        })
        .fail(App.handleRequestFailure);
    }    

    function getStockAdjustData() {
        let data = {};
        data.reference_code = $('#adjustment-ref').val();
        data.reason = $('#adjustment-description option:selected').val();
        let entries = [];
        $('.adjust-entry').each(function(){
            let $entry = $(this);
            let entryData = {};
            let stockData = $entry.data('default_sku_data');
            entryData.product_id = $entry.find('.product-id').text();
            entryData.stock_unit_id = 1;
            entryData.old_quantity = $entry.find('.old-quantity').text()
            entryData.new_quantity = $entry.find('.new-quantity').val();
            entryData.difference = $entry.find('.difference-quantity').val() || 0.00;
            entryData.cost_price = stockData.stock.cost_price || 0;
            entryData.selling_price = stockData.stock.selling_price || 0
            entries.push(entryData)
        });
        data.entries = entries;
        return data;
    }

    function createStockAdjustment(data, then) {
        let url = `${App.ROOT_URL}/inventory/stock/adjustment`;
        $.ajax({
            type:'post',
            url:url,
            dataType:'json',
            data:JSON.stringify(data),
            constentType:'application/json; charset=utf-8',
            contentType:'application/json; charset=utf-8',
            headers: {
                'CSRF-TOKEN': _TOKEN,
                'X-CSRF-TOKEN': _TOKEN,
                'XSRF-TOKEN': _TOKEN
            }
        })
        .done((result)=> {
            then(result);
        })
        .fail(App.handleRequestFailure);
    }
})(jQuery)