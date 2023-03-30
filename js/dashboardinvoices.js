CRM.$(function ($) {

    $("a.add-invoice").click(function( event ) {
        event.preventDefault();
        var href = $(this).attr('href');
        href = href + "&dialogue=1";
        // alert(href);
        var $el =CRM.loadForm(href, {
            dialog: {width: '50%', height: '50%'}
        }).on('crmFormSuccess', function() {
            var hm_tab = $('.selector-invoices');
            var hm_table = hm_tab.DataTable();
            hm_table.draw();
        }).close;
    });


    var invoices_sourceUrl = CRM.vars.source_url['invoices_source_url'];

    $(document).ready(function () {
        //Reset Table, add Filter and Search Possibility
        //invoices datatable
        var invoices_tab = $('.selector-invoices');
        var invoices_table = invoices_tab.DataTable();
        var invoices_dtsettings = invoices_table.settings().init();
        invoices_dtsettings.bFilter = true;
        //turn on search

        invoices_dtsettings.sDom = '<"crm-datatable-pager-top"lp>Brt<"crm-datatable-pager-bottom"ip>';
        //turn of search field
        invoices_dtsettings.sAjaxSource = invoices_sourceUrl;
        invoices_dtsettings.aoColumns = [
            null,
            null,
            null,
            null,
            { "sClass": "right", "mRender": function(data, type, row) {
                    return '$' + data;
                } }
        ];
        invoices_dtsettings.fnInitComplete = function (oSettings, json) {
        };
        invoices_dtsettings.fnFooterCallback = function (nFoot, aData, iStart, iEnd, aiDisplay) {
            var total = 0;
            for (var i = iStart; i < iEnd; i++) {
                total += parseFloat(aData[aiDisplay[i]][4]);
            }
            // console.log($(nFoot));
            $(nFoot).find('#i_total_sum').html('<i>$' + total.toFixed(2) + '</i>');
        }
        invoices_dtsettings.fnDrawCallback = function (oSettings) {
            // $("a.view-invoice").css('background','red');
            $("a.view-invoice").off("click").click(function (event) {
                event.preventDefault();
                var href = $(this).attr('href');
                href = href + "&dialogue=1"
                // alert(href);
                var $el =CRM.loadForm(href, {
                    dialog: {width: '50%', height: '50%'}
                }).on('crmFormSuccess', function() {
                    var hm_tab = $('.selector-invoices');
                    var hm_table = hm_tab.DataTable();
                    hm_table.draw();
                }).close;
            });
            // $("a.update-invoice").css('background','blue');
            $("a.update-invoice").off("click").click(function (event) {
                event.preventDefault();
                var href = $(this).attr('href');
                href = href + "&dialogue=1"
                // alert(href);
                var $el =CRM.loadForm(href, {
                    dialog: {width: '50%', height: '50%'}
                }).on('crmFormSuccess', function() {
                    var hm_tab = $('.selector-invoices');
                    var hm_table = hm_tab.DataTable();
                    hm_table.draw();
                }).close;
            });
            $("a.delete-invoice").off("click").click(function (event) {
                event.preventDefault();
                var href = $(this).attr('href');
                href = href + "&dialogue=1"
                // alert(href);
                var $el =CRM.loadForm(href, {
                    dialog: {width: '50%', height: '50%'}
                }).on('crmFormSuccess', function() {
                    var hm_tab = $('.selector-invoices');
                    var hm_table = hm_tab.DataTable();
                    hm_table.draw();
                }).close;
            });
        };
        invoices_dtsettings.fnServerData = function ( sSource, aoData, fnCallback ) {
            aoData.push({ "name": "tenant_id",
                "value": $('#tenant_id').val() });
            $.ajax( {
                "dataType": 'json',
                "type": "POST",
                "url": sSource,
                "data": aoData,
                "success": fnCallback
            });
        };
        invoices_table.destroy();
        var new_invoices_table = invoices_tab.DataTable(invoices_dtsettings);
        //End Reset Table
        $('.dashboard-filter :input').keyup(function(){
            // alert('Filter Changed!');
            new_invoices_table.draw();
        });
    });
});