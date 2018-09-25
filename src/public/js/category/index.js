(function($){
    let token = $('meta[name="csrf-token"]').attr('content');
    $(document).ready(()=> {

    });

    $(document).on('click', '.category-delete', (event)=> {
        event.preventDefault();
        let entryRow = $(event.target).closest('tr');
        let categoryId = entryRow.find('.category-id').text();
        deleteCategory(categoryId, (result)=> {
            Notifier.success('Delete successful');
            location.href = `${App.ROOT_URL}/inventory/category`;
        });
    })


    $(document).on('click', '.category-edit', (event)=> {
        event.preventDefault();
        let entryRow = $(event.target).closest('tr');
        let categoryId = entryRow.find('.category-id').text();
        getCategory(categoryId, (result)=> {
            let name = (result.name) || '';
            let description = (result.description) || '';
            console.log(result);
            $('#category-edit-id').val(result.id);
            $('#category-edit-modal').modal('show');
            $('#category-edit-name').val(name)
            $('#category-edit-description').val(description);
        });
    });

    $(document).on('click', '#btn-update-category', (event)=> {
        console.log('update')
        let data = {};
        let name = $('#category-edit-name').val();
        let description = $('#category-edit-description').val();
        let id = $('#category-edit-id').val();
        data.name = name;
        data.description = description;
        updateCategory(id, data, (result)=> {
            location.reload(true);
        });
    })


    function deleteCategory(id, then) {
        let categoryId = id || '';
        let url = `${App.ROOT_URL}/inventory/category/${categoryId}`;
        let tokenz = 
        $.ajax({
            type:'delete',
            url:url,
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


    function getCategory(id, then) {
        let url = `${App.ROOT_URL}/inventory/category/${id}/edit`;
        $.ajax({
            type:'get',
            url:url,
            dataType: 'json',
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

    function updateCategory(id, categoryData, then) {
        let url = `${App.ROOT_URL}/inventory/category/${id}`;
        let data = categoryData || {};
        console.log(data);
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


})(jQuery)