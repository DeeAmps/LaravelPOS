let App = {

    ROOT_URL: 'http://easypos.dev.cc', //'http://localhost:8000',

    token: $('meta[name="csrf-token"]').attr('content'),

    handleRequestFailure: function(xhr, status, req){
        if(status === 401) {
            location.href = `${App.ROOT_URL}/login`;
        }
        if(status === 'error') {
            let error = xhr.responseJSON.message;
            Notifier.error('Cannot perform operation!');
        }
        let errors = (xhr.responseJSON.errors) || []
        let errorList = Object.keys(errors)[0];
        Notifier.error(errors[errorList][0] || '');
    },

    createSelect: function(list_data) {
        let result = '';
        let options = list_data || [];
        options.forEach((option) => {
            let $option = `<option value=${option.id}>${option.label}</option>`
            result+=$option;
        });
      return result;
    },

    productSearch: (filter, then) => {
        let url = `${App.ROOT_URL}/product-search?filter=${filter}`;
        $.ajax({
            url:url,
            contentType:'application/json; charset=utf-8',
            type:'get',
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
    },

    convertToCurrency: (price_amount)=> {
        let amount = price_amount || 0.00;
        return (price*100).toFixed.toFixed(2);
    }
}   