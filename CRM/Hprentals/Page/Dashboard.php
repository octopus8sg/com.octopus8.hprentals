<?php

use CRM_Hprentals_ExtensionUtil as E;
use CRM_Hprentals_Utils as U;

class CRM_Hprentals_Page_Dashboard extends CRM_Core_Page
{
    const TENANT_TABLE = 'civicrm_contact';
    const EXPENSE_TABLE = 'civicrm_o8_rental_expense';
    const INVOICE_TABLE = 'civicrm_o8_rental_invoice';
    const PAYMENT_TABLE = 'civicrm_o8_rental_payment';
    const METHOD_TABLE = 'civicrm_o8_rental_method';
    const RENTAL_TABLE = 'civicrm_o8_rental_rental';

    public function run()
    {
        // Example: Set the page-title dynamically; alternatively, declare a static title in xml/Menu/*.xml
        CRM_Utils_System::setTitle(E::ts('Financial Dashboard'));

        // link for datatables
        $urlQry['snippet'] = 4;
        $invoice_source_url = CRM_Utils_System::url('civicrm/rentals/dashboard_ajax_invoice', $urlQry, FALSE, NULL, FALSE);
        $payment_source_url = CRM_Utils_System::url('civicrm/rentals/dashboard_ajax_payment', $urlQry, FALSE, NULL, FALSE);
        $rental_source_url = CRM_Utils_System::url('civicrm/rentals/dashboard_ajax_rental', $urlQry, FALSE, NULL, FALSE);
        $balance_source_url = CRM_Utils_System::url('civicrm/rentals/dashboard_ajax_balance', $urlQry, FALSE, NULL, FALSE);
//        $funds_source_url = "";
        $sourceUrl['invoices_source_url'] = $invoice_source_url;
        $sourceUrl['payments_source_url'] = $payment_source_url;
        $sourceUrl['rentals_source_url'] = $rental_source_url;
        $sourceUrl['balance_source_url'] = $balance_source_url;
        $this->assign('useAjax', true);
        CRM_Core_Resources::singleton()->addVars('source_url', $sourceUrl);

        // controller form for ajax search
        $controller_data = new CRM_Core_Controller_Simple(
            'CRM_Hprentals_Form_DashboardFilter',
            ts('Tenant Filter'),
            NULL,
            FALSE, FALSE, TRUE
        );
        $controller_data->setEmbedded(TRUE);
        $controller_data->run();

        // Example: Assign a variable for use in a template
        $this->assign('last_month', 'One Month');

        parent::run();
    }

