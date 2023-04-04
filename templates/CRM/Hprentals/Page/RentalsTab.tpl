{crmScope extensionKey='com.octopus8.devices'}
    <div class="rentals-tab view-content">

        <div id="secondaryTabContainer1" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
            {include file="CRM/common/TabSelected.tpl" defaultTab="data" tabContainer="#secondaryTabContainer1"}

            <ul class="ui-tabs-nav ui-corner-all ui-helper-reset ui-helper-clearfix ui-widget-header">
                <li id="tab_rental"
                    class="crm-tab-button ui-corner-all ui-tabs-tab ui-corner-top ui-state-default ui-tab ui-tabs-active ui-state-active">
                    <a href="#rental-subtab" title="{ts}Data{/ts}">
                        {ts}Rentals{/ts} <em>{$rentalCount}</em>
                    </a>
                </li>
                <li id="tab_invoice"
                    class="crm-tab-button ui-corner-all ui-tabs-tab ui-corner-top ui-state-default ui-tab">
                    <a href="#invoice-subtab" title="{ts}Devices{/ts}">
                        {ts}Invoices{/ts} <em>{$invoiceCount}</em>
                    </a>
                </li>
                <li id="tab_payment"
                    class="crm-tab-button ui-corner-all ui-tabs-tab ui-corner-top ui-state-default ui-tab">
                    <a href="#payment-subtab" title="{ts}Analytics{/ts}">
                        {ts}Payments{/ts} <em>{$paymentCount}</em>
                    </a>
                </li>
            </ul>

            <div id="rental-subtab" class="ui-tabs-panel ui-widget-content ui-corner-bottom">
                <crm-angular-js modules="tenantRentalSearch">
                    <form id="bootstrap-theme">
                        <tenant-rental-search options='{$myAfformVars|@json_encode}'></tenant-rental-search>
                    </form>
                </crm-angular-js>
            </div>
            <div id="invoice-subtab" class="invoice-subtab ui-tabs-panel ui-widget-content ui-corner-bottom">
                <crm-angular-js modules="tenantInvoiceSearch">
                    <form id="bootstrap-theme">
                        <tenant-invoice-search options='{$myAfformVars|@json_encode}'></tenant-invoice-search>
                    </form>
                </crm-angular-js>

            </div>
            <div id="payment-subtab" class="payment-subtab ui-tabs-panel ui-widget-content ui-corner-bottom">
                <crm-angular-js modules="tenantPaymentSearch">
                    <form id="bootstrap-theme">
                        <tenant-payment-search options='{$myAfformVars|@json_encode}'></tenant-payment-search>
                    </form>
                </crm-angular-js>

            </div>
            <div class="clear"></div>
        </div>
    </div>
{/crmScope}

{literal}
    <script type="text/javascript">
        CRM.$(function ($) {
            $('input.hasDatepicker')
                .crmDatepicker({
                    format: "yy-mm-dd",
                    altFormat: "yy-mm-dd",
                    dateFormat: "yy-mm-dd"
                });

        });
        // CRM.$(function($) {
        //   $("input[name='dateselect_to']").datepicker({
        //     format: "yy-mm-dd",
        //     altFormat: "yy-mm-dd",
        //     dateFormat: "yy-mm-dd"
        //   });
        //   $("input[name='dateselect_from']").datepicker({
        //     format: "yy-mm-dd",
        //     altFormat: "yy-mm-dd",
        //     dateFormat: "yy-mm-dd"
        //   });
        // });
    </script>
{/literal}
