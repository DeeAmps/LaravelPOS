(function($){
    let token = $('meta[name="csrf-token"]').attr('content');
    $(document).ready(()=> {
        
    });

    $(document).on('click', '.create-product-btn', (event)=> {
            window.location.href = `${App.ROOT_URL}/inventory/product/create`;
    });

    $(document).on('click', '.product-edit-btn', (event)=> {
        event.preventDefault();
        let entryRow = $(event.target).closest('tr')
        let productId = entryRow.find('.product-list-id').text();
        getProductJSONById(productId, (result)=> {
            console.log(result);
            $('.product-edit-modal').modal('show');
            handleInitProductEditForm(result);
        })
    });



    $(document).on('click', '#update-stock', (event)=> {
        let data = getStockUpdateData();
        let productId = _product.id;
        console.log(data);
        let updateConfirm = confirm('Are you sure you want to update stock price?');
        if(updateConfirm) {
            updateStock(productId, data, (result)=> {
                Notifier.success('update successful')
                location.reload(true);
            })
        }
    })

    
    function updateStock(productId, data, then) {
        let url = `${App.ROOT_URL}/inventory/product/${productId}/stock-units/update`;
        console.log(url)
        let loader = $('#productDetailLoader');
        $.ajax({
            url:url,
            type:'put',
            data:JSON.stringify(data),
            contentType:'application/json; charset=utf-8',
            headers: {
                'CSRF-TOKEN': token,
                'X-CSRF-TOKEN': token,
                'XSRF-TOKEN': token
            }
        })
        .done((result)=> {
            then(result)
        })
        .fail(App.handleRequestFailure);
    }

    $(document).on('click', '.product-delete-btn', (event)=> {
        event.preventDefault();
        let entryRow = $(event.target).closest('tr')
        let productId = entryRow.find('.product-list-id').text();
        let confirmDelete = confirm('Are you sure you want to delete this product?');
        if(confirmDelete) {
            deleteProduct(productId, (result)=> {
                Notifier.success(result.message);
                entryRow.remove();
            });
        }
    });


    $(document).on('click', '.product-edit-update-btn', (event)=> {
        event.preventDefault();
        console.log('send');
        handleProductUpdate();
    })

    function getProductById(id, then) {
        let url = `${App.ROOT_URL}/inventory/product/${id}`;
        console.log(url)
        let loader = $('#productDetailLoader');
        $.ajax({
            url:url,
            type:'get',
            headers: {
                'CSRF-TOKEN': token,
                'X-CSRF-TOKEN': token,
                'XSRF-TOKEN': token
            }
        })
        .done((result)=> {
            then(result)
        })
        .fail(App.handleRequestFailure);
    }

    function getProductJSONById(id, then) {
        let url = `${App.ROOT_URL}/inventory/product/${id}/edit`;
        console.log(url)
        let loader = $('#productDetailLoader');
        $.ajax({
            url:url,
            type:'get',
            headers: {
                'CSRF-TOKEN': token,
                'X-CSRF-TOKEN': token,
                'XSRF-TOKEN': token
            }
        })
        .done((result)=> {
            then(result)
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
                'CSRF-TOKEN': token,
                'X-CSRF-TOKEN': token,
                'XSRF-TOKEN': token
            }
        })
        .done((result)=> {
            then(result);
        })
        .fail(App.handleRequestFailure);
    }

    function handleInitProductEditForm(data) {
        let categories = (data.categories) || [];
        let product = (data.product) || {};
        buildCategoryDropdown(categories, product.product_category_id);
        console.log(product.product_category_id)
        $('#product-edit-id').val(product.id);
        $('#product-edit-name').val(product.name);
        $('#product-edit-manufacturer').val(product.manufacturer);
        $('#product-edit-description').val(product.description);
    }

    function buildCategoryDropdown(dataList, defaultId) {
        let $select = $('#product-edit-category');
        dataList.forEach((data)=> {
            let option = `<option value=${data.id}>${data.name}</option>`;
            $select.append(option);
        })
        $select.val(defaultId);
    }

    function handleProductUpdate() {
        let category = $('#product-edit-category option:selected').val();
        let name = $('#product-edit-name').val();
        let manufacturer = $('#product-edit-manufacturer').val();
        let description = $('#product-edit-description').val();
        let id = $('#product-edit-id').val();
        let data = {category_id:category, name:name, description:description, manufacturer:manufacturer}
        console.log(data)
        updateProduct(data, id, (result)=> {
            console.log(result);
            if(result.code == 0) {
                Notifier.success(result.message);
                location.reload(true);
            } else if(result.code == 1){
                Notifier.error(result.error_message);
            }
        })
    }

    function updateProduct(data, id, then) {
        let url = `${App.ROOT_URL}/inventory/product/${id}`;
        console.log(data);
        console.log(url)
        $.ajax({
            type:'put',
            url:url,
            data:JSON.stringify(data),
            dataType:'json',
            contentType:'application/json; charset=utf-8',
            headers: {
                'CSRF-TOKEN': token,
                'X-CSRF-TOKEN': token,
                'XSRF-TOKEN': token
            }
        })
        .done((result)=> {
            then(result);
        })
        .fail(App.handleRequestFailure);
    }

    function getStockUpdateData() {
        let data = [];
        $('#product-stock-table-body').find('tr').each(function(){
            let stock = {};
            let entry = $(this);
            stock.stock_unit_id = entry.find('.sku-id').text();
            stock.cost_price = (entry.find('.cost-price').val()) || 0.00;
            stock.selling_price = (entry.find('.selling-price').val()) || 0.00;
            data.push(stock);
        })
        return data; 
    }


})(jQuery)