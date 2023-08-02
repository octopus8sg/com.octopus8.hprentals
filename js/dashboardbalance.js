CRM.$(function ($) {

    var balance_sourceUrl = CRM.vars.source_url['balance_source_url'];

    $(document).ready(function () {
        //Reset Table, add Filter and Search Possibility
        //balance datatable
        var balance_tab = $('.selector-balance');
        var balance_table = balance_tab.DataTable();
        var balance_dtsettings = balance_table.settings().init();
        balance_dtsettings.bFilter = true;
        //turn on search

        balance_dtsettings.sDom = '<"crm-datatable-pager-top"lp>Brt<"crm-datatable-pager-bottom"ip>';
        //turn of search field
        balance_dtsettings.sAjaxSource = balance_sourceUrl;
        balance_dtsettings.fnInitComplete = function (oSettings, json) {
        };
        balance_dtsettings.fnDrawCallback = function (oSettings) {
        };
        balance_dtsettings.fnServerData = function ( sSource, aoData, fnCallback ) {
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
        balance_table.destroy();
        var new_balance_table = balance_tab.DataTable(balance_dtsettings);
        //End Reset Table
        $('.dashboard-filter :input').change(function(){
            // alert('Filter Changed!');
            new_balance_table.draw();
        });
    });
});