
{*{debug}*}

{if $action eq 8}
  {* Are you sure to delete form *}
  <div class="crm-block crm-form-block">
    <div class="crm-section">{ts 1=$myEntity.code}Are you sure you wish to delete the Invoice # %1?{/ts}</div>
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


  <div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="bottom"}
  </div>
{/if}