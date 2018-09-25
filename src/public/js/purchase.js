(function($) {
    let _TOKEN = $('meta[name="csrf-token"]').attr('content');
    $(document).ready(()=> {

        $(document).on('click', '.create-vendor-open-modal', (event)=> {
            openCreateVendorModel();
        });

        //create vendor submit btn
        $('#create-vendor-form').on('submit', (event)=> {
            event.preventDefault();
            console.log('create vendor submit');
            let data = getVendorDataFromForm();
            createVendor(data, (result)=> {
                console.log(result);
                if(result.code == 0) {
                    Notifier.success('Vendor created successfully!')
                    let options = App.createSelect(result.customers);
                    $('#vendor-id').empty();
                    $('#vendor-id').append(options);
                    resetCreateModalForm();
                    $('.create-vendor-modal').modal('hide');
                } else {
                    Notifier.error('Vendor create failed!');
                }
            })
        })


        $('#product-search-form').on('submit', (event)=> {
            event.preventDefault();
            console.log('form submit')
            let filter = $('#product-search-input').val();
            App.productSearch(filter, (result)=> {
                let products = result || [];
                $('#product-search-result-tbody').empty();
                handleSearchResult(filter, products);
            });
        });

        $(document).on('click', '.search-result-item', (event)=> {
            let product = $(event.target).closest('tr').data('productData') || {};
            appendToEntries(product);
        })

        $(document).on('change', '.entry-sku-select', (event)=> {
            console.log('hey')
        })

        $(document).on('click', '.entry-remove', (event)=> {
            let entry = $(event.target).closest('tr');
            entry.remove()
            setTotalAmount();
        });

        $(document).on('change keyup', '.entry-amount-input', (event)=> {
            setTotalAmount();
        })

        $(document).on('click', '#create-purchase-btn', (event)=> {
            let data = getPurchaseDataFromForm();
            createPurchase(data, (result)=> {
                if(result.code == 0) {
                    Notifier.success('Purchase successful');
                    location.reload(true);
                }
            })
        });
    });


    function handleSearchResult(filter, product_result) {
        let products = product_result || [];
        let productLen = products.length;
        if(productLen==0) {
            Notifier.error(`No product found for ${filter} in the system`);
        } else if(productLen == 1) {
            let product = products[0];
            appendToEntries(product);
        } else {
            appendToSearchResults(products);
        }
    }

    function appendToSearchResults(products) {
        let base = $('#product-search-result-tbody');
        products.forEach((product)=> {
            let $row = $(`<tr></tr>`).html(`<td>${product.name}</td>`).addClass('search-result-item');
            $row.data('productData', product)
            base.append($row);
        })
    }

    function appendToEntries(product_result) {
        let $selector = $('#purchase-entries-tbody')
        let product = product_result || {};
        let stockUnits = product.stock_units || [];
        let name = product.name;
        let createSku = App.createSelect(stockUnits, 1);
        let $row = $(`<tr></tr>`).html(`<td>${name}</td>
                                        <td><select class="entry-sku-select form-control"></select></td>
                                        <td><input type="number" class="form-control entry-unit-price-input"></td>
                                        <td><input type="number" class="form-control entry-quantity-input"/></td>
                                        <td><input type="number" class="form-control entry-amount-input"/></td>
                                        <td><button class="btn btn-danger entry-remove">X</button></td>
                                        <td><input type="number" readonly class="hidden form-control entry-product-id" value=${product.id} /></td>`).addClass('purchase-entry')
        $row.find('.entry-sku-select').append(createSku);
        $row.find('.entry-sku-select').change();                                
        $selector.prepend($row);
    }

    function openCreateVendorModel() {
        $('.create-vendor-modal').modal('show');
    }


    function setTotalAmount() {
        $('#purchase-total-amount-value').val(calculateTotal());
    }

    function calculateTotal() {
        let total = 0.00;
        $('.entry-amount-input').each(function() {
            let amount = Number($(this).val()) || 0.00;
            total += amount;
        })
        return total;
    }

    function allTotalFieldsSet() {
        $('.entry-amount-input').each(function() {
            if($(this).val() == '') return false;
        })
        return true;
    }

    function createVendor(data, then) {
        let url = `${App.ROOT_URL}/customer`;
        $.ajax({
            type:'post',
            url:url,
            data:JSON.stringify(data),
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

    function getVendorDataFromForm() {
        let data = {};
        data.name = $('#vendor-name').val();
        data.phone = $('#vendor-phone').val();
        data.address = $('#vendor-address').val();
        return data;
    }


    function createPurchase(data, then) {
        let url = `${App.ROOT_URL}/purchase`;
        $.ajax({
            type:'post',
            url:url,
            data:JSON.stringify(data),
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

    function resetCreateModalForm() {
        $('#create-vendor-form').trigger('reset');
    }

    function getPurchaseDataFromForm() {
        let data = {};
        let entries = [];
        data.reference_code = $('#invoice-ref-code-input').val();
        data.amount = $('#purchase-total-amount-value').val();
        data.customer_id = $('#purchase-vendor-select').val();
        $('.purchase-entry').each(function() {
            let entry = {};
            let $entry = $(this).closest('tr');
            entry.product_id = $entry.find('.entry-product-id').val();
            entry.stock_unit_id = $entry.find('.entry-sku-select option:selected').val();
            entry.quantity = $entry.find('.entry-quantity-input').val();
            entry.cost_price = $entry.find('.entry-unit-price-input').val();
            entry.amount = $entry.find('.entry-amount-input').val();
            entries.push(entry);
        });
        data.entries = entries;
        return data;
    }
})(jQuery)