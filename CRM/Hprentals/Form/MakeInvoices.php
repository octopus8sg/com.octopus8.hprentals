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
    const RENTAL_TABLE = 'civicrm_o8_rental_rental';
    const INVOICE_TABLE = 'civicrm_o8_rental_invoice';

    public function buildQuickForm()
    {

        /*
         * Ex:
             * // first select
             * $select1[0] = 'Pop';
             * $select1[1] = 'Classical';
             * $select1[2] = 'Funeral doom';
             *
             * // second select
             * $select2[0][0] = 'Red Hot Chil Peppers';
             * $select2[0][1] = 'The Pixies';
             * $select2[1][0] = 'Wagner';
             * $select2[1][1] = 'Strauss';
             * $select2[2][0] = 'Pantheist';
             * $select2[2][1] = 'Skepticism';
             *
             * // If only need two selects
             * //     - and using the depracated functions
             * $sel =& $form->addElement('hierselect', 'cds', 'Choose CD:');
             * $sel->setMainOptions($select1);
             * $sel->setSecOptions($select2);
         */

        $uninvoicedMonthsHierSelectArrays = self::getUninvoicedMonthsHierSelectArrays();
        $years = $uninvoicedMonthsHierSelectArrays['years'];
//        $yearsAssoc = array_combine($years, $years);
        $months = $uninvoicedMonthsHierSelectArrays['months'];

        $sel =& $this->addElement('hierselect', 'uninvoicedmonths', 'Choose Rental Months');
        $sel->setMainOptions($years);
        $sel->setSecOptions($months);
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
        $year = intval($values['uninvoicedmonths'][0]);
        $month = intval($values['uninvoicedmonths'][1]);
        $rentals = self::getUninvoicedRentals($year, $month);
        $iFilteredTotal = sizeof($rentals);
        $count = 0;
        $start_of_month = new \DateTime("$year-$month-01");
        $end_of_month = clone $start_of_month;
        $end_of_month->modify('last day of this month');
        $start_of_month_str = $start_of_month->format('Y-m-d');
        $end_of_month_str = $end_of_month->format('Y-m-d');
        foreach ($rentals as $rental) {
            list($rental_id, $year_number, $month_number) = explode('-', $rental);
            $rental_id = intval($rental_id);
            $the_rental = self::getRental($rental_id);
//            U::writeLog($the_rental);
            $rental_start_date = $the_rental['admission'];
            $rental_end_date = $the_rental['discharge'];
            $first_day = $start_of_month_str;
            if ($rental_start_date > $first_day) {
                $first_day = $rental_start_date;
            }
            $last_date = $end_of_month_str;
            $closed = false;
            if ($rental_end_date) {
                if ($last_date >= $rental_end_date) {
                    $last_date = $rental_end_date;
                    $closed = true;
                }
            }
            $calculate_expenses = U::calculate_expenses($first_day, $last_date, $rental_start_date, $closed);
            $invoice = [];
            $invoice['code'] = "";
            $invoice['description'] = $calculate_expenses['description'];
            $invoice['amount'] = $calculate_expenses['total'];
            $invoice['rental_id'] = $rental_id;
            if ($last_date) {
                $invoice['end_date'] = $last_date;
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

    /**
     * Get an array of invoice months for each invoice record in the database.
     *
     * @return array An array containing rental_id-year-month values for all invoice records.
     * @throws Exception
     * @author Dr. Khindol Madraimov <khindol.madraimov@gmail.com>
     */
    public static function getInvoiceMonths()
    {
        $invoice_table = self::INVOICE_TABLE;
        $invoice_months = [];
        $sql2 = "SELECT  
                rental_id,
                DATE_FORMAT(start_date, \"%Y-%m\") as start
        FROM $invoice_table";
        $dao2 = CRM_Core_DAO::executeQuery($sql2);
        while ($dao2->fetch()) {
            $invoice_months[] = $dao2->rental_id . '-' . $dao2->start;
        }

        return $invoice_months;
    }

    /**
     * Get an array of unpaid months (rental months with no corresponding invoice).
     *
     * @return array An array containing rental_id-year-month values for unpaid months.
     * @throws Exception
     * @author Dr. Khindol Madraimov <khindol.madraimov@gmail.com>
     */
    public static function getUninvoicedMonths()
    {
        $rental_months = U::getRentalMonths();
        $invoiced_months = self::getInvoiceMonths();
        $uninvoiced_months = array_diff($rental_months, $invoiced_months);
        return $uninvoiced_months;

    }

    /**
     * Get an array of unpaid months organized in hierarchical arrays for years and months.
     *
     * @return array An array with 'years' and 'months' keys, each containing unpaid months.
     * @throws Exception
     * @author Dr. Khindol Madraimov <khindol.madraimov@gmail.com>
     */
    public static function getUninvoicedMonthsHierSelectArrays()
    {
        $uninvoiced_months = self::getUninvoicedMonths();
        $hierSelectArrays = U::getHierSelectArrays($uninvoiced_months);
        return $hierSelectArrays;
    }

    public static function getRentalForMonth($year, $month)
    {
        $rental_table = self::RENTAL_TABLE;
        $start_of_month = new \DateTime("$year-$month-01");
        $end_of_month = clone $start_of_month;
        $end_of_month->modify('last day of this month');
        $start_of_month_str = $start_of_month->format('Y-m-d');
        $end_of_month_str = $end_of_month->format('Y-m-d');
        $is_the_current_months = U::isTheCurrentMonth($year, $month);
        $sql = "SELECT  
            id,
            admission,
            discharge 
        FROM $rental_table
        WHERE admission <= '$end_of_month_str'
        AND (discharge >= '$start_of_month_str' OR discharge is NULL)";

        //skip unclosed rentals for the current month
        if ($is_the_current_months) {
            $sql = "SELECT  
            id,
            admission,
            discharge 
        FROM $rental_table
        WHERE admission <= '$end_of_month_str'
        AND discharge >= '$start_of_month_str'";
        }
        $dao = CRM_Core_DAO::executeQuery($sql);
        $rental_months = [];
        $interval = \DateInterval::createFromDateString('1 month');

        while ($dao->fetch()) {
            $start = new \DateTime($dao->admission);
            if ($dao->discharge) {
                $end = new \DateTime($dao->discharge);
            } else {
                $end = $end_of_month;
            }
            $end->add($interval);
            $period = new \DatePeriod($start, $interval, $end);
            // Convert the DatePeriod to an array using iterator_to_array()

            foreach ($period as $dt) {
                $dtYear = $dt->format('Y');
                $dtMonth = $dt->format('m');
//                echo "\ndtYear $dtYear dtMonth $dtMonth year $year month $month \n";
                // Check if the year and month match the optional parameters
                if ($dtYear == $year && $dtMonth == $month) {
                    $rental_months[] = $dao->id . '-' . $dt->format('Y-m');
                }
            }
        }
        return $rental_months;
    }

    public static function getInvoiceForMonth($year, $month)
    {
        $invoice_table = self::INVOICE_TABLE;
        $start_of_month = new \DateTime("$year-$month-01");
        $end_of_month = clone $start_of_month;
        $end_of_month->modify('last day of this month');
        $start_of_month_str = $start_of_month->format('Y-m-d');
        $end_of_month_str = $end_of_month->format('Y-m-d');
        $invoice_months = [];
        $sql2 = "SELECT  
                rental_id,
                DATE_FORMAT(start_date, \"%Y-%m\") as start
        FROM $invoice_table
        WHERE start_date <= '$end_of_month_str'
        AND end_date >= '$start_of_month_str'";
        $dao2 = CRM_Core_DAO::executeQuery($sql2);
        while ($dao2->fetch()) {
            $invoice_months[] = $dao2->rental_id . '-' . $dao2->start;
        }
        return $invoice_months;
    }

    public static function getUninvoicedRentals($year, $month)
    {
        $rental_months = self::getRentalForMonth($year, $month);
        $invoiced_months = self::getInvoiceForMonth($year, $month);
        $uninvoiced_rentals = array_diff($rental_months, $invoiced_months);
        return $uninvoiced_rentals;
    }

    /**
     * @param int $rental_id
     */
    public static function getRental(int $rental_id)
    {
        $lastRental = null;
        try {
            $lastRental = civicrm_api4('RentalsRental', 'get', [
                'select' => [
                    'id', 'admission', 'discharge',
                ],
                'limit' => 1,
                'checkPermissions' => FALSE,
                'where' => [
                    ['id', '=', $rental_id],
                ],
            ])[0];

//            self::writeLog($lastInvoice, 'lastInvoice');
        } catch (Exception $e) {
            U::writeLog($e->getMessage());
        }
        return $lastRental;
    }
}
