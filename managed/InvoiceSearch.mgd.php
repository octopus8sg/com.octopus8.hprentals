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
                        'description',
                        'amount',
                        'created_id.display_name',
                        'created_date',
                        'modified_id.display_name',
                        'modified_date',
                        'rental_id',
                        'RentalsInvoice_RentalsRental_rental_id_01_RentalsRental_Contact_tenant_id_01.display_name',
                        'RentalsInvoice_RentalsRental_rental_id_01.admission',
                    ],
                    'orderBy' => [],
                    'where' => [],
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
                            'tally' => [
                                'fn' => 'COUNT',
                            ],
                        ],
                        [
                            'type' => 'field',
                            'key' => 'rental_id',
                            'dataType' => 'Integer',
                            'label' => 'Rental ID',
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
                            'key' => 'code',
                            'dataType' => 'String',
                            'label' => 'Code',
                            'sortable' => TRUE,
                            'tally' => [
                                'fn' => NULL,
                            ],
                        ],
                        [
                            'type' => 'field',
                            'key' => 'description',
                            'dataType' => 'String',
                            'label' => 'Description',
                            'sortable' => TRUE,
                            'tally' => [
                                'fn' => NULL,
                            ],
                        ],
                        [
                            'type' => 'field',
                            'key' => 'amount',
                            'dataType' => 'Money',
                            'label' => 'Amount',
                            'sortable' => TRUE,
                            'tally' => [
                                'fn' => 'SUM',
                            ],
                        ],
                        [
                            'type' => 'field',
                            'key' => 'created_id.display_name',
                            'dataType' => 'String',
                            'label' => 'Created By',
                            'sortable' => TRUE,
                            'tally' => [
                                'fn' => NULL,
                            ],
                        ],
                        [
                            'type' => 'field',
                            'key' => 'created_date',
                            'dataType' => 'Timestamp',
                            'label' => 'Created Date',
                            'sortable' => TRUE,
                            'tally' => [
                                'fn' => NULL,
                            ],
                        ],
                        [
                            'type' => 'field',
                            'key' => 'modified_id.display_name',
                            'dataType' => 'String',
                            'label' => 'Modified By',
                            'sortable' => TRUE,
                            'tally' => [
                                'fn' => NULL,
                            ],
                        ],
                        [
                            'type' => 'field',
                            'key' => 'modified_date',
                            'dataType' => 'Timestamp',
                            'label' => 'Modified Date',
                            'sortable' => TRUE,
                            'tally' => [
                                'fn' => NULL,
                            ],
                        ],
                        [
                            'links' => [
                                [
                                    'entity' => 'RentalsInvoice',
                                    'action' => 'update',
                                    'join' => '',
                                    'target' => 'crm-popup',
                                    'icon' => 'fa-pencil',
                                    'text' => 'Edit Rentals Invoice',
                                    'style' => 'default',
                                    'path' => '',
                                    'condition' => [],
                                ],
                            ],
                            'type' => 'links',
                            'alignment' => 'text-right',
                            'label' => 'Actions',
                        ],
                    ],
                    'tally' => [
                        'label' => 'Total',
                    ],
                ],
                'acl_bypass' => FALSE,
            ],
        ],
    ],
];