<?php

use CRM_Hprentals_ExtensionUtil as E;
use CRM_Hprentals_Utils as U;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Hprentals_Form_Method extends CRM_Core_Form
{
    protected $_id;

    protected $_myentity;

    public function getDefaultEntity()
    {
        return 'RentalsMethod';
    }

    public function getDefaultEntityName()
    {
        return 'Rentals Payment Method';
    }

    public function getDefaultEntityTable()
    {
        return 'civicrm_o8_rental_method';
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

        U::writeLog($id, "Rental Payment Method id");

        $myEntity = null;
        $entityName = $this->getDefaultEntityName();
        $title = 'Add ' . $entityName;
        $entityClass = $this->getDefaultEntity();
        $session = CRM_Core_Session::singleton();
        if ($id) {
            $myEntity = U::getMyEntity($id, $entityClass);
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
        if($this->_action == CRM_Core_Action::DELETE){
            $title = 'Delete ' . $entityName;
        }
        CRM_Utils_System::setTitle($title);

    }

    public function buildQuickForm()
    {
        $fields = [];

        $id = $this->getEntityId();
        $this->assign('id', $id);
        if($this->_action == CRM_Core_Action::DELETE){
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
        U::writeLog($defaults, "Rentals Payment Method Defaults");
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
        $entity = $this->getDefaultEntity();
        $now = date('YmdHis');
        $action = $this->_action;
        $values = $this->controller->exportValues();
        $params['name'] = $values['name'];
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
                civicrm_api4($entity, 'delete', ['where' => [['id', '=', $id]]]);
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

            $result = civicrm_api4($entity, $apiAction, ['values' => $params]);
            if(sizeof($result) == 1){
                $myentity=$result[0];
                $id = $myentity['id'];

            }
            $url = (CRM_Utils_System::url(U::PATH_METHOD,
                "reset=1&id={$id}"));
            U::writeLog($url);
            $session->replaceUserContext($url);
            CRM_Utils_System::redirect($url);
        }

        parent::postProcess();
    }

}
