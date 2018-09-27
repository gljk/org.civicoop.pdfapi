<h3>{$ruleActionHeader}</h3>
<div class="crm-block crm-form-block crm-civirule-rule_action-block-pdf-create">
  <div class="crm-section to_contact">
    <div class="label">{$form.to_contact.label}</div>
    <div class="content">{$form.to_contact.html}</div>
    <div class="clear"></div>
  </div>
  <div class="crm-section hiddenElement to_email">
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

{literal}
  <script type="text/javascript">
    cj(function() {
      cj('#to_contact').change(triggerToContactChange);
      triggerToContactChange();
      triggerFallBackPrimary();
    });
  function triggerToContactChange() {
    cj('.crm-section.to_email').addClass('hiddenElement');
    var val = cj('#to_contact').prop('checked');
    if (!val) {
      cj('.crm-section.to_email').removeClass('hiddenElement');
    }
  }
  function triggerFallBackPrimary() {
    cj('.crm-section.to_email').removeClass('hiddenElement');
    triggerToContactChange();
  }
  </script>
{/literal}

