(function($){
    // let token = $('meta[name="csrf-token"]').attr('content');
    $(document).ready(()=> {
        
    });

    $(document).on('click', '.barcode-edit-btn', (event)=> {
        console.log('edit')
        let $entry = $(event.target).closest('tr');
        let barcodeId = $entry.find('.barcode-id').text();
        if(!isNaN(barcodeId)) {
            getBarcodeJSONById(barcodeId, (result)=> {
                console.log(result);
                if(result.code == 0) {
                    initEditModal(result);
                    $('.barcode-edit-modal').modal('show');
                } else {
                    Notifier.error('Could not get product');
                }
            });
        } else {
            Notifier.error('ENtry has no ID');
        }
        
    });

    $(document).on('click', '.barcode-edit-update-btn', (event)=> {
        event.preventDefault();
        let id = $('#barcode-edit-id').val();
        let data = getDataFromBarcodeEditForm();
        console.log(data);
        updateBarcode(data, id, (result)=> {
            if((result.code) || '' == 0) {
                Notifier.success((result.message) || '');
                location.reload(true);
            } else if((result.code)|| ''==1) {
                Notifier.error((result.error_message)||'');

            }
        });
    });

    $(document).on('click', '.product-create-modal-submit-btn', handleSubmitProductCreateForm)

    $(document).on('click','.create-product-modal-btn', (event)=> {
        getCategories((result)=> {
            let data = result || [];
            console.log(result);
            buildCategorySelect(data)
            $('#create-product-modal').modal('show');
        });
        
    })

    $(document).on('click', '.barcode-delete-btn', handleDeleteBarcode);



    $(document).on('click', '.create-barcode-btn', (event)=> {
        console.log('create');
        let url = `${App.ROOT_URL}/inventory/barcode/create`;
        location.href = url;
    });

    function initEditModal(dataObj) {
        console.log(dataObj);
        let data = (dataObj.barcode) || {};
        let products = (dataObj.products) || [];
        buildProductSelect(products, data.product_id);
        $('#barcode-edit-id').val((data.id) || '');
        $('#barcode-edit-name').val((data.name) || '');
        $('#barcode-edit-barcode').val((data.barcode) || '');
        $('#barcode-edit-description').val((data.description) || '');
    }

    function buildProductSelect(dataList, defaultId) {
        let products = dataList || [];
        let $select = $('#barcode-edit-product');
        products.forEach((product)=> {
            console.log(product.name);
            let $option = `<option value=${product.id}>${product.name}</option>`
            $select.append($option);
        });
        $select.val(defaultId);
    }


    function getBarcodeJSONById(id, then) {
        let url = `${App.ROOT_URL}/inventory/barcode/${id}/edit`;
        let loader = $('#productDetailLoader');
        $.ajax({
            url:url,
            type:'get',
            headers: {
                'CSRF-TOKEN': App.token,
                'X-CSRF-TOKEN': App.token,
                'XSRF-TOKEN': App.token
            }
        })
        .done((result)=> {
            then(result)
        })
        .fail(App.handleRequestFailure);
    }

    function updateBarcode(data, id, then) {
        let url = `${App.ROOT_URL}/inventory/barcode/${id}`;
        $.ajax({
            type:'put',
            url:url,
            data:JSON.stringify(data),
            dataType:'json',
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

    function getDataFromBarcodeEditForm() {
        let productId = $('#barcode-edit-product').val();
        let barcodeName = $('#barcode-edit-name').val()
        let barcodeBarcode = $('#barcode-edit-barcode').val();
        let barcodeDescription = $('#barcode-edit-description').val();
        let barcodeId = $('#barcode-edit-id').val();
        let data = {product_id:productId, name:barcodeName, barcode:barcodeBarcode, description:barcodeDescription}; 
        return data;
    }

    function handleDeleteBarcode(event) {
        let $entry = $(event.target).closest('tr');
        let barcodeId = $entry.find('.barcode-id').text();
        console.log(barcodeId);
        let confirmDelete = confirm('Are you sure you want to delete?');
        if(confirmDelete) {
            deleteBarcode(barcodeId, (result)=> {
                console.log(result);
                if((result.code) || ''==0) {
                    Notifier.success((result.message) || '');
                    $entry.remove();
                } else if((result.code)||'' == 1) {
                    Notifier.error((result.error_message)||'');
                }
            })
        }
    }

    function deleteBarcode(id, then) {
            let url = `${App.ROOT_URL}/inventory/barcode/${id}`;
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

    function getCategories(then) {
        let url = `${App.ROOT_URL}/inventory/category`;
        $.ajax({
            url:url,
            type:'get',
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

    function buildCategorySelect(dataList){
        let categories = dataList || [];
        categories.forEach((category)=> {
            let $option = `<option value=${category.id}>${category.name}</option>` 
            $('#product-category-select').append($option)
        })
    }

    function handleSubmitProductCreateForm(event) {
        let data = {};
        data.category_id = $('#product-category-select').val() || '';
        data.name = $('#product-name-input').val() || '';
        data.manufacturer = $('#product-manufacturer-select').val() || '';
        data.description = $('#product-description-textArea').val() || '';
        createProduct(data, (result)=> {
            if(result.code == 0) {
                Notifier.success(result.message)
                location.reload(true);
            } else {
                Notifier.error('error_message');
            }
        });
    }

    function createProduct(data, then) {
        let url = `${App.ROOT_URL}/inventory/product`;
        $.ajax({
            url:url,
            type:'post',
            dataType:'json',
            data: JSON.stringify(data),
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
})(jQuery)