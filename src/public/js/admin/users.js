(function($) {
    let _TOKEN = $('meta[name="csrf-token"]').attr('content');
    $(document).ready(()=> {
        $(document).on('click', '.user-entry-open', (event)=> {
            let entry = $(event.target).closest('tr');
            let id = entry.find('.user-id').text();
            let url = `${App.ROOT_URL}/admin/users/${id}`;
            location.href = url;
        })

        $(document).on('click', '.user-entry-delete', (event)=> {
            let id = $(event.target).closest('tr').find('.user-id').text();
            deleteUser(id, (result)=> {
                if(result.code == 0) {
                    Notifier.success('User deleted!');
                    location.reload(true);
                }
            })
        })
    })


    function deleteUser(id) {
        let url = `${App.ROOT_URL}/admin/users/${id}`;
        console.log(url);
        $.ajax({
            type:'delete',
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
})(jQuery)