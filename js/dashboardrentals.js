CRM.$(function ($) {

    $("a.add-rental").click(function( event ) {
        event.preventDefault();
        var href = $(this).attr('href');
        href = href + "&dialogue=1";
        var $el =CRM.loadForm(href, {
            dialog: {width: '50%', height: '50%'}
        }).on('crmFormSuccess', function() {
            var hm_tab = $('.selector-rentals');
            var hm_table = hm_tab.DataTable();
            hm_table.draw();
        });
    });


    var rentals_sourceUrl = CRM.vars.source_url['rentals_source_url'];

    $(document).ready(function () {
        //Reset Table, add Filter and Search Possibility
        //rentals datatable
        var rentals_tab = $('.selector-rentals');
        var rentals_table = rentals_tab.DataTable();
        var rentals_dtsettings = rentals_table.settings().init();
        rentals_dtsettings.bFilter = true;
        //turn on search

        rentals_dtsettings.sDom = '<"crm-datatable-pager-top"lp>Brt<"crm-datatable-pager-bottom"ip>';
        //turn of search field
        rentals_dtsettings.sAjaxSource = rentals_sourceUrl;
        rentals_dtsettings.fnInitComplete = function (oSettings, json) {
        };
        rentals_dtsettings.fnDrawCallback = function (oSettings) {
            // $("a.view-rental").css('background','red');
            $("a.view-rental").off("click").click(function (event) {
                event.preventDefault();
                var href = $(this).attr('href');
                href = href + "&dialogue=1";
                var $el = CRM.loadForm(href, {
                    dialog: {width: '50%', height: '50%'}
                }).on('crmFormSuccess', function () {
                    var hm_tab = $('.selector-rentals');
                    var hm_table = hm_tab.DataTable();
                    hm_table.draw();
                });
            });
            // $("a.update-rental").css('background','blue');
            $("a.update-rental").off("click").click(function (event) {
                event.preventDefault();
                var href = $(this).attr('href');
                href = href + "&dialogue=1";
                var $el = CRM.loadForm(href, {
                    dialog: {width: '50%', height: '50%'}
                }).on('crmFormSuccess', function () {
                    var hm_tab = $('.selector-rentals');
                    var hm_table = hm_tab.DataTable();
                    hm_table.draw();
                });
            });
            $("a.delete-rental").off("click").click(function (event) {
                event.preventDefault();
                var href = $(this).attr('href');
                href = href + "&dialogue=1";
                var $el = CRM.loadForm(href, {
                    dialog: {width: '50%', height: '50%'}
                }).on('crmFormSuccess', function () {
                    var hm_tab = $('.selector-rentals');
                    var hm_table = hm_tab.DataTable();
                    hm_table.draw();
                });
            });
        };
        rentals_dtsettings.fnServerData = function ( sSource, aoData, fnCallback ) {
            aoData.push({ "name": "tenant_id",
                "value": $('#tenant_id').val() });
            aoData.push({ "name": "year",
                "value": $('#months_0  :selected').val() });
            aoData.push({ "name": "month",
                "value": $('#months_1  :selected').val() });
            $.ajax( {
                "dataType": 'json',
                "type": "POST",
                "url": sSource,
                "data": aoData,
                "success": fnCallback
            });
        };
        rentals_table.destroy();
        var new_rentals_table = rentals_tab.DataTable(rentals_dtsettings);
        //End Reset Table
        $('.dashboard-filter :input').change(function(){
            // alert('Filter Changed!');
            new_rentals_table.draw();
        });
    });
});