<?php

use CRM_Hprentals_ExtensionUtil as E;
use CRM_Hprentals_Utils as U;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Hprentals_Form_MakeInvoices extends CRM_Core_Form
{
    public function buildQuickForm()
    {
        $rentalsRentals = civicrm_api4('RentalsRental', 'get', [
            'select' => [
                'admission',
            ],
            'orderBy' => [
                'admission' => 'ASC',
            ],
            'limit' => 1,
        ]);
        $current_year = date('Y');
        $first_year = (sizeof($rentalsRentals) == 0) ? $current_year : date('Y', strtotime($rentalsRentals[0]['admission']));

// Get the current year


// Create the range of years
        $years = range($first_year, $current_year);

// Reverse the order of the years so that the most recent year comes first
        $years = array_reverse($years);
        $yearsAssoc = array_combine($years, $years);
        $this->add('select', 'year', ts('Select Year'), $yearsAssoc, TRUE, ['placeholder' => ts('- select year -')]);
        $months = array(
            1 => "January",
            2 => "February",
            3 => "March",
            4 => "April",
            5 => "May",
            6 => "June",
            7 => "July",
            8 => "August",
            9 => "September",
            10 => "October",
            11 => "November",
            12 => "December"
        );

        $this->add('select', 'month', ts('Select Month'), $months, TRUE, ['placeholder' => ts('- select month -')]);
        $this->assign('elementNames', $this->getRenderableElementNames());
        $submit = [
            'type' => 'submit',
            'name' => E::ts('Submit'),
            'isDefault' => TRUE,
        ];
        $this->addButtons([$submit]);
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

    public function postProcess()
    {
        $values = $this->exportValues();
        $year = $values['year'];
        $month = $values['month'];
        $first_day = "$year-$month-01";
        $sql = "
SELECT distinct r.id,
       r.admission,
       r.discharge
FROM civicrm_o8_rental_rental r
         INNER JOIN civicrm_o8_rental_invoice i on (r.id = i.rental_id)
WHERE (r.admission < '$first_day')
  AND (r.discharge IS NULL
           or
       ((YEAR(r.discharge) = YEAR(CURRENT_DATE())
           AND MONTH(r.discharge) = MONTH(CURRENT_DATE()))
           AND r.discharge >= '$first_day')
      )
  AND r.id not in (select rental_id
                   from civicrm_o8_rental_invoice rin
                   where (YEAR(rin.start_date) = $year
                       AND MONTH(rin.start_date) = $month))
    ";
        $dao = CRM_Core_DAO::executeQuery($sql);
        $iFilteredTotal = CRM_Core_DAO::singleValueQuery("SELECT FOUND_ROWS()");
        $count = 0;
        while ($dao->fetch()) {
            $last_date = date("Y-m-t", strtotime($first_day));
            $rental_start_date = $dao->admission;
            if ($dao->discharge) {
                $last_date = $dao->discharge;
            }
            $calculate_expenses = U::calculate_expenses($first_day, $last_date, $rental_start_date);
            $invoice = [];
            $invoice['code'] = "";
            $invoice['description'] = $calculate_expenses['description'];
            $invoice['amount'] = $calculate_expenses['total'];
            $invoice['rental_id'] = $dao->id;
            if ($dao->discharge) {
                $invoice['end_date'] = $dao->discharge;
            }
            $invoice['start_date'] = $first_day;
//            U::writeLog($invoice, "RentalsInvoice");
            try {
                $rentals_api = civicrm_api4('RentalsInvoice', 'create', ['values' => $invoice, 'checkPermissions' => FALSE]);
//                U::writeLog($rentals_api, "RentalsInvoice result");

            } catch (Exception $e) {
//                U::writeLog($e->getMessage(), "RentalsInvoice creation ");
            }
            $count++;
        }
        CRM_Core_Session::setStatus('Created ' . $count . " invoices out of " . $iFilteredTotal . " filtered", "Batch Invoice Create", 'success');

//        U::writeLog($values, 'MakeInvoices');
        parent::postProcess();
    }

}
