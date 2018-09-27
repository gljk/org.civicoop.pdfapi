<?php

require_once 'CRM/Core/Form.php';

/**
 * Form controller class
 *
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 */
class CRM_Pdfapi_Form_CivirulesAction extends CRM_Core_Form {

  protected $ruleActionId = false;

  protected $ruleAction;

  protected $action;

  /**
   * Overridden parent method to do pre-form building processing
   *
   * @throws Exception when action or rule action not found
   * @access public
   */
  public function preProcess() {
    $this->ruleActionId = CRM_Utils_Request::retrieve('rule_action_id', 'Integer');

    $this->ruleAction = new CRM_Civirules_BAO_RuleAction();
    $this->action = new CRM_Civirules_BAO_Action();
    $this->ruleAction->id = $this->ruleActionId;
    if ($this->ruleAction->find(true)) {
      $this->action->id = $this->ruleAction->action_id;
      if (!$this->action->find(true)) {
        throw new Exception('CiviRules Could not find action with id '.$this->ruleAction->action_id);
      }
    } else {
      throw new Exception('CiviRules Could not find rule action with id '.$this->ruleActionId);
    }

    parent::preProcess();
  }

  /**
   * Method to get groups
   *
   * @return array
   * @access protected
   */

  protected function getMessageTemplates() {
    $return = array('' => ts('-- please select --'));
    $dao = CRM_Core_DAO::executeQuery("SELECT * FROM `civicrm_msg_template` WHERE `is_active` = 1 AND `workflow_id` IS NULL ORDER BY msg_title");
    while($dao->fetch()) {
      $return[$dao->id] = $dao->msg_title;
    }
    return $return;
  }

  function buildQuickForm() {
    $this->setFormTitle();
    $this->add('hidden', 'rule_action_id');
    $this->add('text', 'to_email', ts('To e-mail address'), array(),FALSE);
    $this->addRule('to_email', ts('Email is not valid.'), 'email');
    $this->add('checkbox','to_contact', ts('Send to Contact'));
    $this->add('select', 'template_id', ts('Message template for the PDF'), $this->getMessageTemplates(), TRUE);
    $this->add('select', 'body_template_id', ts('Message template for the e-mail that sends the PDF'), $this->getMessageTemplates(), FALSE);
    $this->add('text', 'email_subject', ts('Subject for the e-mail that will send the PDF'), array(), FALSE);
    $this->addButtons(array(
      array('type' => 'next', 'name' => ts('Save'), 'isDefault' => TRUE,),
      array('type' => 'cancel', 'name' => ts('Cancel'))));
  }

  /**
   * Overridden parent method to set default values
   *
   * @return array $defaultValues
   * @access public
   */
  public function setDefaultValues() {
    $data = array();
    $defaultValues = array();
    $defaultValues['rule_action_id'] = $this->ruleActionId;
    if (!empty($this->ruleAction->action_params)) {
      $data = unserialize($this->ruleAction->action_params);
    }
    if (!empty($data['to_email'])) {
      $defaultValues['to_email'] = $data['to_email'];
    }
    if (!empty($data['to_contact'])) {
      $defaultValues['to_contact'] = $data['to_contact'];
    }
    if (!empty($data['template_id'])) {
      $defaultValues['template_id'] = $data['template_id'];
    }
    if (!empty($data['body_template_id'])) {
      $defaultValues['body_template_id'] = $data['body_template_id'];
    }
    if (!empty($data['email_subject'])) {
      $defaultValues['email_subject'] = $data['email_subject'];
    }
    return $defaultValues;
  }

  /**
   * Overridden parent method to process form data after submitting
   *
   * @access public
   */
  public function postProcess() {
    $data['to_email'] = $this->_submitValues['to_email'];
    $data['to_contact'] = $this->_submitValues['to_contact'];
    $data['template_id'] = $this->_submitValues['template_id'];
    $data['body_template_id'] = $this->_submitValues['body_template_id'];
    $data['email_subject'] = $this->_submitValues['email_subject'];

    $ruleAction = new CRM_Civirules_BAO_RuleAction();
    $ruleAction->id = $this->ruleActionId;
    $ruleAction->action_params = serialize($data);
    $ruleAction->save();

    $session = CRM_Core_Session::singleton();
    $session->setStatus('Action '.$this->action->label.' parameters updated to CiviRule '.CRM_Civirules_BAO_Rule::getRuleLabelWithId($this->ruleAction->rule_id),
      'Action parameters updated', 'success');

    $redirectUrl = CRM_Utils_System::url('civicrm/civirule/form/rule', 'action=update&id='.$this->ruleAction->rule_id, TRUE);
    CRM_Utils_System::redirect($redirectUrl);
  }

  /**
   * Method to set the form title
   *
   * @access protected
   */
  protected function setFormTitle() {
    $title = 'CiviRules Edit Action parameters';
    $this->assign('ruleActionHeader', 'Edit action '.$this->action->label.' of CiviRule '.CRM_Civirules_BAO_Rule::getRuleLabelWithId($this->ruleAction->rule_id));
    CRM_Utils_System::setTitle($title);
  }
}
