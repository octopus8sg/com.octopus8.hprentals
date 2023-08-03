<?php

use CRM_Hprentals_ExtensionUtil as E;
use CRM_Hprentals_Utils as U;
use Dompdf\Dompdf;
use Dompdf\Options;

//use GuzzleHttp\Client;
//use GuzzleHttp\Exception\GuzzleException;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Hprentals_Form_Payment extends CRM_Core_Form
{
    use CRM_Contact_Form_Task_PDFTrait;
    protected $_id;

    protected $_cid;

    protected $_myentity;

    protected $_dialog;

    public function getDefaultEntity()
    {
        return 'RentalsPayment';
    }

    public function getDefaultEntityName()
    {
        return 'Rentals Payment/Receipt';
    }

    public function getDefaultEntityTable()
    {
        return 'civicrm_o8_rental_payment';
    }

    public function getEntityId()
    {
        return $this->_id;
    }

    public function getPaymentCode()
    {
        return $this->_myentity['code'];
    }

    public function getTenantName()
    {
        return CRM_Contact_BAO_Contact::displayName($this->_myentity['tenant_id']);
    }

    public function getLastAdmission()
    {
        $tenant_id = $this->_myentity['tenant_id'];
        $rental = \Civi\Api4\RentalsRental::get(FALSE)
            ->addSelect('admission')
            ->addWhere('tenant_id', '=', $tenant_id)
            ->addOrderBy('admission', 'DESC')
            ->setLimit(1)
            ->execute()->single();
        if(!$rental){
            return "";
        }
        $date = new DateTime($rental['admission']);
        return $date->format('j/n/Y');
    }

    public function getPaymentMethod()
    {
        $method_id = $this->_myentity['method_id'];
        $method = \Civi\Api4\RentalsMethod::get(FALSE)
            ->addSelect('name')
            ->addWhere('id', '=', $method_id)
            ->setLimit(1)
            ->execute()->single();
        if(!$method){
            return "";
        }
        $name = $method['name'];
        return $name;
    }

    public function getCurrentDate()
    {
        $date = new DateTime();
        // Format the date as "1/4/2023 12:10 PM"
        return $date->format('j/n/Y g:i A');
    }

    public function getPaymentDate()
    {
        $date = new DateTime($this->_myentity['created_date']);
        // Format the date as "1/4/2023 12:10 PM"
        return $date->format('j/n/Y');
    }

    public function getPaymentAmount()
    {
        return CRM_Utils_Money::format($this->_myentity['amount']);
    }

    public function getPdfFileName()
    {
        return $this->_myentity['code'] . '_' . $this->_myentity['id'] . '.pdf';
    }

    public function getPdfFilePath()
    {
        $pdfFileName = $this->getPdfFileName();
        return E::path('PDF' . DIRECTORY_SEPARATOR . 'payments' . DIRECTORY_SEPARATOR . $pdfFileName);
    }

    public function getTemplateFilePath()
    {
        $templateFileName = 'payment.html';
        return E::path('PDF' . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . $templateFileName);
    }

    public function hasPdfFile()
    {
        $pdfFilePath = $this->getPdfFilePath();
        return file_exists($pdfFilePath);
    }

    public function putPdfFile()
    {
        $pdf_filename = $this->getPdfFilePath();
        $logo_path = E::path('PDF' . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . 'logo.jpg');
        $templateFilePath = $this->getTemplateFilePath();

        $options = new Options();
        $options->setChroot(E::path('PDF' . DIRECTORY_SEPARATOR . 'template'));
        $options->setIsRemoteEnabled(true);
        $options->setPdfBackend('CPDF');
        $domPdf = new Dompdf($options);
        $domPdf->setPaper("A4", "portrait");



        $html = file_get_contents($templateFilePath);
        $html = str_replace([
            "{{ logo_path }}",
            "{{ print_date }}",
            "{{ tenant_name }}",
            "{{ last_rental_admission }}",
            "{{ payment_code }}",
            "{{ payment_date }}",
            "{{ payment_method }}",
            "{{ payment_amount }}",
        ], [
            $logo_path,
            $this->getCurrentDate(),
            $this->getTenantName(),
            $this->getLastAdmission(),
            $this->getPaymentCode(),
            $this->getPaymentDate(),
            $this->getPaymentMethod(),
            $this->getPaymentAmount(),
        ], $html);
        $domPdf->loadHtml($html);
        $domPdf->render();
        $pdf = $domPdf->output();
        file_put_contents($pdf_filename, $pdf);
        return $pdf_filename;
    }

    public function getPdfFileUrl()
    {
        $pdfFileName = $this->getPdfFileName();
        return E::url('PDF' . DIRECTORY_SEPARATOR . 'payments' . DIRECTORY_SEPARATOR . $pdfFileName);
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
                $session->replaceUserContext(CRM_Utils_System::url(U::PATH_PAYMENTS,
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

            $code = $this->add('text', 'code', E::ts('Receipt No'), ['class' => 'huge']);
            $code->freeze();

            //
            if (0 < intval($cid)) {
                $tenant_id = $this->add('hidden', 'tenant_id');
            } else {
                $tenant_id = $this->addEntityRef('tenant_id', "Tenant_" . $cid, [], TRUE);
            }
            if ($action == CRM_Core_Action::PREVIEW) {
                $tenant_id->freeze();
            }
            $method_id = $this->addEntityRef('method_id', E::ts('Method'), [
                'api' => [
                    'search_fields' => ['name'],
                    'label_field' => "name",
                    'description_field' => [
                        'id',
                        'method_id.name',
                    ]
                ],
                'entity' => 'RentalsMethod',
                'select' => ['minimumInputLength' => 0],
                'class' => 'huge',
                'placeholder' => ts('- Select Payment Method -'),
            ], TRUE);
            if ($action == CRM_Core_Action::PREVIEW) {
                $method_id->freeze();
            }
//            if ($cid) {
//                $rental_id->freeze();
//            }

            $amount = $this->add('text', 'amount', ts('Amount'), ['size' => 8, 'maxlength' => 8], TRUE);
            if ($action == CRM_Core_Action::PREVIEW) {
                $amount->freeze();
            }
//            if ($cid) {
//                $amount->freeze();
//            }
            $this->addRule('amount', ts('Amount should be a positive decimal number, like "100.25"'), 'regex', '/^[+]?((\d+(\.\d{0,2})?)|(\.\d{0,2}))$/');

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
            $submits = [[
                'type' => 'submit',
                'name' => E::ts('Submit'),
                'isDefault' => TRUE,
            ]];
            if ($action == CRM_Core_Action::PREVIEW) {

                $hasPdfFile = $this->hasPdfFile();
                if (!$hasPdfFile) {
                    $this->putPdfFile();
                }
                $fileUrl = $this->getPdfFileUrl();
                $fileName = $this->getPdfFileName();
                //
                $this->addElement('link', 'pdfurl', 'Pdf', $fileUrl, $fileName);
                $submits = [
                    ['type' => 'submit', 'name' => E::ts('Close'), 'isDefault' => TRUE]
                ];
            }
            $this->addButtons($submits);
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
//        U::writeLog($defaults, "Payment Defaults");
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
        $entity = $this->getDefaultEntity();
        $entityName = $this->getDefaultEntityName();
        $action = $this->_action;
        $values = $this->controller->exportValues();
//        U::writeLog($values, $entityName . " values for " . $action);
        $params['tenant_id'] = $values['tenant_id'];
        $params['method_id'] = $values['method_id'];
        $params['amount'] = $values['amount'];

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
                CRM_Utils_System::civiExit();
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
//        CRM_Utils_System::civiExit();
    }


}