    public function getInvoices()
    {

//        U::writeLog($_REQUEST,'invoices request');
//        U::writeLog($_POST,'invoices request');

        $invoice_table = self::INVOICE_TABLE;
        $tenant_table = self::TENANT_TABLE;
        $rental_table = self::RENTAL_TABLE;
        $tenant_id = CRM_Utils_Request::retrieveValue('tenant_id', 'Positive', null);
        $year = CRM_Utils_Request::retrieveValue('year', 'Positive', null);
        $month = CRM_Utils_Request::retrieveValue('month', 'Positive', null);
        $start_of_month = new \DateTime();
        $start_of_month->setDate($start_of_month->format('Y'), $start_of_month->format('m'), 1);
        if ($year && $month) {
            $start_of_month = new \DateTime("$year-$month-01");
        }
        $end_of_month = clone $start_of_month;
        $end_of_month->modify('last day of this month');
        $start_of_month_str = $start_of_month->format('Y-m-d');
        $end_of_month_str = $end_of_month->format('Y-m-d');

        $offset = CRM_Utils_Request::retrieveValue('iDisplayStart', 'Positive', 0);
//        CRM_Core_Error::debug_var('offset', $offset);

        $limit = CRM_Utils_Request::retrieveValue('iDisplayLength', 'Positive', 10);
//        CRM_Core_Error::debug_var('limit', $limit);


        $sortMapper = [
            0 => 'id',
            1 => 'code',
            2 => 'display_name',
            3 => 'start_date',
            4 => 'end_date',
            5 => 'created_date',
            6 => 'amount',
        ];

        $sort = isset($_REQUEST['iSortCol_0']) ? CRM_Utils_Array::value(CRM_Utils_Type::escape($_REQUEST['iSortCol_0'], 'Integer'), $sortMapper) : NULL;
        $sortOrder = isset($_REQUEST['sSortDir_0']) ? CRM_Utils_Type::escape($_REQUEST['sSortDir_0'], 'String') : 'asc';


//        $searchParams = self::getSearchOptionsFromRequest();
        $queryParams = [];

        $join = '';
        $where = [];

//        $isOrQuery = self::isOrQuery();

        $nextParamKey = 3;
        $sql = "
    SELECT SQL_CALC_FOUND_ROWS
      i.id,
      i.code,
      c.display_name,
      i.start_date,
      i.end_date,
      i.created_date,
      i.amount,
      r.tenant_id
    FROM $invoice_table i 
        INNER JOIN $rental_table r on r.id = i.rental_id
        INNER JOIN $tenant_table c on r.tenant_id = c.id
    AND c.is_deleted = 0
    WHERE DATE(i.start_date) >= '$start_of_month_str' 
    AND DATE(i.end_date) <= '$end_of_month_str' ";


        if (isset($tenant_id)) {
            if (is_numeric($tenant_id)) {
                $sql .= " AND c.`id` = " . intval($tenant_id) . " ";
            }
        }


        if ($sort !== NULL) {
            $sql .= " ORDER BY {$sort} {$sortOrder}";
        }

        if ($limit !== false) {
            if ($limit !== NULL) {
                if ($offset !== false) {
                    if ($offset !== NULL) {
                        $sql .= " LIMIT {$offset}, {$limit}";
                    }
                }
            }
        }

//        CRM_Core_Error::debug_var('method_sql', $sql);

        $dao = CRM_Core_DAO::executeQuery($sql);
        $iFilteredTotal = CRM_Core_DAO::singleValueQuery("SELECT FOUND_ROWS()");
        $rows = array();
        $count = 0;
        while ($dao->fetch()) {
            if (!empty($dao->tenant_id)) {
                $contact = '<a href="' . CRM_Utils_System::url('civicrm/contact/view',
                        ['reset' => 1, 'cid' => $dao->tenant_id]) . '">' .
                    CRM_Contact_BAO_Contact::displayName($dao->tenant_id) . '</a>';
            }
            $rows[$count][] = $dao->id;
            $rows[$count][] = $dao->code;
            $rows[$count][] = $contact;
            $rows[$count][] = $dao->start_date;
            $rows[$count][] = $dao->end_date;
            $rows[$count][] = $dao->created_date;
            $rows[$count][] = CRM_Utils_Money::formatLocaleNumericRoundedForDefaultCurrency(intval($dao->amount));
            $count++;
        }

        $searchRows = $rows;
        $iTotal = 0;
        if (is_countable($searchRows)) {
            $iTotal = sizeof($searchRows);
        }
        $hmdatas = [
            'data' => $searchRows,
            'recordsTotal' => $iTotal,
            'recordsFiltered' => $iFilteredTotal,
        ];
        if (!empty($_REQUEST['is_unit_test'])) {
            return $hmdatas;
        }
        CRM_Utils_JSON::output($hmdatas);
    }

