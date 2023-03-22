CRM.$(function ($) {

    $("a.add-method").click(function( event ) {
        event.preventDefault();
        var href = $(this).attr('href');
        href = href + "&dialogue=1"
        var $el =CRM.loadForm(href, {
            dialog: {width: '50%', height: '50%'}
        }).on('crmFormSuccess', function() {
            var hm_tab = $('.selector-methods');
            var hm_table = hm_tab.DataTable();
            hm_table.draw();
        });
    });


    var methods_sourceUrl = CRM.vars.source_url['methods_source_url'];

    $(document).ready(function () {
        //Reset Table, add Filter and Search Possibility
        //methods datatable
        var methods_tab = $('.selector-methods');
        var methods_table = methods_tab.DataTable();
        var methods_dtsettings = methods_table.settings().init();
        methods_dtsettings.bFilter = true;
        //turn on search

        methods_dtsettings.sDom = '<"crm-datatable-pager-top"lp>Brt<"crm-datatable-pager-bottom"ip>';
        //turn of search field
        methods_dtsettings.sAjaxSource = methods_sourceUrl;
        methods_dtsettings.fnInitComplete = function (oSettings, json) {
        };
        methods_dtsettings.fnDrawCallback = function (oSettings) {
            // $("a.view-method").css('background','red');
            $("a.view-method").off("click").click(function (event) {
                event.preventDefault();
                var href = $(this).attr('href');
                href = href + "&dialogue=1"
                var $el = CRM.loadForm(href, {
                    dialog: {width: '50%', height: '50%'}
                }).on('crmFormSuccess', function () {
                    var hm_tab = $('.selector-methods');
                    var hm_table = hm_tab.DataTable();
                    hm_table.draw();
                });
            });
            // $("a.update-method").css('background','blue');
            $("a.update-method").off("click").click(function (event) {
                event.preventDefault();
                var href = $(this).attr('href');
                href = href + "&dialogue=1"
                var $el = CRM.loadForm(href, {
                    dialog: {width: '50%', height: '50%'}
                }).on('crmFormSuccess', function () {
                    var hm_tab = $('.selector-methods');
                    var hm_table = hm_tab.DataTable();
                    hm_table.draw();
                });
            });
            $("a.delete-method").off("click").click(function (event) {
                event.preventDefault();
                var href = $(this).attr('href');
                href = href + "&dialogue=1"
                var $el = CRM.loadForm(href, {
                    dialog: {width: '50%', height: '50%'}
                }).on('crmFormSuccess', function () {
                    var hm_tab = $('.selector-methods');
                    var hm_table = hm_tab.DataTable();
                    hm_table.draw();
                });
            });
        };
        methods_dtsettings.fnServerData = function ( sSource, aoData, fnCallback ) {
            aoData.push({ "name": "method_id",
                "value": $('#method_id').val() });
            aoData.push({ "name": "method_name",
                "value": $('#method_name').val() });
            $.ajax( {
                "dataType": 'json',
                "type": "POST",
                "url": sSource,
                "data": aoData,
                "success": fnCallback
            });
        };
        methods_table.destroy();
        var new_methods_table = methods_tab.DataTable(methods_dtsettings);
        //End Reset Table
        $('.method-filter :input').keyup(function(){
            // alert('Filter Changed!');
            new_methods_table.draw();
        });
    });
});