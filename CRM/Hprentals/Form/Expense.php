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

    protected $_dialog;

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

        $this->_action = $action;
        U::writeLog($action, 'action after');
        $this->assign('action', $action);
        $myEntity = null;
        $entityName = $this->getDefaultEntityName();
        $entityClass = $this->getDefaultEntity();
        if ($id) {
            $myEntity = U::getMyEntity($id, $entityClass);
//            U::writeLog($myEntity, "RentalExpense Entity");

            if($myEntity){
                $this->_myentity = $myEntity;
                $this->_id = $id;
                $title = 'Edit ' . $entityName;
                $this->assign('myEntity', $myEntity);
            }
        }
        if($this->_action == CRM_Core_Action::DELETE){
            $title = 'Delete ' . $entityName;
            CRM_Utils_System::setTitle($title);
        }else{
            throw new CRM_Core_Exception(ts('You can only delete using this path'));
        }
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
        }else{
            throw new CRM_Core_Exception(ts('You can only delete using this path'));
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
        $entity = $this->getDefaultEntity();
        $action = $this->_action;
        $dialog = $this->_dialog;
        $id = $this->getEntityId();
        switch ($action) {
            case CRM_Core_Action::DELETE:
                $apiAction = 'delete';
                civicrm_api4($entity, 'delete', ['where' => [['id', '=', $id]]]);
                CRM_Core_Session::setStatus(E::ts('Removed Expense'), E::ts('Expense'), 'success');
                $url = (CRM_Utils_System::url(U::PATH_EXPENSES,
                    "reset=1"));
                U::writeLog($url);
                $session->replaceUserContext($url);
                if(!$dialog) {
                    CRM_Utils_System::redirect($url);
                }
                break;
        }
        parent::postProcess();
    }

}



