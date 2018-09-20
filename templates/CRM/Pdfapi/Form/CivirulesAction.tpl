<h3>{$ruleActionHeader}</h3>
<div class="crm-block crm-form-block crm-civirule-rule_action-block-pdf-create">
  <div class="crm-section">
    <div class="label">{$form.to_email.label}</div>
    <div class="content">{$form.to_email.html}</div>
    <div class="clear"></div>
  </div>
  <div class="crm-section">
    <div class="label">{$form.template_id.label}</div>
    <div class="content">{$form.template_id.html}</div>
    <div class="clear"></div>
  </div>
  <div class="crm-section">
    <div class="label">{$form.body_template_id.label}</div>
    <div class="content">{$form.body_template_id.html}</div>
    <div class="clear"></div>
  </div>
  <div class="crm-section">
    <div class="label">{$form.email_subject.label}</div>
    <div class="content">{$form.email_subject.html}</div>
    <div class="clear"></div>
  </div>
</div>
<div class="crm-submit-buttons">
  {include file="CRM/common/formButtons.tpl" location="bottom"}
</div>