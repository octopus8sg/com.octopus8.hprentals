CRM.$(function ($) {

    $("a.add-expense").click(function( event ) {
        event.preventDefault();
        var href = $(this).attr('href');
        href = href + "&dialogue=1";
        // alert(href);
        var $el =CRM.loadForm(href, {
            dialog: {width: '50%', height: '50%'}
        }).on('crmFormSuccess', function() {
            var hm_tab = $('.selector-expenses');
            var hm_table = hm_tab.DataTable();
            hm_table.draw();
        }).close;
    });


    var expenses_sourceUrl = CRM.vars.source_url['expenses_source_url'];

    $(document).ready(function () {
        //Reset Table, add Filter and Search Possibility
        //expenses datatable
        var expenses_tab = $('.selector-expenses');
        var expenses_table = expenses_tab.DataTable();
        var expenses_dtsettings = expenses_table.settings().init();
        expenses_dtsettings.bFilter = true;
        //turn on search

        expenses_dtsettings.sDom = '<"crm-datatable-pager-top"lp>Brt<"crm-datatable-pager-bottom"ip>';
        //turn of search field
        expenses_dtsettings.sAjaxSource = expenses_sourceUrl;
        expenses_dtsettings.fnInitComplete = function (oSettings, json) {
        };
        expenses_dtsettings.fnDrawCallback = function (oSettings) {
            // $("a.view-expense").css('background','red');
            $("a.view-expense").off("click").click(function (event) {
                event.preventDefault();
                var href = $(this).attr('href');
                href = href + "&dialogue=1"
                // alert(href);
                var $el =CRM.loadForm(href, {
                    dialog: {width: '50%', height: '50%'}
                }).on('crmFormSuccess', function() {
                    var hm_tab = $('.selector-expenses');
                    var hm_table = hm_tab.DataTable();
                    hm_table.draw();
                }).close;
            });
            // $("a.update-expense").css('background','blue');
            $("a.update-expense").off("click").click(function (event) {
                event.preventDefault();
                var href = $(this).attr('href');
                href = href + "&dialogue=1"
                // alert(href);
                var $el =CRM.loadForm(href, {
                    dialog: {width: '50%', height: '50%'}
                }).on('crmFormSuccess', function() {
                    var hm_tab = $('.selector-expenses');
                    var hm_table = hm_tab.DataTable();
                    hm_table.draw();
                }).close;
            });
            $("a.delete-expense").off("click").click(function (event) {
                event.preventDefault();
                var href = $(this).attr('href');
                href = href + "&dialogue=1"
                // alert(href);
                var $el =CRM.loadForm(href, {
                    dialog: {width: '50%', height: '50%'}
                }).on('crmFormSuccess', function() {
                    var hm_tab = $('.selector-expenses');
                    var hm_table = hm_tab.DataTable();
                    hm_table.draw();
                }).close;
            });
        };
        expenses_dtsettings.fnServerData = function ( sSource, aoData, fnCallback ) {
            aoData.push({ "name": "expense_id",
                "value": $('#expense_id').val() });
            aoData.push({ "name": "expense_name",
                "value": $('#expense_name').val() });
            $.ajax( {
                "dataType": 'json',
                "type": "POST",
                "url": sSource,
                "data": aoData,
                "success": fnCallback
            });
        };
        expenses_table.destroy();
        var new_expenses_table = expenses_tab.DataTable(expenses_dtsettings);
        //End Reset Table
        $('.expense-filter :input').keyup(function(){
            // alert('Filter Changed!');
            new_expenses_table.draw();
        });
    });
});