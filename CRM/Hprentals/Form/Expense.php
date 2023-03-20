<?php

use CRM_Hprentals_ExtensionUtil as E;
use CRM_Hprentals_Utils as U;


/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Hprentals_Form_Expense extends CRM_Core_Form
{
    protected $_id;

    protected $_myentity;

    public function getDefaultEntity()
    {
        return 'RentalsExpense';
    }

    public function getDefaultEntityName()
    {
        return 'Rentals Expense';
    }

    public function getDefaultEntityTable()
    {
        return 'civicrm_o8_rental_expense';
    }

    public function getEntityId()
    {
        return $this->_id;
    }


    /**
     * Preprocess form.
     *
     * This is called before buildForm. Any pre-processing that
     * needs to be done for buildForm should be done here.
     *
     * This is a virtual function and should be redefined if needed.
     */
    public function preProcess()
    {
        parent::preProcess();

        $action = $this->getAction();
        U::writeLog($action, 'action before');
        $id = CRM_Utils_Request::retrieve('id', 'Positive', $this, FALSE);
        if(!$action){
            if(!$id){
                $action = CRM_Core_Action::ADD;
            }
            if($id){
                $action = CRM_Core_Action::UPDATE;
            }
        }
        if($action == CRM_Core_Action::UPDATE) {
            if (!$id) {
                $action = CRM_Core_Action::ADD;
            }
        }
        $this->_action = $action;
        U::writeLog($action, 'action after');
        $this->assign('action', $action);

        U::writeLog($id, "RentalExpense id");

        $myEntity = null;
        $entityName = $this->getDefaultEntityName();
        $title = 'Add ' . $entityName;
        $entityClass = $this->getDefaultEntity();
        $session = CRM_Core_Session::singleton();
        if ($id) {
            $myEntity = $this->getMyEntity($id, $entityClass);
//            U::writeLog($myEntity, "RentalExpense Entity");

            if($myEntity){
                $this->_myentity = $myEntity;
                $this->_id = $id;
                $title = 'Edit ' . $entityName;
                $this->assign('myEntity', $myEntity);
                $session->replaceUserContext(CRM_Utils_System::url(U::PATH_EXPENSE,
                    ['id' => $this->getEntityId(),
                        'action' => 'update']));
            }
        }
        if($this->_action = CRM_Core_Action::DELETE){
            $title = 'Delete ' . $entityName;
        }
        CRM_Utils_System::setTitle($title);

    }

    public function buildQuickForm()
    {
        $fields = [];

        $id = $this->getEntityId();
        $this->assign('id', $id);
        if($this->_action = CRM_Core_Action::DELETE){
            $this->add('hidden', 'id');
            $this->addButtons([
                ['type' => 'submit', 'name' => E::ts('Delete'), 'isDefault' => TRUE],
                ['type' => 'cancel', 'name' => E::ts('Cancel')]
            ]);
        }
        if($this->_action != CRM_Core_Action::DELETE) {


            $id_field = $this->add('text', 'id', E::ts('ID'), ['class' => 'huge'],)->freeze();
            $name = $this->add('text', 'name', E::ts('Name'), ['class' => 'huge']);
//
            $frequencies = U::getExpenseFrequency();
            $frequency = $this->add('select', 'frequency',
                E::ts('Frequency'),
                $frequencies,
                TRUE, ['class' => 'huge crm-select2']);
            $amount = $this->add('text', 'amount', ts('Amount'), ['size' => 8, 'maxlength' => 8], TRUE);
            $this->addRule('amount', ts('Amount should be a decimal number, like "100.25" or "-100.25"'), 'regex', '/^[+-]?((\d+(\.\d{0,2})?)|(\.\d{0,2}))$/');


            $is_refund = $this->addElement('checkbox', 'is_refund', 'Is Refund?');

            $is_prorate = $this->addElement('checkbox', 'is_prorate', 'Is Prorate?');
            $created_id = $this->addEntityRef('created_id', E::ts('Created By'),
                false);
            $created_id->freeze();
            $created_at = $this->add('datepicker', 'created_date', E::ts('Created At'));
            $created_at->freeze();
            $modified_id = $this->addEntityRef('modified_id', E::ts('Updated By'),
                false);
            $modified_id->freeze();
            $modified_at = $this->add('datepicker', 'modified_date', E::ts('Updated At'));
            $modified_at->freeze();
            $this->addButtons(array(
                array(
                    'type' => 'submit',
                    'name' => E::ts('Submit'),
                    'isDefault' => TRUE,
                ),
            ));
        }
        // export form elements
        $this->assign('elementNames', $this->getRenderableElementNames());
        parent::buildQuickForm();
    }
    /**
     * Get the fields/elements defined in this form.
     *
     * @return array (string)
     */
    public function getRenderableElementNames() {
        // The _elements list includes some items which should not be
        // auto-rendered in the loop -- such as "qfKey" and "buttons".  These
        // items don't have labels.  We'll identify renderable by filtering on
        // the 'label'.
        $elementNames = array();
        foreach ($this->_elements as $element) {
            /** @var HTML_QuickForm_Element $element */
            $label = $element->getLabel();
            if (!empty($label)) {
                $elementNames[] = $element->getName();
            }
        }
        return $elementNames;
    }

    /**
     * This virtual function is used to set the default values of various form
     * elements.
     *
     * @return array|NULL
     *   reference to the array of default values
     */
    public function setDefaultValues()
    {
        $defaults = [];
        if ($this->_myentity) {
            $defaults = $this->_myentity;
        }
        U::writeLog($defaults, "RentalsExpense Defaults");
        return $defaults;
    }

    /**
     * @throws API_Exception
     * @throws CRM_Core_Exception
     * @throws CiviCRM_API3_Exception
     * @throws \Civi\API\Exception\NotImplementedException
     */
    public function postProcess()
    {
        $session = CRM_Core_Session::singleton();
        $userId = CRM_Core_Session::singleton()->getLoggedInContactID();
        $now = date('YmdHis');
        $action = $this->_action;
        $values = $this->controller->exportValues();
        $params['name'] = $values['name'];
        $params['frequency'] = $values['frequency'];
        $params['is_refund'] = $values['is_refund'];
        $params['is_prorate'] = $values['is_prorate'];
        $params['amount'] = $values['amount'];
        $id = $this->getEntityId();
        switch ($action) {
            case CRM_Core_Action::ADD:
                $params['created_id'] = $userId;
                $params['created_date'] = $now;
                $apiAction = "create";
                break;

            case CRM_Core_Action::UPDATE:
                $params['modified_id'] = $userId;
                $params['modified_date'] = $now;
                $params['id'] = $id;
                $apiAction = "update";
                break;

            case CRM_Core_Action::PREVIEW:
                return;
                break;

            case CRM_Core_Action::DELETE:
                $apiAction = 'delete';
                civicrm_api4('RentalsExpense', 'delete', ['where' => [['id', '=', $id]]]);
                CRM_Core_Session::setStatus(E::ts('Removed Expense'), E::ts('Expense'), 'success');
                $url = (CRM_Utils_System::url(U::PATH_EXPENSES,
                    "reset=1"));
                U::writeLog($url);
                $session->replaceUserContext($url);
                CRM_Utils_System::redirect($url);
                break;
        }
//        U::writeLog($params, 'after switch 1');
//        U::writeLog($apiAction, 'apiAction switch 1');
        if(($action == CRM_Core_Action::ADD) || ($action == CRM_Core_Action::UPDATE)){

            $result = civicrm_api4('RentalsExpense', $apiAction, ['values' => $params]);
            if(sizeof($result) == 1){
                $myentity=$result[0];
                $id = $myentity['id'];

            }
            $url = (CRM_Utils_System::url(U::PATH_EXPENSE,
                "reset=1&id={$id}"));
            U::writeLog($url);
            $session->replaceUserContext($url);
            CRM_Utils_System::redirect($url);
        }

        parent::postProcess();
    }

    /**
     * @param $id
     * @return mixed|null
     */
    public function getMyEntity($id, $entityName)
    {
        $myentity = null;
        $entities = civicrm_api4($entityName, 'get', ['where' => [['id', '=', $id]], 'limit' => 1]);
        if (!empty($entities)) {
            $myentity = $entities[0];
        }

        return $myentity;
    }

}



