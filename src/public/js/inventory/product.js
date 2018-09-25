(function($){
    let CATEGORY_SELECT_FIELD = $('#category-select');
    let MANUFACTURER_SELECT_FIELD = $('#manufacturer-select');
    let CREATESKUMODAL = $('#create-sku-modal');
    $(document).ready(()=> {
        $(document).on('click', '.product-delete-btn', (event)=> {
            let entry = $(event.target).closest('tr');
            let entryId = entry.find('.product-list-id').text();
            let confirmDelete = confirm('Are you sure you want to delete?');
            if(confirmDelete) {
                deleteProduct(entryId, (result)=> {
                    if(result.code==0) {
                        Notifier.success('Delete successful');
                        entry.remove();
                    } else {
                        Notifier.error(result.error_message);
                    }
                })
            }
        });
        
        $(document).on('click', '.create-category-open-modal', (event)=> {
            $('.create-category-modal').modal('show');
        });

        $(document).on('click', '.create-manufacturer-open-modal', (event)=> {
            $('.create-manufacturer-modal').modal('show');
        });

        $(document).on('click', '#create-category-submit', (event)=> {
            let data = getCategoryDataFromForm();
            createCategory(data, (result)=> {
                if(result.code == 0) {
                    Notifier.success('Category created successfully');
                    $('#create-category-form').trigger('reset');
                    $('.create-category-modal').modal('hide');
                    let options = App.createSelect(result.categories || []);
                    console.log(options);
                    CATEGORY_SELECT_FIELD.empty();
                    CATEGORY_SELECT_FIELD.append(options);
                } 
            });
        });

        $('#create-product-form').on('submit', (event)=> {
            event.preventDefault();
            let data = getCreateProductDataFromForm();
            createProduct(data, (result)=> {
                if(result.code == 0) {
                    Notifier.success('Product created successfully');
                    let url = `${App.ROOT_URL}/inventory/product/${result.product_id}`;
                    location.href = url;
                } else {
                    Notifier.error('Product creation failed!!');
                }
            });
        });

        
        $(document).on('click', '#create-manufacturer-submit', (event)=> {
            let data = getManufacturerDataFromForm();
            createManufacturer(data, (result)=> {
                console.log(result);
                if(result.code == 0) {
                    Notifier.success('Manufacturer created successfully');
                    $('#create-manufacturer-form').trigger('reset');
                    $('.create-manufacturer-modal').modal('hide');
                    let options = App.createSelect(result.manufacturers || []);
                    MANUFACTURER_SELECT_FIELD.empty();
                    MANUFACTURER_SELECT_FIELD.append(options);
                }
            });
        });

        $(document).on('click', '#open-create-sku-modal', (event)=> {
            CREATESKUMODAL.modal('show');
        });

        $(document).on('click', '#create-sku-submit', (event)=> {
            let data = getCreateSkuDataFromForm();
            createSku(data, (result)=> {
                if(result.code == 0) {
                    Notifier.success('sku created successfully!')
                    CREATESKUMODAL.modal('hide');
                    appendSkuToSelect(result.stocks || []);
                }
            })
        })

        $(document).on('click', '.remove-sku', (event)=> {
            let productId = $('#product-detail-id').val();
            let skuId = $(event.target).closest('tr').find('.sku-id').text();
            let data = {product:productId, sku:skuId};
            detachSkuFromProduct(data, (result)=> {
                if(result.code == 0) {
                    Notifier.success('sku removed from product successfully');
                    location.reload(true)
                } else {
                    Notifier.error('sku remove failed!');
                }
            });
        });

        $(document).on('click', '#attach-sku', (event)=> {
            let productId = $('#product-detail-id').val();
            let skuId = $('#product-sku-select option:selected').val()
            let data = {product:productId, stock_unit_id:skuId};
            attachSkuToProduct(data, (result)=> {
                if(result.code == 0) {
                    Notifier.success('sku added to product successfuly');
                    location.reload(true);
                } else {
                    Notifier.error('sku delete failed!');
                }
            });
        })

        $(document).on('click', '.update-selling-price', (event)=> {
            let entry = $(event.target).closest('tr');
            let productId = $('#product-detail-id').val();
            let entrySkuId = entry.find('.sku-id').text();
            let sp = entry.find('.sku-selling-price-entry').val()
            let data = {product:productId, sku:entrySkuId, selling_price:sp};
            console.log(data)
            updateProductSKuPrice(data, (result)=> {
                //console.log(result)
                if(result.code == 0) {
                    Notifier.success('Price updated successfully')
                    location.reload(true);
                } else {
                    Notifier.error('Price update failed!')
                }
            });
        })
    })


    function detachSkuFromProduct(data, then) {
        console.log(data);
        let url = `${App.ROOT_URL}/inventory/product/${data.product}/stock-units/${data.sku}/remove`;
        $.ajax({
            url:url,
            type:'get',
            dataType:'json',
            data:data,
            headers: {
                'CSRF-TOKEN': App.token,
                'X-CSRF-TOKEN': App.token,
                'XSRF-TOKEN': App.token
            }
        })
        .done((result)=> {
            then(result);
        })
        .fail(App.handleRequestFailure);
    }


    function attachSkuToProduct(data, then) {
        let url = `${App.ROOT_URL}/inventory/product/${data.product}/stock-units/add`;
        $.ajax({
            url:url,
            type:'get',
            dataType:'json',
            data:data,
            headers: {
                'CSRF-TOKEN': App.token,
                'X-CSRF-TOKEN': App.token,
                'XSRF-TOKEN': App.token
            }
        })
        .done((result)=> {
            then(result);
        })
        .fail(App.handleRequestFailure);
    }


    function updateProductSKuPrice(data, then) {
        let url = `${App.ROOT_URL}/inventory/product/${data.product}/stock-units/${data.sku}/update-selling-price`;
        $.ajax({
            url:url,
            type:'put',
            dataType:'json',
            data:JSON.stringify(data),
            contentType:'application/json; charset=utf-8',
            headers: {
                'CSRF-TOKEN': App.token,
                'X-CSRF-TOKEN': App.token,
                'XSRF-TOKEN': App.token
            }
        })
        .done((result)=> {
            then(result);
        })
        .fail(App.handleRequestFailure);
    }

    function deleteProduct(id, then) {
        let url = `${App.ROOT_URL}/inventory/product/${id}`;
        $.ajax({
            url:url,
            type:'delete',
            dataType:'json',
            headers: {
                'CSRF-TOKEN': App.token,
                'X-CSRF-TOKEN': App.token,
                'XSRF-TOKEN': App.token
            }
        })
        .done((result)=> {
            then(result);
        })
        .fail(App.handleRequestFailure);
    }


    function createCategory(data, then) {
        let url = `${App.ROOT_URL}/inventory/category`;
        $.ajax({
            url:url,
            type:'post',
            data: JSON.stringify(data),
            contentType: 'application/json; charset=utf-8',
            dataType:'json',
            headers: {
                'CSRF-TOKEN': App.token,
                'X-CSRF-TOKEN': App.token,
                'XSRF-TOKEN': App.token
            }
        })
        .done((result)=> {
            then(result);
        })
        .fail(App.handleRequestFailure);
    }



    function createManufacturer(data, then) {
        let url = `${App.ROOT_URL}/inventory/manufacturer`;
        $.ajax({
            url:url,
            type:'post',
            data: JSON.stringify(data),
            contentType: 'application/json; charset=utf-8',
            dataType:'json',
            headers: {
                'CSRF-TOKEN': App.token,
                'X-CSRF-TOKEN': App.token,
                'XSRF-TOKEN': App.token
            }
        })
        .done((result)=> {
            then(result);
        })
        .fail(App.handleRequestFailure);
    }
    

    function getCategoryDataFromForm() {
        let data={};
        data.name = $('#create-category-name').val();
        data.description = $('#create-category-description').val();
        return data;
    }

    function getManufacturerDataFromForm() {
        let data={};
        data.name = $('#create-manufacturer-name').val();
        data.email = $('#create-manufacturer-email').val();
        data.address = $('#create-manufacturer-address').val();
        data.phone = $('#create-manufacturer-phone').val();
        return data;
    }


    function createProduct(data, then) {
        let url = `${App.ROOT_URL}/inventory/product`;
        $.ajax({
            url:url,
            type:'post',
            data: JSON.stringify(data),
            contentType: 'application/json; charset=utf-8',
            dataType:'json',
            headers: {
                'CSRF-TOKEN': App.token,
                'X-CSRF-TOKEN': App.token,
                'XSRF-TOKEN': App.token
            }
        })
        .done((result)=> {
            then(result);
        })
        .fail(App.handleRequestFailure);
    }


    function getCreateProductDataFromForm() {
        let $form = $('#create-product-form');
        let data = {};
        let productName = $form.find('#brand-input').val().toUpperCase() || '';
        let productModel =$form.find('#model-input').val().toUpperCase() || '';
        let productSize = $form.find('#size-input').val().toUpperCase() || '';
        data.category_id = $form.find('#category-select option:selected').val();
        data.manufacturer_id = $form.find('#manufacturer-select option:selected').val();
        data.barcode = $form.find('#barcode-input').val();
        data.description = $form.find('#description-textArea').val();
        let name = `${productName} ${productModel}`;
        if(productSize) {
            name = name+` ${productSize}`;
        } 
        data.name = name;
        return data;
    }

    function getCreateSkuDataFromForm() {
        let data = {};
        data.label = $('#create-sku-label').val();
        data.quantity = $('#create-sku-quantity').val();
        return data;
    }

    function createSku(data, then) {
        let url = `${App.ROOT_URL}/inventory/stock-units`;
        $.ajax({
            url:url,
            type:'post',
            data: JSON.stringify(data),
            contentType: 'application/json; charset=utf-8',
            dataType:'json',
            headers: {
                'CSRF-TOKEN': App.token,
                'X-CSRF-TOKEN': App.token,
                'XSRF-TOKEN': App.token
            }
        })
        .done((result)=> {
            then(result);
        })
        .fail(App.handleRequestFailure);
    }

    function appendSkuToSelect(skus) {
        let stocks = skus || [];
        let select = $('#product-sku-select');
        let options = App.createSelect(stocks);
        select.empty();
        select.append(options);
    }
})(jQuery)