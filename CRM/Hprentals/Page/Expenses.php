<?php

use CRM_Hprentals_ExtensionUtil as E;
use CRM_Hprentals_Utils as U;

class CRM_Hprentals_Page_Expenses extends CRM_Core_Page
{

    public function run()
    {

// This part differs for different search pages
        CRM_Utils_System::setTitle(E::ts('Search Rentals Expenses'));
        $pageName = 'RentalsExpenses';
        $ajaxSourceName = 'expenses_source_url';
        $urlQry['snippet'] = 4;
        $ajaxSourceUrl = CRM_Utils_System::url('civicrm/rentals/expenses_ajax', $urlQry, FALSE, NULL, FALSE);
// End this part differs for different search pages

        $sourceUrl[$ajaxSourceName] = $ajaxSourceUrl;
        $this->assign('useAjax', true);
        CRM_Core_Resources::singleton()->addVars('source_url', $sourceUrl);

        // controller form for ajax search
        $controller_data = new CRM_Core_Controller_Simple(
            'CRM_Hprentals_Form_ExpenseFilter',
            ts('Types Filter'),
            NULL,
            FALSE, FALSE, TRUE
        );
        $controller_data->setEmbedded(TRUE);
        $controller_data->assign('pagename', $pageName);
        $controller_data->run();
        parent::run();
    }

    public function getAjax()
    {

        U::writeLog($_REQUEST,'request');
        U::writeLog($_POST,'post');


        $expense_id = CRM_Utils_Request::retrieveValue('expense_id', 'Positive', null);

        $expense_name = CRM_Utils_Request::retrieveValue('expense_name', 'String', null);

        $offset = CRM_Utils_Request::retrieveValue('iDisplayStart', 'Positive', 0);
//        CRM_Core_Error::debug_var('offset', $offset);

        $limit = CRM_Utils_Request::retrieveValue('iDisplayLength', 'Positive', 10);
//        CRM_Core_Error::debug_var('limit', $limit);


        $sortMapper = [
            0 => 'id',
            1 => 'name',
            2 => 'frequency',
            3 => 'is_refund',
            4 => 'is_prorate',
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
      e.id,
      e.name,
      e.frequency,
      e.is_refund,
      e.is_prorate,
      e.amount
    FROM civicrm_o8_rental_expense e 
    WHERE 1";


        if (isset($expense_id)) {
            if (is_numeric($expense_id)) {
                $sql .= " AND e.`id` = " . intval($expense_id) . " ";
            }
        }

        if (isset($expense_name)) {
            if (strval($expense_name) != "") {
                $sql .= " AND e.`name` like '%" . strval($expense_name) . "%' ";
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

//        CRM_Core_Error::debug_var('expense_sql', $sql);

        $dao = CRM_Core_DAO::executeQuery($sql);
        $iFilteredTotal = CRM_Core_DAO::singleValueQuery("SELECT FOUND_ROWS()");
        $rows = array();
        $count = 0;
        $frequencies = U::getExpenseFrequency();
        $yesNo = [0 => "No", 1 => "Yes"];
        while ($dao->fetch()) {

            $r_update = CRM_Utils_System::url(U::PATH_EXPENSE,
                ['action' => 'update', 'id' => $dao->id]);
            $r_delete = CRM_Utils_System::url(U::PATH_EXPENSE,
                ['action' => 'delete', 'id' => $dao->id]);
            $update = '<a class="update-expense action-item crm-hover-button" target="_blank" href="' . $r_update . '"><i class="crm-i fa-pencil"></i>&nbsp;Edit</a>';
            $delete = '<a class="delete-expense action-item crm-hover-button" target="_blank" href="' . $r_delete . '"><i class="crm-i fa-trash"></i>&nbsp;Delete</a>';
            $action = "<span>$update $delete</span>";
            $rows[$count][] = $dao->id;
            $rows[$count][] = $dao->name;
            $rows[$count][] = $frequencies[$dao->frequency];
            $rows[$count][] = $yesNo[intval($dao->is_refund)];
            $rows[$count][] = $yesNo[intval($dao->is_prorate)];
            $rows[$count][] = CRM_Utils_Money::formatLocaleNumericRoundedForDefaultCurrency($dao->amount);
            $rows[$count][] = $action;
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
