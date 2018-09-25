(function($) {
    let _TOKEN = $('meta[name="csrf-token"]').attr('content');
    $(document).ready(()=> {
        $(document).on('click', '.user-entry-open', (event)=> {
            let entry = $(event.target).closest('tr');
            let id = entry.find('.user-id').text();
            let url = `${App.ROOT_URL}/admin/users/${id}`;
            location.href = url;
        })

        $(document).on('click', '.get-backup', (event)=> {
            createBackup((result)=> {
                if(resul.code == 0) {
                    Notifier.success('Backup created succesfully');
                    
                }
            })
        })
    })


    function createBackup(then) {
        let url = `${App.ROOT_URL}/admin/backup/create`;
        console.log(url);
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


    function getBackup() {
        let url = 'https://api.dropboxapi.com/2/files/list_folder';
        console.log(url);
        $.ajax({
            type:'get',
            url:url,
            dataType:'json',
            constentType:'application/json; charset=utf-8',
            contentType:'application/json; charset=utf-8',
            headers: {
                "Authorization": "Bearer <ACCESS_TOKEN>"
            },
        })
        .done((result)=> {
            then(result);
        })
        .fail(App.handleRequestFailure);
    }
})(jQuery)