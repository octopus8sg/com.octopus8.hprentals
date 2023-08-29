<?php

use CRM_Hprentals_ExtensionUtil as E;
use CRM_Hprentals_Utils as U;
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Hprentals_Form_Invoice extends CRM_Core_Form
{

    protected $_cid;

    protected $_id;

    protected $_myentity;

    protected $_dialog;

    protected $_myrental;

    public function getDefaultEntity()
    {
        return 'RentalsInvoice';
    }

    public function getDefaultEntityName()
    {
        return 'Rentals Invoice';
    }

    public function getDefaultEntityTable()
    {
        return 'civicrm_o8_rental_invoice';
    }

    public function getEntityId()
    {
        return $this->_id;
    }

    public function getInvoiceCode()
    {
        return $this->_myentity['code'];
    }

    public function getTenantName()
    {
        $rental_id = $this->_myentity['rental_id'];
        $rental = \Civi\Api4\RentalsRental::get(FALSE)
            ->addSelect('tenant_id')
            ->addWhere('id', '=', $rental_id)
            ->setLimit(1)
            ->execute()->single();
        if(!$rental){
            return "";
        }
        $tenant_id = $rental['tenant_id'];
        $this->_myentity['tenant_id'] = $tenant_id;
        $this->_myrental = $rental;
        return CRM_Contact_BAO_Contact::displayName($this->_myentity['tenant_id']);
    }

    public function getLastAdmission()
    {
        $tenant_id = $this->_myentity['tenant_id'];
        if(!$tenant_id){
            return "";
        }
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


    public function getCurrentDate()
    {
        $date = new DateTime();
        // Format the date as "1/4/2023 12:10 PM"
        return $date->format('j/n/Y g:i A');
    }

    public function getInvoiceDate()
    {
        $date = new DateTime($this->_myentity['created_date']);
        // Format the date as "1/4/2023 12:10 PM"
        return $date->format('j/n/Y');
    }

    public function getStartDate()
    {
        $date = new DateTime($this->_myentity['start_date']);
        // Format the date as "1/4/2023 12:10 PM"
        return $date->format('j/n/Y');
    }

    public function getEndDate()
    {
        $date = new DateTime($this->_myentity['end_date']);
        // Format the date as "1/4/2023 12:10 PM"
        return $date->format('j/n/Y');
    }

    public function getInvoiceYear()
    {

        $year_name = date("Y", strtotime($this->_myentity['start_date']));
        return $year_name;
    }

    public function getInvoiceMonth()
    {
        $month_name = date("F", strtotime($this->_myentity['start_date']));
        return $month_name;
    }

    public function getDescription()
    {
        $description = nl2br(htmlspecialchars($this->_myentity['description']));
        return $description;
    }

    public function getInvoiceAmount()
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
        return E::path('PDF' . DIRECTORY_SEPARATOR . 'invoices' . DIRECTORY_SEPARATOR . $pdfFileName);
    }

    public function getTemplateFilePath()
    {
        $templateFileName = 'invoice.html';
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
            "{{ invoice_code }}",
            "{{ invoice_date }}",
            "{{ invoice_year }}",
            "{{ invoice_month }}",
            "{{ start_date }}",
            "{{ end_date }}",
            "{{ description }}",
            "{{ invoice_amount }}",
        ], [
            $logo_path,
            $this->getCurrentDate(),
            $this->getTenantName(),
            $this->getLastAdmission(),
            $this->getInvoiceCode(),
            $this->getInvoiceDate(),
            $this->getInvoiceYear(),
            $this->getInvoiceMonth(),
            $this->getStartDate(),
            $this->getEndDate(),
            $this->getDescription(),
            $this->getInvoiceAmount(),
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
        return E::url('PDF' . DIRECTORY_SEPARATOR . 'invoices' . DIRECTORY_SEPARATOR . $pdfFileName);
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
        $cid = CRM_Utils_Request::retrieve('cid', 'Positive');
        if ($cid) {
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
                $session->replaceUserContext(CRM_Utils_System::url(U::PATH_INVOICES,
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

            $code = $this->add('text', 'code', E::ts('Invoice No'), ['class' => 'huge']);
            $code->freeze();

            //
            $rental_id = $this->addEntityRef('rental_id', E::ts('Rental'), [
                'api' => [
                    'search_fields' => ['code'],
                    'label_field' => "code",
                    'description_field' => [
                        'id',
                        'tenant_id.sort_name',
                        'admission',
                    ]
                ],
                'entity' => 'RentalsRental',
                'select' => ['minimumInputLength' => 0],
                'class' => 'huge',
                'placeholder' => ts('- Select Rental -'),
            ], TRUE);
            if ($action == CRM_Core_Action::PREVIEW) {
                $rental_id->freeze();
            }
            if ($cid) {
                $rental_id->freeze();
            }
            $noteAttrib = CRM_Core_DAO::getAttribute('CRM_Core_DAO_Note');
            $description = $this->add('textarea', 'description', ts('Description'), $noteAttrib['note'], TRUE);
            //4
            if ($action == CRM_Core_Action::PREVIEW) {
                $description->freeze();
            }
//            if ($cid) {
//                $description->freeze();
//            }
            $amount = $this->add('text', 'amount', ts('Amount'), ['size' => 8, 'maxlength' => 8], TRUE);
            if ($action == CRM_Core_Action::PREVIEW) {
                $amount->freeze();
            }
//            if ($cid) {
//                $amount->freeze();
//            }
//            $this->addRule('amount', ts('Amount should be a positive decimal number, like "100.25"'), 'regex', '/^[+]?((\d+(\.\d{0,2})?)|(\.\d{0,2}))$/');
            if ($action == CRM_Core_Action::PREVIEW) {
                $year = $this->add('text', 'year', ts('Invoice Year'), ['size' => 8, 'maxlength' => 8], FALSE)->freeze();
                $month = $this->add('text', 'month', ts('Invoice Month'), ['size' => 8, 'maxlength' => 8], FALSE)->freeze();

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
                $hasPdfFile = $this->hasPdfFile();
                if (!$hasPdfFile) {
                    $this->putPdfFile();
                }
                $fileUrl = $this->getPdfFileUrl();
                $fileName = $this->getPdfFileName();
                //
                $this->addElement('link', 'pdfurl', 'Pdf', $fileUrl, $fileName);
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
            $month_name = date("F", strtotime($this->_myentity['start_date']));
            $year_name = date("Y", strtotime($this->_myentity['start_date']));
            $defaults['month'] = $month_name;
            $defaults['year'] = $year_name;
        }
        if ($cid) {
            $defaults['tenant_id'] = $cid;
        }


//        U::writeLog($cid, "cid Defaults");
//        U::writeLog($defaults, "Rentals Defaults");
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
        $params['rental_id'] = $values['rental_id'];
        $params['description'] = $values['description'];
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

    public static function generateInvoices()
    {
        //Select all current rentals that does not start this month
        //and they have invoices in this month
        //if they is some in the list
        //create invoice for the Types that are not onetyme
        //if total length of rental is > 6 month - one price, if < 6m, another
    }

}
