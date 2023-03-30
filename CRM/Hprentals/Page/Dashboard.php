<?php

use CRM_Hprentals_ExtensionUtil as E;
use CRM_Hprentals_Utils as U;

class CRM_Hprentals_Page_Dashboard extends CRM_Core_Page
{

    public function run()
    {
        // Example: Set the page-title dynamically; alternatively, declare a static title in xml/Menu/*.xml
        CRM_Utils_System::setTitle(E::ts('Financial Dashboard'));

        // link for datatables
        $urlQry['snippet'] = 4;
        $invoice_source_url = CRM_Utils_System::url('civicrm/rentals/dashboard_ajax_invoice', $urlQry, FALSE, NULL, FALSE);
        $payment_source_url = CRM_Utils_System::url('civicrm/rentals/dashboard_ajax_payment', $urlQry, FALSE, NULL, FALSE);
        $rental_source_url = CRM_Utils_System::url('civicrm/rentals/dashboard_ajax_rental', $urlQry, FALSE, NULL, FALSE);
//        $funds_source_url = "";
        $sourceUrl['invoices_source_url'] = $invoice_source_url;
        $sourceUrl['payments_source_url'] = $payment_source_url;
        $sourceUrl['rentals_source_url'] = $rental_source_url;
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
        $this->assign('last_month', date('M Y'));

        parent::run();
    }

    public function getInvoices()
    {

//        U::writeLog($_REQUEST,'invoices request');
//        U::writeLog($_POST,'invoices request');


        $tenant_id = CRM_Utils_Request::retrieveValue('tenant_id', 'Positive', null);

        $offset = CRM_Utils_Request::retrieveValue('iDisplayStart', 'Positive', 0);
//        CRM_Core_Error::debug_var('offset', $offset);

        $limit = CRM_Utils_Request::retrieveValue('iDisplayLength', 'Positive', 10);
//        CRM_Core_Error::debug_var('limit', $limit);


        $sortMapper = [
            0 => 'id',
            1 => 'code',
            2 => 'display_name',
            3 => 'created_at',
            4 => 'amount',
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
      i.amount
    FROM civicrm_o8_rental_invoice i 
        INNER JOIN civicrm_o8_rental_rental r on r.id = i.rental_id
        INNER JOIN civicrm_contact c on r.tenant_id = c.id
    WHERE YEAR(i.created_date) = YEAR(CURRENT_DATE()) 
    AND MONTH(i.created_date) = MONTH(CURRENT_DATE()) ";

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
            $rows[$count][] = $dao->id;
            $rows[$count][] = $dao->code;
            $rows[$count][] = $dao->display_name;
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

//        U::writeLog($_REQUEST,'invoices request');
//        U::writeLog($_POST,'invoices request');


        $tenant_id = CRM_Utils_Request::retrieveValue('tenant_id', 'Positive', null);

        $offset = CRM_Utils_Request::retrieveValue('iDisplayStart', 'Positive', 0);
//        CRM_Core_Error::debug_var('offset', $offset);

        $limit = CRM_Utils_Request::retrieveValue('iDisplayLength', 'Positive', 10);
//        CRM_Core_Error::debug_var('limit', $limit);


        $sortMapper = [
            0 => 'id',
            1 => 'code',
            2 => 'display_name',
            3 => 'created_at',
            4 => 'amount',
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
      i.amount
    FROM civicrm_o8_rental_payment i 
        INNER JOIN civicrm_contact c on i.tenant_id = c.id
    WHERE YEAR(i.created_date) = YEAR(CURRENT_DATE()) 
    AND MONTH(i.created_date) = MONTH(CURRENT_DATE()) ";

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
            $rows[$count][] = $dao->id;
            $rows[$count][] = $dao->code;
            $rows[$count][] = $dao->display_name;
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

    public function getRentals()
{

//        U::writeLog($_REQUEST,'invoices request');
//        U::writeLog($_POST,'invoices request');


    $tenant_id = CRM_Utils_Request::retrieveValue('tenant_id', 'Positive', null);

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

    $nextParamKey = 3;
    $sql = "
    SELECT SQL_CALC_FOUND_ROWS
      i.id,
      c.display_name,
      i.admission,
      i.discharge
    FROM civicrm_o8_rental_rental i 
        INNER JOIN civicrm_contact c on i.tenant_id = c.id
    WHERE YEAR(i.created_date) = YEAR(CURRENT_DATE()) 
    AND MONTH(i.created_date) = MONTH(CURRENT_DATE()) ";

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
        $rows[$count][] = $dao->id;
        $rows[$count][] = $dao->display_name;
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
}