    public function getPayments()
    {
        $payment_table = self::PAYMENT_TABLE;
        $invoice_table = self::INVOICE_TABLE;
        $tenant_table = self::TENANT_TABLE;
        $rental_table = self::RENTAL_TABLE;
        $method_table = self::METHOD_TABLE;
        $year = CRM_Utils_Request::retrieveValue('year', 'Positive', null);
        $month = CRM_Utils_Request::retrieveValue('month', 'Positive', null);
        $start_of_month = new \DateTime();
        $start_of_month->setDate($start_of_month->format('Y'), $start_of_month->format('m'), 1);
        if ($year && $month) {
            $start_of_month = new \DateTime("$year-$month-01");
        }
        $end_of_month = clone $start_of_month;
        $end_of_month->modify('last day of this month');
        $start_of_month_str = $start_of_month->format('Y-m-d');
        $end_of_month_str = $end_of_month->format('Y-m-d');

//        U::writeLog($_REQUEST,'payments request');
//        U::writeLog($_POST,'payments request');


        $tenant_id = CRM_Utils_Request::retrieveValue('tenant_id', 'Positive', null);

        $offset = CRM_Utils_Request::retrieveValue('iDisplayStart', 'Positive', 0);
//        CRM_Core_Error::debug_var('offset', $offset);

        $limit = CRM_Utils_Request::retrieveValue('iDisplayLength', 'Positive', 10);
//        CRM_Core_Error::debug_var('limit', $limit);


        $sortMapper = [
            0 => 'id',
            1 => 'code',
            2 => 'display_name',
            3 => 'created_date',
            4 => 'method',
            5 => 'amount',
        ];

        $sort = isset($_REQUEST['iSortCol_0']) ? CRM_Utils_Array::value(CRM_Utils_Type::escape($_REQUEST['iSortCol_0'], 'Integer'), $sortMapper) : NULL;
        $sortOrder = isset($_REQUEST['sSortDir_0']) ? CRM_Utils_Type::escape($_REQUEST['sSortDir_0'], 'String') : 'asc';


//        $searchParams = self::getSearchOptionsFromRequest();
        $queryParams = [];

        $join = '';
        $where = [];

//        $isOrQuery = self::isOrQuery();

        $nextParamKey = 3;
        $sql = "
    SELECT SQL_CALC_FOUND_ROWS
      i.id,
      i.code,
      c.display_name,
      i.created_date,
      m.name as method,
      i.amount,
      i.tenant_id
    FROM $payment_table i 
        INNER JOIN $tenant_table c on i.tenant_id = c.id
        INNER JOIN $method_table m on i.method_id = m.id
    AND c.is_deleted = 0
    WHERE DATE(i.created_date) >= '$start_of_month_str' 
    AND DATE(i.created_date) <= '$end_of_month_str' ";
//    U::writeLog($sql, 'payment_sql');
        if (isset($tenant_id)) {
            if (is_numeric($tenant_id)) {
                $sql .= " AND c.`id` = " . intval($tenant_id) . " ";
            }
        }


        if ($sort !== NULL) {
            $sql .= " ORDER BY {$sort} {$sortOrder}";
        }

        if ($limit !== false) {
            if ($limit !== NULL) {
                if ($offset !== false) {
                    if ($offset !== NULL) {
                        $sql .= " LIMIT {$offset}, {$limit}";
                    }
                }
            }
        }

//        CRM_Core_Error::debug_var('method_sql', $sql);

        $dao = CRM_Core_DAO::executeQuery($sql);
        $iFilteredTotal = CRM_Core_DAO::singleValueQuery("SELECT FOUND_ROWS()");
        $rows = array();
        $count = 0;
        while ($dao->fetch()) {
            if (!empty($dao->tenant_id)) {
                $contact = '<a href="' . CRM_Utils_System::url('civicrm/contact/view',
                        ['reset' => 1, 'cid' => $dao->tenant_id]) . '">' .
                    CRM_Contact_BAO_Contact::displayName($dao->tenant_id) . '</a>';
            }

            $rows[$count][] = $dao->id;
            $rows[$count][] = $dao->code;
            $rows[$count][] = $contact;
            $rows[$count][] = $dao->created_date;
            $rows[$count][] = $dao->method;
            $rows[$count][] = CRM_Utils_Money::formatLocaleNumericRoundedForDefaultCurrency(intval($dao->amount));
            $count++;
        }

        $searchRows = $rows;
        $iTotal = 0;
        if (is_countable($searchRows)) {
            $iTotal = sizeof($searchRows);
        }
        $hmdatas = [
            'data' => $searchRows,
            'recordsTotal' => $iTotal,
            'recordsFiltered' => $iFilteredTotal,
        ];
        if (!empty($_REQUEST['is_unit_test'])) {
            return $hmdatas;
        }
        CRM_Utils_JSON::output($hmdatas);
    }

