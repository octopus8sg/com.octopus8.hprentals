{* HEADER *}
{*{debug}*}
{* FIELD EXAMPLE: OPTION 1 (AUTOMATIC LAYOUT) *}
{if $action eq 8}
    {* Are you sure to delete form *}
    <div class="crm-block crm-form-block">
        <div class="crm-section">{ts 1=$myEntity.name}Are you sure you wish to delete the Rental Expense with name: %1?{/ts}</div>
    </div>
    <div class="crm-submit-buttons">
        {include file="CRM/common/formButtons.tpl" location="bottom"}
    </div>
{else}
    {foreach from=$elementNames item=elementName}
        <div class="crm-section">
            <div class="label">{$form.$elementName.label}</div>
            <div class="content">{$form.$elementName.html}</div>
            <div class="clear"></div>
        </div>
    {/foreach}

    {* FIELD EXAMPLE: OPTION 2 (MANUAL LAYOUT)

      <div>
        <span>{$form.favorite_color.label}</span>
        <span>{$form.favorite_color.html}</span>
      </div>

    {* FOOTER *}
    <div class="crm-submit-buttons">
        {include file="CRM/common/formButtons.tpl" location="bottom"}
    </div>
{/if}