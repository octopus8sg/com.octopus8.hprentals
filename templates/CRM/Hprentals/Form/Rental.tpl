{* HEADER *}
{*{debug}*}
{* FIELD EXAMPLE: OPTION 1 (AUTOMATIC LAYOUT) *}
{if $action eq 8}
  {* Are you sure to delete form *}
  <div class="crm-block crm-form-block">
    <div class="crm-section">{ts 1=$myEntity.code}Are you sure you wish to delete the Rental Payment with code: %1?{/ts}</div>
  </div>
  <div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="bottom"}
  </div>
{/if}