{crmScope extensionKey='com.octopus8.hprentals'}
    <h3 class="crm-content-block">
    {*                    {debug}*}
    <div class="clear"></div>
    {include file="CRM/Hprentals/Form/DashboardFilter.tpl"}
    <div class="clear"></div>
    <div class="crm-results-block">
        <h3>Rental Summary - {$last_month}</h3>
        <div class="crm-search-results">
            <table>
                <table class="selector-rentals row-highlight pagerDisplay" id="DashboardRentals"
                       name="DashboardContact">
                    <thead class="sticky">
                    <tr>
                        <th scope="col">
                            {ts}ID{/ts}
                        </th>
                        <th scope="col">
                            {ts}Tenant{/ts}
                        </th>
                        <th scope="col">
                            {ts}Admission{/ts}
                        </th>
                        <th scope="col">
                            {ts}Discharge{/ts}
                        </th>
                    </tr>
                    </thead>
                </table>
        </div>
    </div>
    <div class="clear"></div>
    <div class="crm-results-block">
        <h3>Invoice Summary - {$last_month}</h3>
        <div class="crm-search-results">
            {include file="CRM/common/enableDisableApi.tpl"}
            {include file="CRM/common/jsortable.tpl"}
            <table class="selector-invoices row-highlight pagerDisplay" id="DashboardInvoices" name="DashboardInvoices">
                <thead class="sticky">
                <tr>
                    <th id="sortable" scope="col">
                        {ts}ID{/ts}
                    </th>
                    <th scope="col">
                        {ts}Code{/ts}
                    </th>
                    <th scope="col">
                        {ts}Tenant{/ts}
                    </th>
                    <th scope="col">
                        {ts}Start date{/ts}
                    </th>
                    <th scope="col">
                        {ts}End date{/ts}
                    </th>
                    <th scope="col">
                        {ts}Issue date{/ts}
                    </th>
                    <th scope="col">
                        {ts}Amount{/ts}
                    </th>
                </tr>
                </thead>
                <tfoot id="i_total">
                <tr>
                    <td colspan="7" id="i_total_td" style="text-align: right;"><b>Total: <span id="i_total_sum"></span></b></td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="clear"></div>
    <div class="clear"></div>
    <div class="crm-results-block">
        <h3>Payment Summary - {$last_month}</h3>
        <div class="crm-search-results">
            {include file="CRM/common/enableDisableApi.tpl"}
            {include file="CRM/common/jsortable.tpl"}
            <table class="selector-payments row-highlight pagerDisplay" id="DashboardPayments" name="DashboardPayments">
                <thead class="sticky">
                <tr>
                    <th id="sortable" scope="col">
                        {ts}ID{/ts}
                    </th>
                    <th scope="col">
                        {ts}Code{/ts}
                    </th>
                    <th scope="col">
                        {ts}Tenant{/ts}
                    </th>
                    <th scope="col">
                        {ts}Date{/ts}
                    </th>
                    <th scope="col">
                        {ts}Method{/ts}
                    </th>
                    <th scope="col">
                        {ts}Amount{/ts}
                    </th>
                </tr>
                </thead>
                <tfoot id="p_total">
                <tr>
                    <td colspan="6" id="p_total_td" style="text-align: right;"><b>Total: <span id="p_total_sum"></span></b></td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="clear"></div>
    <div class="clear"></div>
    <div class="crm-results-block">
        <h3>Balance Summary</h3>
        <div class="crm-search-results">
            {include file="CRM/common/enableDisableApi.tpl"}
            {include file="CRM/common/jsortable.tpl"}
            <table class="selector-balance row-highlight pagerDisplay" id="DashboardBalance" name="DashboardBalance">
                <thead class="sticky">
                <tr>
                    <th id="sortable" scope="col">
                        {ts}Tenant ID{/ts}
                    </th>
                    <th scope="col">
                        {ts}Tenant{/ts}
                    </th>
                    <th scope="col">
                        {ts}Invoice Amount{/ts}
                    </th>
                    <th scope="col">
                        {ts}Payment Amount{/ts}
                    </th>
                    <th scope="col">
                        {ts}Balance{/ts}
                    </th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
{crmScript ext=com.octopus8.hprentals file=js/dashboardrentals.js}
{crmScript ext=com.octopus8.hprentals file=js/dashboardpayments.js}
{crmScript ext=com.octopus8.hprentals file=js/dashboardinvoices.js}
{crmScript ext=com.octopus8.hprentals file=js/dashboardbalance.js}
{/crmScope}