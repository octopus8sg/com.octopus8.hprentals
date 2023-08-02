CRM.$(function ($) {

    $("a.add-payment").click(function( event ) {
        event.preventDefault();
        var href = $(this).attr('href');
        href = href + "&dialogue=1";
        // alert(href);
        var $el =CRM.loadForm(href, {
            dialog: {width: '50%', height: '50%'}
        }).on('crmFormSuccess', function() {
            var hm_tab = $('.selector-payments');
            var hm_table = hm_tab.DataTable();
            hm_table.draw();
        }).close;
    });


    var payments_sourceUrl = CRM.vars.source_url['payments_source_url'];

    $(document).ready(function () {
        //Reset Table, add Filter and Search Possibility
        //payments datatable
        var payments_tab = $('.selector-payments');
        var payments_table = payments_tab.DataTable();
        var payments_dtsettings = payments_table.settings().init();
        payments_dtsettings.bFilter = true;
        //turn on search

        payments_dtsettings.sDom = '<"crm-datatable-pager-top"lp>Brt<"crm-datatable-pager-bottom"ip>';
        //turn of search field
        payments_dtsettings.sAjaxSource = payments_sourceUrl;
        payments_dtsettings.fnInitComplete = function (oSettings, json) {
        };
        payments_dtsettings.aoColumns = [
            null,
            null,
            null,
            null,
            null,
            { "sClass": "right", "mRender": function(data, type, row) {
                    return '$' + data;
                } }
        ];

        payments_dtsettings.fnFooterCallback = function (nFoot, aData, iStart, iEnd, aiDisplay) {
            var total = 0;
            for (var i = iStart; i < iEnd; i++) {
                total += parseFloat(aData[aiDisplay[i]][5]);
            }
            // console.log($(nFoot));
            $(nFoot).find('#p_total_sum').html('<i>$' + total.toFixed(2) + '</i>');
        };
        payments_dtsettings.fnDrawCallback = function (oSettings) {
            // $("a.view-payment").css('background','red');
            $("a.view-payment").off("click").click(function (event) {
                event.preventDefault();
                var href = $(this).attr('href');
                href = href + "&dialogue=1"
                // alert(href);
                var $el =CRM.loadForm(href, {
                    dialog: {width: '50%', height: '50%'}
                }).on('crmFormSuccess', function() {
                    var hm_tab = $('.selector-payments');
                    var hm_table = hm_tab.DataTable();
                    hm_table.draw();
                }).close;
            });
            // $("a.update-payment").css('background','blue');
            $("a.update-payment").off("click").click(function (event) {
                event.preventDefault();
                var href = $(this).attr('href');
                href = href + "&dialogue=1"
                // alert(href);
                var $el =CRM.loadForm(href, {
                    dialog: {width: '50%', height: '50%'}
                }).on('crmFormSuccess', function() {
                    var hm_tab = $('.selector-payments');
                    var hm_table = hm_tab.DataTable();
                    hm_table.draw();
                }).close;
            });
            $("a.delete-payment").off("click").click(function (event) {
                event.preventDefault();
                var href = $(this).attr('href');
                href = href + "&dialogue=1"
                // alert(href);
                var $el =CRM.loadForm(href, {
                    dialog: {width: '50%', height: '50%'}
                }).on('crmFormSuccess', function() {
                    var hm_tab = $('.selector-payments');
                    var hm_table = hm_tab.DataTable();
                    hm_table.draw();
                }).close;
            });
        };
        payments_dtsettings.fnServerData = function ( sSource, aoData, fnCallback ) {
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
        payments_table.destroy();
        var new_payments_table = payments_tab.DataTable(payments_dtsettings);
        //End Reset Table
        $('.dashboard-filter :input').change(function(){
            // alert('Filter Changed!');
            new_payments_table.draw();
        });
    });
});