    public function getRentals()
    {
        $payment_table = self::PAYMENT_TABLE;
        $invoice_table = self::INVOICE_TABLE;
        $tenant_table = self::TENANT_TABLE;
        $rental_table = self::RENTAL_TABLE;
        $method_table = self::METHOD_TABLE;

//        U::writeLog($_REQUEST,'rentals request');
//        U::writeLog($_POST,'rentals request');


        $tenant_id = CRM_Utils_Request::retrieveValue('tenant_id', 'Positive', null);
        $year = CRM_Utils_Request::retrieveValue('year', 'Positive', null);
        $month = CRM_Utils_Request::retrieveValue('month', 'Positive', null);
        $start_of_month = new \DateTime();
        $start_of_month->setDate($start_of_month->format('Y'), $start_of_month->format('m'), 1);
        if ($year && $month) {
            $start_of_month = new \DateTime("$year-$month-01");
        }
        $end_of_month = clone $start_of_month;
        $end_of_month->modify('last day of this month');
        $start_of_month_str = $start_of_month->format('Y-m-d');
        $end_of_month_str = $end_of_month->format('Y-m-d');

        $offset = CRM_Utils_Request::retrieveValue('iDisplayStart', 'Positive', 0);
//        CRM_Core_Error::debug_var('offset', $offset);

        $limit = CRM_Utils_Request::retrieveValue('iDisplayLength', 'Positive', 10);
//        CRM_Core_Error::debug_var('limit', $limit);


        $sortMapper = [
            0 => 'id',
            1 => 'display_name',
            2 => 'admission',
            3 => 'discharge'
        ];

        $sort = isset($_REQUEST['iSortCol_0']) ? CRM_Utils_Array::value(CRM_Utils_Type::escape($_REQUEST['iSortCol_0'], 'Integer'), $sortMapper) : NULL;
        $sortOrder = isset($_REQUEST['sSortDir_0']) ? CRM_Utils_Type::escape($_REQUEST['sSortDir_0'], 'String') : 'asc';


//        $searchParams = self::getSearchOptionsFromRequest();
        $queryParams = [];

        $join = '';
        $where = [];

//        $isOrQuery = self::isOrQuery();

        $nextParamKey = 3;;
        $sql = "
    SELECT SQL_CALC_FOUND_ROWS
      i.id,
      c.display_name,
      i.admission,
      i.discharge,
      i.tenant_id
    FROM $rental_table i 
        INNER JOIN $tenant_table c on i.tenant_id = c.id
    WHERE c.is_deleted = 0
";

        if (isset($tenant_id)) {
            if (is_numeric($tenant_id)) {
                $sql .= " AND c.`id` = " . intval($tenant_id) . " ";
            }
        }

        $where_date = "
       AND 
       ((DATE(i.admission) <= '$end_of_month_str' 
       AND DATE(i.discharge) >= '$start_of_month_str' ) OR
       (DATE(i.admission) <= '$end_of_month_str' 
       AND i.discharge IS NULL))
       ";
        $sql = $sql . $where_date;


        if ($sort !== NULL) {
            $sql .= " ORDER BY {$sort} {$sortOrder}";
        }

        if ($limit !== false) {
            if ($limit !== NULL) {
                if ($offset !== false) {
                    if ($offset !== NULL) {
                        $sql .= " LIMIT {$offset}, {$limit}";
                    }
                }
            }
        }

//        U::writeLog($sql);

        $dao = CRM_Core_DAO::executeQuery($sql);
        $iFilteredTotal = CRM_Core_DAO::singleValueQuery("SELECT FOUND_ROWS()");
        $rows = array();
        $count = 0;
        while ($dao->fetch()) {
            if (!empty($dao->tenant_id)) {
                $contact = '<a href="' . CRM_Utils_System::url('civicrm/contact/view',
                        ['reset' => 1, 'cid' => $dao->tenant_id]) . '">' .
                    CRM_Contact_BAO_Contact::displayName($dao->tenant_id) . '</a>';
            }
            $rows[$count][] = $dao->id;
            $rows[$count][] = $contact;
            $rows[$count][] = $dao->admission;
            $rows[$count][] = $dao->discharge;
            $count++;
        }

        $searchRows = $rows;
        $iTotal = 0;
        if (is_countable($searchRows)) {
            $iTotal = sizeof($searchRows);
        }
        $hmdatas = [
            'data' => $searchRows,
            'recordsTotal' => $iTotal,
            'recordsFiltered' => $iFilteredTotal,
        ];
        if (!empty($_REQUEST['is_unit_test'])) {
            return $hmdatas;
        }
        CRM_Utils_JSON::output($hmdatas);
    }


