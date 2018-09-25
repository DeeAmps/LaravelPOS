(function($){
    $(document).ready(()=> {
        redirectToInventory();
    });

    $(document).on('click', '.menu-toggle', toggleMenu);





    function toggleMenu(event) {
        let $menuWrapper = $('.sidebar-wrapper');
        $menuWrapper.toggle('500', ()=> {
            console.log('hey')
        });
    }


    $(document).on('click', '#mod-inventory', (event)=> {

    })

    function redirectToInventory() {
        let url = `${App.ROOT_URL}/inventory`
    }


})(jQuery)