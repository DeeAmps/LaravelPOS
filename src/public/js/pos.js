(function($) {
    let _TOKEN = $('meta[name="csrf-token"]').attr('content');

    $(document).ready(()=> {

        $('#pos-product-search-field').focus();

        $('#product-search-form').on('submit', (event)=> {
            event.preventDefault();
            $('#pos-product-search-result-tbody').empty();
            let filter = $(event.target).find('#pos-product-search-field').val();
            searchProduct(filter, (result)=> {
                handleSearchResult(result);
                setChangeField();
            })
        });


        $(document).on('change', '.sku-select', (event)=> {
            let entry = $(event.target).closest('tr');
            let skuId = $(event.target).val();
            let skuData = (getProductDataFromDOM(event) || {}).stock_units;
            let sku = skuData.filter((sku)=> {
                return sku.id ==skuId;
            })[0] || {};
            let unitPrice = ((((sku.stock) || {}).selling_price || 0.00)/100).toFixed(2);
            entry.find('.entry-unit-price').text(unitPrice)
            entry.find('.entry-unit-price').change();
            setEntrySubtotal(entry)
            let selectedSku = sku || {}; 
            setTotal();  
            setChangeField(); 
        });


        $(document).on('change keyup mouseup', '.order-quantity', (event)=> {
            let entry = $(event.target).closest('tr');
            setEntrySubtotal(entry);
            setTotal();
        });

        $(document).on('click', '.search-result', (event)=> {
            let entry = $(event.target).closest('tr');
            let product = entry.data('product_search_data')
            appendToCart(product);
            setChangeField();
        });

        $(document).on('click', '.remove-entry', removeEntry);

        $(document).on('change keyup mouseup', '#vat-range', (event)=> {
            let changedValue = $(event.target).val();
            $('#vat-value').val(changedValue);
            setTotal();
            setChangeField();
        })

        $(document).on('change mouseup keyup', '#amount-paid', (event)=> {
            handleToggleSellBtn();
            setChangeField();
        });

        $('#order-checkout-form').on('submit', (event)=> {
            event.preventDefault();
            let saleData = getSaleDataFromDOM();
            //validateSale();
            completeSale(saleData, (result)=> {
                if((result || {}).code == 0) {
                    Notifier.success((result || {}).message);
                    let date = (result.date || {}).date;
                    buildReceiptTable(saleData, date);
                    window.print();
                    location.reload(true);
                }
            })
        })

        $(document).on('click', '.order-entry', (event)=> {
            let $entry = $(event.target).closest('tr');
            let data = $entry.data('product_data');
        });
    })


    function setChangeField() {
        let paid = Number($('#amount-paid').val()) || 0.00;
        let total = Number($('#total-value').val()) || 0.00;
        let change = (paid - total).toFixed(2);
        $('#payment-change').val(change);
    }

    function buildReceiptTable(data, date) {
        let entries = data.entries || [];
        let vat  = Number((data.summary || {}).vat) || 0.00;
        let totalAmount = Number((data.summary || {}).subtotal) || 0.00;
        let vatvalue = getVatFor(vat, totalAmount);
        $('#receipt-total-value').text((data.summary|| {}).total);
        $('#receipt-date').text(new Date(date).toLocaleDateString());
        $('#receipt-ref').text((data.summary || {}).refCode);
        $('#receipt-change-value').text((data.summary || {}).change);
        $('#receipt-paid-value').text((data.summary||{}).paid);
        $('#receipt-sub-total').text((data.summary || {}).subtotal || 0.00);
        $('#receipt-vat-value').text(vatvalue);
        entries.forEach((entry)=> {
            buildReceiptRow(entry);
        })
    }


    function buildReceiptRow(entry_data) {
        let entry = entry_data || {};
        let sum = (Number(entry.unit_price) * Number(entry.quantity)).toFixed(2);
        $row = $('<tr></tr>').html(`<td>${entry.product_name || '--'}</td>
                                     <td>${entry.unit_price || '0.00'}</td>
                                     <td>${entry.quantity || ''} ${entry.stock_unit_name}</td>
                                     <td>${sum}</td>`);
        $('.receipt-tbody').append($row);      
    }


    function handleSearchResult(data) {
        let products = data || [];
        let numberOfProducts = products.length;
        if(numberOfProducts == 0) {
            Notifier.info('No product found for this search!');
        } else if(numberOfProducts == 1){
            appendToCart(products[0]);
        } else {
            appendToSearchResultList(products);
        }
    }


    function getSaleDataFromDOM() {
        let data = {};
        let summaryData = {};
        let entries = [];
        $('#order-table-body').find('.order-entry').each(function() {
            let $entry = $(this).closest('tr');
            let entry = {};
            let selectedSkuId = $entry.find('.sku-select option:selected').val();
            let productData = $entry.data('product_data');
            let stockUnits = (productData.stock_units || [])
            let selectedStockUnitData = stockUnits.filter((sku)=> {
                                                                return sku.id == selectedSkuId;
                                                            })[0] || {};                                               
            let costPrice = (selectedStockUnitData.stock || {}).cost_price || 0;
            let sellingPrice = (selectedStockUnitData.stock || {}).selling_price || 0;                                             
            entry.product_id =  $entry.find('.product-id').text();
            entry.product_name = $entry.find('.product-name').text();
            entry.stock_unit_id = selectedSkuId;
            entry.stock_unit_name = $entry.find('.sku-select option:selected').text();
            entry.unit_price = $entry.find('.entry-unit-price').text();
            entry.quantity = $entry.find('.order-quantity').val();
            entry.balance = (sellingPrice - costPrice)*Number(entry.quantity);
            entries.push(entry);
        });
        summaryData.vat = $('#vat-value').val();
        summaryData.change = $('#payment-change').val();
        summaryData.total = $('#total-value').val();
        summaryData.refCode = $('#order-ref').text();
        summaryData.subtotal=$('#sub-total-value').text();
        summaryData.currency_id = 1;
        summaryData.paid = $('#amount-paid').val();
        data.summary = summaryData;
        data.entries = entries;
        return data;
    }


    function appendToCart(product) {
        let productName = product.name || '';
        let productSkus = (product.stock_units || []).filter((sku)=> {
            return sku.stock.selling_price > 0;
        });
        let $tbody = $('#order-table-body');
        let skuSelect = createSkuSelect(productSkus);
        let $row = $('<tr></tr>').html(`<td class="product-name">${productName}</td>
                        <td class="product-id hidden">${product.id}</td>
    		            <td class="sku"></td>
    		            <td class='entry-unit-price'></td>
                    <td><input type='number' class='form-control order-quantity' value="1" min="1" step="1"/></td>
                        <td class="entry-sub-total"></td>
                    <td><button class="btn btn-danger remove-entry">X</button></td>
                    <td class="entry-id hidden"></td>`).addClass('order-entry');
        $row.find('.sku').append(skuSelect);  
        $row.data('product_data', product);   
        $tbody.prepend($row);
        skuSelect.change();
        $('#pos-product-search-field').val('');
        $('#pos-product-search-field').focus();
        handleToggleSellBtn();
    }

    function appendToSearchResultList(products) {
        $base = $('#pos-product-search-result-tbody');
        products.forEach((product)=> {
            let $row = $('<tr></tr>').html(`<td>${product.name}</td>`).addClass('search-result');
            $row.data('product_search_data', product);
            $base.append($row);
        });
    }



    function createSkuSelect(stocks, default_id) {
        let $select = $('<select class="form-control"></select>').addClass('sku-select');
            if(stocks.length < 2){
                $select.attr({disabled:true});
            }
            stocks.forEach((stock) => {
                let attr = {value: stock.id};
                if (default_id == stock.id) {
                    attr.selected = true;
                }
                let $option = $('<option></option>')
                                .attr(attr)
                                .text(`${stock.label}`);
                $(stock).data({stock_data: stock});
                $select.append($option);
            });
      return $select;
    }

    function searchProduct(search_filter, then) {
        let filter = search_filter;
        let url = `${App.ROOT_URL}/pos/product/search?filter=${filter}`;
        $.ajax({
            type:'get',
            url:url,
            dataType:'json',
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

    function completeSale(sale_data, then) {
        let data = sale_data || {};
        let url = `${App.ROOT_URL}/pos/sales/close-order`;
        $.ajax({
            type:'post',
            url:url,
            data:JSON.stringify(data),
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


    function getProductDataFromDOM(event) {
        return $(event.target).closest('tr').data('product_data');
    }

    function setEntrySubtotal(entry) {
        let quantity = Number(entry.find('.order-quantity').val());
        let stockPrice = (Number(entry.find('.entry-unit-price').text())||0.00)*100;
        let subTotal = ((quantity*stockPrice)/100).toFixed(2);
        entry.find('.entry-sub-total').text(subTotal);
    }

    function setTotal() {
        let total = 0.00;
        let vat = 0.00;
        $('.entry-sub-total').each(function() {
            let sub = (Number($(this).text()) || 0.00)*100;
            total += sub;
        })
        totalAmount = (total/100).toFixed(2);
        $('#sub-total-value').val(totalAmount);
        $('#total-value').val(totalAmount);
        setChangeField();
    }

    function removeEntry(event) {
        $(event.target).closest('tr').remove();
        setTotal();
        handleToggleSellBtn();
    }

    function getVatFor(vat_value, paid_amount) {
        let vat = Number(vat_value) || 0.00;
        let amount = Number(paid_amount) || 0.00; 
        let per = (vat/100).toFixed(4);
        return Math.round(per*amount) || 0.00;
    }

    function handleToggleSellBtn() {
        let amountPaid = Number($('#amount-paid').val());
        let totalamount = Number($('#total-value').val());
        let entryExist = ($('#order-table-body').find('tr')).length;
        if(amountPaid < totalamount || entryExist == 0) {
            disableBtn($('#sell-btn'));
        } else if(entryExist>0){
            enableBtn($('#sell-btn'));
        }
    }

    function enableBtn($btn) {
        if($btn) {
            $btn.prop({disabled:false});
        }
    }

    function disableBtn($btn) {
        if($btn) {
            $btn.prop({disabled:true});
        }
    }

    function validateSale() {
        $('.order-entry').each(function() {
            let $entry = $(this);
            let data = $entry.data('product_data');
            let selectedSkuId = $entry.find('.sku-select option:selected').val();
            let enteredQuantity = Number($entry.find('.order-quantity').val());
            let stockUnits = data.stock_units || [];
            let pieceSku = (stockUnits.filter((stock)=> {
                                                        return stock.id == 1;
                                                    })[0] || {});
            let pieceQuantity = (pieceSku.stock || {}).stock_quantity || 0;
            let selectedStockData = stockUnits.filter((stock)=> {
                                                        return stock.id == selectedSkuId;
                                                    })[0];
            let selectedSkuRate = selectedStockData.relative_sku_to_sku || 1;
            let stockQuantity = (Number(selectedSkuRate) * Number(pieceQuantity)).toFixed(2) || 1;
            let orderedQuantity = (Number(enteredQuantity) * Number(selectedSkuRate)).toFixed(2);
            if(orderedQuantity > stockQuantity) {
                Notifier.error('Not enough in stock!');
                //return false;
            } else {
                //return true;
            }                           
        });
    }
    
})(jQuery)