    public function getBalance()
    {

        $payment_table = self::PAYMENT_TABLE;
        $invoice_table = self::INVOICE_TABLE;
        $tenant_table = self::TENANT_TABLE;
        $rental_table = self::RENTAL_TABLE;
        $method_table = self::METHOD_TABLE;

//        U::writeLog($_REQUEST,'rentals request');
//        U::writeLog($_POST,'rentals request');


        $tenant_id = CRM_Utils_Request::retrieveValue('tenant_id', 'Positive', null);

        $offset = CRM_Utils_Request::retrieveValue('iDisplayStart', 'Positive', 0);
//        CRM_Core_Error::debug_var('offset', $offset);

        $limit = CRM_Utils_Request::retrieveValue('iDisplayLength', 'Positive', 10);
//        CRM_Core_Error::debug_var('limit', $limit);


        $sortMapper = [
            0 => 'tenant_id',
            1 => 'tenant_name',
        ];

        $sort = isset($_REQUEST['iSortCol_0']) ? CRM_Utils_Array::value(CRM_Utils_Type::escape($_REQUEST['iSortCol_0'], 'Integer'), $sortMapper) : NULL;
        $sortOrder = isset($_REQUEST['sSortDir_0']) ? CRM_Utils_Type::escape($_REQUEST['sSortDir_0'], 'String') : 'asc';


//        $searchParams = self::getSearchOptionsFromRequest();
        $queryParams = [];

        $join = '';
        $where = [];

//        $isOrQuery = self::isOrQuery();

        $nextParamKey = 3;
        $sql = "
SELECT DISTINCT t.id                       AS tenant_id,
       t.display_name             AS tenant_name
FROM $tenant_table t
         INNER JOIN
     $rental_table r ON t.id = r.tenant_id
         ";

        if (isset($tenant_id)) {
            if (is_numeric($tenant_id)) {
                $sql .= " AND t.`id` = " . intval($tenant_id) . " ";
            }
        }

        U::writeLog($sql, 'balance_sql');
        if ($sort !== NULL) {
            $sql .= " ORDER BY {$sort} {$sortOrder}";
        }

        if ($limit !== false) {
            if ($limit !== NULL) {
                if ($offset !== false) {
                    if ($offset !== NULL) {
                        $sql .= " LIMIT {$offset}, {$limit}";
                    }
                }
            }
        }

//        CRM_Core_Error::debug_var('method_sql', $sql);

        $dao = CRM_Core_DAO::executeQuery($sql);
        $iFilteredTotal = CRM_Core_DAO::singleValueQuery("SELECT FOUND_ROWS()");
        $rows = array();
        $count = 0;

        while ($dao->fetch()) {
            $dao_tenant_id = $dao->tenant_id;
            if (!empty($dao_tenant_id)) {
                $contact = '<a href="' . CRM_Utils_System::url('civicrm/contact/view',
                        ['reset' => 1, 'cid' => $dao_tenant_id]) . '">' .
                    CRM_Contact_BAO_Contact::displayName($dao_tenant_id) . '</a>';
            }
            $invoice_total_amount = U::getInvoiceTotalAmountByTenantId($dao_tenant_id);
            $payment_total_amount = U::getPaymentTotalAmountByTenantId($dao_tenant_id);
            $rows[$count][] = $dao_tenant_id;
            $rows[$count][] = $contact;
            $rows[$count][] = CRM_Utils_Money::format($invoice_total_amount);
            $rows[$count][] = CRM_Utils_Money::format($payment_total_amount);
            $rows[$count][] = CRM_Utils_Money::format($invoice_total_amount - $payment_total_amount);
            $count++;
        }

        $searchRows = $rows;
        $iTotal = 0;
        if (is_countable($searchRows)) {
            $iTotal = sizeof($searchRows);
        }
        $hmdatas = [
            'data' => $searchRows,
            'recordsTotal' => $iTotal,
            'recordsFiltered' => $iFilteredTotal,
        ];
        if (!empty($_REQUEST['is_unit_test'])) {
            return $hmdatas;
        }
        CRM_Utils_JSON::output($hmdatas);
    }
}
