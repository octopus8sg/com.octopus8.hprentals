{crmScope extensionKey='com.octopus8.hprentals'}
    <div class="crm-content-block">
        <div class="action-link">
            {*                    {debug}*}
            <a class="button add-method" href="{crmURL p="civicrm/rentals/method" q="reset=1&action=add" }">
                <i class="crm-i fa-plus-circle">&nbsp;</i>
                {ts}Add Method{/ts}
            </a>
        </div>
        <div class="clear"></div>
        {include file="CRM/Hprentals/Form/MethodFilter.tpl"}
        <div class="clear"></div>
        <div class="crm-results-block">
            <div class="crm-search-results">
                {include file="CRM/common/enableDisableApi.tpl"}
                {include file="CRM/common/jsortable.tpl"}
                <table class="selector-methods row-highlight pagerDisplay" id="Methods" name="Methods">
                    <thead class="sticky">
                    <tr>
                        <th id="sortable" scope="col">
                            {ts}ID{/ts}
                        </th>
                        <th scope="col">
                            {ts}Name{/ts}
                        </th>
                        <th id="nosort">&nbsp;Action</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
{crmScript ext=com.octopus8.hprentals file=js/methods.js}
{/crmScope}