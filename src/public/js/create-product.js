(function($){
    let tokenz = $('meta[name="csrf-token"]').attr('content');
    $(document).ready(()=> {
        
    });


    $(document).on('click', '#create-category-btn', ()=> {
        console.log('category cretae')
        $('.modal-create-category').modal('show');
    });


    $(document).on('click', '#create-next-barcode-btn', (event)=> {
        let productName = $('#product-name').val();
        console.log(productName);
        $('#create-barcode-form-wrapper').removeClass('hidden');
    });



    $('#create-product-form').on('submit', (event)=> {
        event.preventDefault();
        let categoryId = $('#product-category').val();
        let product = $('#product-name').val();
        let description = $('#product-description').val();
        let manufacturer = $('#product-manufacturer').val();
        let data = {};
        data.manufacturer = manufacturer;
        data.name = product;
        data.description = description;
        data.category_id = categoryId;
        console.log(data);
        createProduct(data, (result)=> {
            if(result.code == 0) {
                Notifier.success(result.message);
                clearProductCreateForm();
            } else {
                Notifier.error(result.message);
            }
        })
    })




    $('#product-search-form').on('submit', (event)=> {
        event.preventDefault();
        let filterValue = $('#product-search').val();
        let data ={};
        data.filter = filterValue;
        console.log('hey am search')
        let url =`http://localhost:8000/inventory/product-search`;
        $.ajax({
            url:url,
            data:data,
            type:'get',
            contentType:'application/json; charset=utf-8',
            dataType:'json',
            headers: {
                'CSRF-TOKEN': tokenz,
                'X-CSRF-TOKEN': tokenz,
                'XSRF-TOKEN': tokenz
            }
        })
        .done((result)=> {
            console.log(result);
            addProductsToLists(result);
            
        })
        .fail(failureHandler);
    })




    $(document).on('click', '#create-product-continue', (event)=> {
        event.preventDefault();
        event.preventDefault();
        let categoryId = $('#select-category').val();
        let product = $('#product-name').val();
        let description = $('#product-description').val();
        let manufacturer = $('#product-manufacturer').val();
        let data = {};
        data.manufacturer = manufacturer;
        data.name = product;
        data.description = description;
        data.category_id = categoryId;
        console.log(data);
        createProduct(data, (result)=> {
            Notifier.success(result.message);
        })
    })


    $(document).on('click', '#create-category-submit', (event)=> {
        event.preventDefault();
        let name = $('#category-name').val();
        let description = $('#category-description').val();
        let token = $('#csrf-token').val();
        let data = {};
        data.name = name;
        data.description = description;
        data.token= token;
        handleCreateCategory(data);
    });

    

    function handleCreateCategory(data) {
        let name = data.name;
        let description = data.description;
        let token = data.token;
        console.log(data);
        $.ajax({
            type:'post',
            url:'http://localhost:8000/inventory/category',
            data:JSON.stringify({'name':name, 'description':description}),
            contentType:'application/json; charset=utf-8',
            headers: {
                'CSRF-TOKEN': token,
                'X-CSRF-TOKEN': token,
                'XSRF-TOKEN': token
            }
            })
            .done((result)=> {
                let response = result || {}
                console.log(result);
                // result.forEach((category)=> {

                // })
                handleBuildCategorySelect(result);
                $('.modal-create-category').modal('hide');
                clearPreviousCategoryOptions();
                buildCategorySelect(response)
            })
            .fail(failureHandler);
    }


    function handleCreateProduct() {
        let categoryId = $('#select-category').val();
        let product = $('#product-name').val();
        let description = $('#product-description').val();
        console.log(categoryId);
    }

    function createProduct(data, then) {
        let url = 'http://localhost:8000/inventory/product'
        $.ajax({
            url:url,
            type:'post',
            data:JSON.stringify(data),
            contentType:'application/json; charset=utf-8',
            dataType:'json',
            headers: {
                'CSRF-TOKEN': tokenz,
                'X-CSRF-TOKEN': tokenz,
                'XSRF-TOKEN': tokenz
            }
        })
        .done((result)=> {
            then(result)
        })
        .fail(App.handleRequestFailure);
    }


    function createBarcode(data, then) {
        let url = `http://localhost:8000/inventory/product/${data.product_id}/barcode`
        console.log(url);
        $.ajax({
            url:url,
            type:'post',
            data:JSON.stringify(data),
            contentType:'application/json; charset=utf-8',
            dataType:'json',
            headers: {
                'CSRF-TOKEN': tokenz,
                'X-CSRF-TOKEN': tokenz,
                'XSRF-TOKEN': tokenz
            }
        })
        .done((result)=> {
            console.log(result);
            then(result)
        })
        .fail(failureHandler);
    }


    function failureHandler(xhr, status, err) {
        Notifier.error(status);
    }

    function handleBuildCategorySelect(data) {
        clearPreviousCategoryOptions($('categories'));
    }

    function clearPreviousCategoryOptions() {
        $('#select-category').empty();
    }

    function buildCategorySelect(data) {
        let $selector = $('#select-category');
        data.forEach(element => {
            let label = element.name;
            let id = element.id;
            $option = `<option value=${id}>${label}</option>`
            $selector.append($option);
        });
    }

    function addProductsToLists(result) {
        let products = result || [];
        $('#product-list-table-body').empty();
        products.forEach((product)=> {
            $row = `<tr id=${product.id}><td>${product.name}</td><td><button class="btn btn-default">
            Add barcode Unit</button></td><tr>'`;
            $('#product-list-table-body').append($row);
        });
    }

    function clearProductCreateForm() {
        $('#product-name').val('');
        $('#product-description').val('');
        $('#product-manufacturer').val('');
    }
})(jQuery)