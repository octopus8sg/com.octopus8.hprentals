<?php
return [
    [
        'name' => 'SavedSearch_Invoices08',
        'entity' => 'SavedSearch',
        'cleanup' => 'unused',
        'update' => 'unmodified',
        'params' => [
            'version' => 4,
            'values' => [
                'name' => 'Invoices08',
                'label' => 'All Invoices',
                'form_values' => NULL,
                'mapping_id' => NULL,
                'search_custom_id' => NULL,
                'api_entity' => 'RentalsInvoice',
                'api_params' => [
                    'version' => 4,
                    'select' => [
                        'id',
                        'code',
                        'amount',
                        'created_id.display_name',
                        'created_date',
                        'modified_id.display_name',
                        'modified_date',
                        'rental_id',
                        'RentalsInvoice_RentalsRental_rental_id_01_RentalsRental_Contact_tenant_id_01.display_name',
                        'RentalsInvoice_RentalsRental_rental_id_01.admission',
                        'YEAR(start_date) AS YEAR_start_date',
                        'MONTH(start_date) AS MONTH_start_date',
                    ],
                    'orderBy' => [],
                    'where' => [['RentalsInvoice_RentalsRental_rental_id_01_RentalsRental_Contact_tenant_id_01.is_deleted',
                        '<>',
                        '1']],
                    'groupBy' => [],
                    'join' => [
                        [
                            'RentalsRental AS RentalsInvoice_RentalsRental_rental_id_01',
                            'INNER',
                            [
                                'rental_id',
                                '=',
                                'RentalsInvoice_RentalsRental_rental_id_01.id',
                            ],
                        ],
                        [
                            'Contact AS RentalsInvoice_RentalsRental_rental_id_01_RentalsRental_Contact_tenant_id_01',
                            'INNER',
                            [
                                'RentalsInvoice_RentalsRental_rental_id_01.tenant_id',
                                '=',
                                'RentalsInvoice_RentalsRental_rental_id_01_RentalsRental_Contact_tenant_id_01.id',

                            ],
                        ],
                    ],
                    'having' => [],
                ],
                'expires_date' => NULL,
                'description' => NULL,
            ],
        ],
    ],
    [
        'name' => 'SavedSearch_Invoices08_SearchDisplay_Invoices_Table08',
        'entity' => 'SearchDisplay',
        'cleanup' => 'unused',
        'update' => 'unmodified',
        'params' => [
            'version' => 4,
            'values' => [
                'name' => 'Invoices_Table08',
                'label' => 'Invoices Table',
                'saved_search_id.name' => 'Invoices08',
                'type' => 'table',
                'settings' => [
                    'actions' => TRUE,
                    'limit' => 50,
                    'classes' => [
                        'table',
                        'table-striped',
                    ],
                    'pager' => [],
                    'placeholder' => 5,
                    'sort' => [],
                    'columns' => [
                        [
                            'type' => 'field',
                            'key' => 'id',
                            'dataType' => 'Integer',
                            'label' => 'ID',
                            'sortable' => TRUE,
                        ],
                        [
                            'type' => 'field',
                            'key' => 'YEAR_start_date',
                            'dataType' => 'Integer',
                            'label' => 'Year',
                            'sortable' => TRUE,
                        ],
                        [
                            'type' => 'field',
                            'key' => 'MONTH_start_date',
                            'dataType' => 'Integer',
                            'label' => 'Month',
                            'sortable' => TRUE,
                        ],
                        [
                            'type' => 'field',
                            'key' => 'code',
                            'dataType' => 'String',
                            'label' => 'Invoice No',
                            'sortable' => TRUE,

                        ],
                        [
                            'type' => 'field',
                            'key' => 'RentalsInvoice_RentalsRental_rental_id_01_RentalsRental_Contact_tenant_id_01.display_name',
                            'dataType' => 'String',
                            'label' => 'Tenant',
                            'sortable' => TRUE,
                        ],
                        [
                            'type' => 'field',
                            'key' => 'RentalsInvoice_RentalsRental_rental_id_01.admission',
                            'dataType' => 'Date',
                            'label' => 'Admission',
                            'sortable' => TRUE,
                        ],
                        [
                            'type' => 'field',
                            'key' => 'amount',
                            'dataType' => 'Money',
                            'label' => 'Amount',
                            'sortable' => TRUE,

                        ],
                        [
                            'type' => 'field',
                            'key' => 'created_id.display_name',
                            'dataType' => 'String',
                            'label' => 'Created By',
                            'sortable' => TRUE,

                        ],
                        [
                            'type' => 'field',
                            'key' => 'created_date',
                            'dataType' => 'Timestamp',
                            'label' => 'Created Date',
                            'sortable' => TRUE,

                        ],
                        [
                            'type' => 'field',
                            'key' => 'modified_id.display_name',
                            'dataType' => 'String',
                            'label' => 'Modified By',
                            'sortable' => TRUE,

                        ],
                        [
                            'type' => 'field',
                            'key' => 'modified_date',
                            'dataType' => 'Timestamp',
                            'label' => 'Modified Date',
                            'sortable' => TRUE,

                        ],
                        [
                            'links' => [
                                [
                                    'entity' => '',
                                    'action' => '',
                                    'join' => '',
                                    'target' => 'crm-popup',
                                    'icon' => 'fa-eye',
                                    'text' => 'View',
                                    'style' => 'default',
                                    'path' => 'civicrm/rentals/invoice?id=[id]&action=preview&reset=1',
                                    'condition' => [],
                                ],
                                [
                                    'entity' => '',
                                    'action' => '',
                                    'join' => '',
                                    'target' => 'crm-popup',
                                    'icon' => 'fa-pencil',
                                    'text' => 'Edit',
                                    'style' => 'default',
                                    'path' => 'civicrm/rentals/invoice?id=[id]&action=update&reset=1',
                                    'condition' => [],
                                ],
                                [
                                    'entity' => '',
                                    'action' => '',
                                    'join' => '',
                                    'target' => 'crm-popup',
                                    'icon' => 'fa-trash-o',
                                    'text' => 'Delete',
                                    'style' => 'danger',
                                    'path' => 'civicrm/rentals/invoice?id=[id]&action=delete&reset=1',
                                    'condition' => [],
                                ]
                            ],
                            'type' => 'links',
                            'alignment' => 'text-right',
                            'label' => 'Actions',
                        ],
                    ],
                ],
                'acl_bypass' => FALSE,
            ],
        ],
    ],
];