(function($){
    let _TOKEN = $('meta[name="csrf-token"]').attr('content');
    $(document).ready(()=> {

        $('#date-filter').datepicker({
            maxDate:0,
            dateFormat: "dd-mm-yy",
            changeMonth: true,
            changeYear: true,
            defaultDate: 0,
            gotoCurrent: true,
            showAnim: "slideDown",
            showOtherMonths: true
        });

        $('#from-date-filter').datepicker({
            maxDate:0,
            dateFormat: "dd-mm-yy",
            changeMonth: true,
            changeYear: true,
            defaultDate: 0,
            gotoCurrent: true,
            showAnim: "slideDown",
            showOtherMonths: true
        });

        $('#to-date-filter').datepicker({
            maxDate:1,
            dateFormat: "dd-mm-yy",
            changeMonth: true,
            changeYear: true,
            defaultDate: 0,
            gotoCurrent: true,
            showAnim: "slideDown",
            showOtherMonths: true
        });

        $('#sale-report-filter-form').on('submit', (event)=> {
            event.preventDefault();
            let data = getReportFilterDataFromForm() || {};
            generateSaleReport(data, (result)=> {
                $('#sale-report-tbody').empty();
                handleReportSearchResult(result);
            })
        });


        $(document).on('change', '#toggle-filter', (event)=> {
            let $checkBox = $(event.target);
            let isChecked = $checkBox.is(':checked');
            if(!isChecked) {
                $('#from-date-filter').val('');
                $('#to-date-filter').val('');
                $('#advance-search-wrapper').addClass('hidden');
                $('#date-wrapper').removeClass('hidden');
            } else {
                $('#date-filter').val('');
                $('#advance-search-wrapper').removeClass('hidden');
                $('#date-wrapper').addClass('hidden');
            }
        })

        $(document).on('click', '.report-entry', (event)=>{
            let $entry = $(event.target).closest('tr');
            let data = $entry.data('report_data') || {}
            let productList = data.entry_data || [];
            $('.report-entry').each(function() {
                $(this).removeClass('report-entry-active');
            })
            $entry.addClass('report-entry-active');
            handleReportEntryClick(productList);
        });

        initSaleReportPage();
    })



    function initSaleReportPage() {
        $('#sale-report-filter-form').submit();
    }

    function getReportFilterDataFromForm() {
        let user = $('#user-filter option:selected').val() || '';
        let date = $('#date-filter').val() || '';
        let fromDate = $('#from-date-filter').val() || '';
        let toDate = $('#to-date-filter').val() || '';
        let data = {};
        if(user) {
            data.user = user;
        }
        if(date) {
            data.date = date;
        } if(fromDate) {
            data.from_date = fromDate;
        }
        if(toDate) {
            data.to_date = toDate;
        }
        return data;
    }


    function generateSaleReport(sale_data, then) {
        let data = sale_data || {};
        let url = `${App.ROOT_URL}/report/sale/search`;
        $.ajax({
            type:'get',
            url:url,
            data:data,
            dataType:'json',
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

    function handleReportSearchResult(result) {
        console.log(result);
        let reportData = result || {};
        let reportTotal = (reportData.total/100).toFixed(2) || 0.00;
        let reports = reportData.reports || [];
        let detail = reportData.detail || '';
        $('#report-detail-user').val(reportData.sales_rep || "");
        $('#report-detail-duration').val(reportData.duration || "");
        $('.report-total-amount-for-period').val(reportTotal);
        reports.forEach((report)=> {
            appendToReports(report);
        });
    }


    function handleReportEntryClick(product_entries) {
        $('#report-product-tbody').empty();
        let products = product_entries || [];
        products.forEach((product)=> {
            appendReportProductList(product);
        })
    }


    function appendReportProductList(product_entry) {
        let product = product_entry || {};
        $select = $('#report-product-tbody');
        $row = $('<tr></tr>').html(`<td>${product.product_name}</td>
                                    <td>${product.ordered_quantity || ''} ${product.stock_unit_name || ''}</td>
                                    <td>${product.unit_price || ''}</td>
                                `).addClass('report-product-entry');
        $select.append($row);   
    }

    function appendToReports(report) {
        $select = $('#sale-report-tbody');
        $row = $('<tr></tr>').html(`<td>${new Date(report.created_on).toDateString() || '--'}</td>
                                    <td>${report.reference_code || '--'}</td>
                                    <td>${report.creator || '--'}</td>
                                    <td>${report.customer}</td>
                                    <td>${(report.amount_paid/100) || 0.00} ${report.currency || ''}</td>
                                `).addClass('report-entry');
        $row.data('report_data', report);                        
        $select.append($row);                        
    }
})(jQuery)