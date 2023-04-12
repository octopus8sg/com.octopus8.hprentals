<?php

use CRM_Hprentals_ExtensionUtil as E;
use CRM_Hprentals_Utils as U;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Hprentals_Form_Rental extends CRM_Core_Form
{
    protected $_cid;

    protected $_id;

    protected $_myentity;

    protected $_dialog;

    public function getDefaultEntity()
    {
        return 'RentalsRental';
    }

    public function getDefaultEntityName()
    {
        return 'Rental';
    }

    public function getDefaultEntityTable()
    {
        return 'civicrm_o8_rental_rental';
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
        $cid = CRM_Utils_Request::retrieveValue('cid', 'Positive', 0);
        if ($cid > 0) {
            $this->_cid = $cid;
        }
//        U::writeLog($cid, 'preProcess cid');
        $action = $this->getAction();
//        U::writeLog($action, 'action before');
        $id = CRM_Utils_Request::retrieve('id', 'Positive', $this, FALSE);


        if (!$action) {
            if (!$id) {
                $action = CRM_Core_Action::ADD;
            }
            if ($id) {
                $action = CRM_Core_Action::PREVIEW;
            }
        }
        if ($action == CRM_Core_Action::UPDATE) {
            if (!$id) {
                $action = CRM_Core_Action::ADD;
            }
        }
        $this->_action = $action;
//        U::writeLog($action, 'action after');
        $this->assign('action', $action);

//        U::writeLog($id, "RentalExpense id");

        $myEntity = null;
        $entityName = $this->getDefaultEntityName();
        $title = 'Add ' . $entityName;
        $entityClass = $this->getDefaultEntity();
        $session = CRM_Core_Session::singleton();
        if ($id) {
            $myEntity = U::getMyEntity($id, $entityClass);
//            U::writeLog($myEntity, "RentalExpense Entity");

            if ($myEntity) {
                $this->_myentity = $myEntity;
                $this->_id = $id;
                $title = 'Edit ' . $entityName;
                $this->assign('myEntity', $myEntity);
                $session->replaceUserContext(CRM_Utils_System::url(U::PATH_RENTAL,
                    ['id' => $this->getEntityId(),
                        'action' => 'update']));
            }
        }
        if ($this->_action == CRM_Core_Action::DELETE) {
            $title = 'Delete ' . $entityName;
        }
        if ($this->_action == CRM_Core_Action::PREVIEW) {
            $title = 'View ' . $entityName;
        }
        CRM_Utils_System::setTitle($title);

    }

    public function buildQuickForm()
    {
        $cid = $this->_cid;
        $fields = [];
//        U::writeLog($cid, 'buildQuickForm cid');

        $id = $this->getEntityId();
        $this->assign('id', $id);
        $action = $this->_action;
        if ($action == CRM_Core_Action::DELETE) {
            $this->add('hidden', 'id');
            $this->addButtons([
                ['type' => 'submit', 'name' => E::ts('Delete'), 'isDefault' => TRUE],
                ['type' => 'cancel', 'name' => E::ts('Cancel')]
            ]);
        }
        if ($action != CRM_Core_Action::DELETE) {

            $id_field = $this->add('text', 'id', E::ts('ID'), ['class' => 'huge'],)->freeze();

            $code = $this->add('text', 'code', E::ts('Rental No'), ['class' => 'huge']);
            $code->freeze();

            //

            //
            if (0 < intval($cid)) {
                $tenant_id = $this->add('hidden', 'tenant_id');
            } else {
                $tenant_id = $this->addEntityRef('tenant_id', "Tenant_" . $cid, [], TRUE);
            }
            if ($action == CRM_Core_Action::PREVIEW) {
                $tenant_id->freeze();
            }

            $attributes = ['formatType' => 'searchDate'];
            $extra = ['time' => FALSE];
            $admission = $this->add('datepicker', 'admission', ts('Admission'), $attributes, TRUE, $extra);
            if ($action == CRM_Core_Action::PREVIEW) {
                $admission->freeze();
            }

            $discharge = $this->add('datepicker', 'discharge', ts('Discharge'), $attributes, FALSE, $extra);
            if ($action == CRM_Core_Action::PREVIEW) {
                $discharge->freeze();
            }
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
            $submit = [
                'type' => 'submit',
                'name' => E::ts('Submit'),
                'isDefault' => TRUE,
            ];
            if ($action == CRM_Core_Action::PREVIEW) {
                $submit = [
                    'type' => 'submit',
                    'name' => E::ts('Close'),
                    'isDefault' => TRUE,
                ];
            }
            $this->addButtons([$submit]);
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
    public function getRenderableElementNames()
    {
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
        $cid = $this->_cid;
        if ($this->_myentity) {
            $defaults = $this->_myentity;
        }
        if ($cid) {
            $defaults['tenant_id'] = $cid;
        }

//        U::writeLog($cid, "cid Defaults");
//        U::writeLog($defaults, "Rentals Defaults");
        return $defaults;
    }


    function validate()
    {
        // Call the parent validate method
        $errors = parent::validate();
        $action = $this->_action;
        if ($action == CRM_Core_Action::DELETE || $action == CRM_Core_Action::PREVIEW) {
            return empty($this->_errors) ? true : false;
        }
        // Retrieve the values of the date_from and date_to fields
        $date_from = $this->_submitValues['admission'];
        $date_to = $this->_submitValues['discharge'];
        $rental_id = $this->_submitValues['id'];
        $tenant_id = $this->_submitValues['tenant_id'];

        // Retrieve the ID of the tenant from the URL parameters
//        $tenant_id = CRM_Utils_Request::retrieve('tenant_id', 'Positive', $this);
        // If open rent is found, set a validation error message
        U::writeLog($tenant_id, 'tenant in validate');
        U::writeLog($date_from, 'date_from in validate');
        U::writeLog($date_to, 'date_to in validate');
        U::writeLog($rental_id, 'rental_id in validate');
        $existing_rent = U::getUnfinishedRents($tenant_id, $rental_id);
        if ($existing_rent > 0) {
            $this->_errors['admission'] = ts('You have an unfinished rent.');
            return empty($this->_errors) ? true : false;
        }
        $existing_rent = U::getEarlierRents($tenant_id, $date_from, $rental_id);
        if ($existing_rent > 0) {
            $this->_errors['admission'] = ts('You already have a rent after this rent.');
            return empty($this->_errors) ? true : false;
        }
        // If an overlap is found, set a validation error message
        $existing_rent = U::getOverlappedRents($tenant_id, $date_from, $date_to, $rental_id);
        if ($existing_rent > 0) {
            $this->_errors['admission'] = ts('You already have a rent during this period.');
        }
        return empty($this->_errors) ? true : false;
    }

    /**
     * @throws API_Exception
     * @throws CRM_Core_Exception
     * @throws CiviCRM_API3_Exception
     * @throws \Civi\API\Exception\NotImplementedException
     */
    public function postProcess()
    {
        $entity = $this->getDefaultEntity();
        $entityName = $this->getDefaultEntityName();
        $action = $this->_action;
        $values = $this->controller->exportValues();
//        U::writeLog($values, $entityName . " values for " . $action);
        $params['tenant_id'] = $values['tenant_id'];
        $params['admission'] = $values['admission'];
        $params['discharge'] = $values['discharge'];

        $id = $this->getEntityId();
        switch ($action) {
            case CRM_Core_Action::ADD:
                $apiAction = "create";
                break;

            case CRM_Core_Action::UPDATE:
                $params['id'] = $id;
                $apiAction = "update";
                break;
            case CRM_Core_Action::PREVIEW:
                return;
                break;

            case CRM_Core_Action::DELETE:
                $apiAction = 'delete';
                civicrm_api4($entity, 'delete', ['where' => [['id', '=', $id]]]);
                CRM_Core_Session::setStatus('Removed ' . $entityName, $entityName, 'success');
                return;
                break;
        }
        if (($action == CRM_Core_Action::ADD) || ($action == CRM_Core_Action::UPDATE)) {
//            U::writeLog($params, $entityName . " params for " . $action);
            $result = civicrm_api4($entity, $apiAction, ['values' => $params]);
//            U::writeLog($result, $entityName . " is " . $action);
        }

        parent::postProcess();
    }

}

