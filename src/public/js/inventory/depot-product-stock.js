(function($){
    $(document).ready(()=> {
        
    });
    $(document).on('click', '.add-entry-to-list-btn', (event)=> {
        let $productEntry = $(event.target).closest('tr');
        let product = $productEntry.find('.product-id').text();
        console.log(product)
        let stockUnit = $productEntry.find('.product-sku-select').val();
        getStockUnitDetailForProduct(product, stockUnit, (result)=> {
            console.log(result);
        });
    })

    function getStockUnitDetailForProduct(product_id, stock_unit_id, then) {
        let productId = product_id;
        let stockunitId = stock_unit_id;
        console.log(productId);
        let url = `${App.ROOT_URL}/inventory/product/${productId}/stock-units/${stockunitId}`;
        $.ajax({
            url:url,
            type:'get',
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


    function getStockTrStr(stocks) {
        let 
    }
})(jQuery)