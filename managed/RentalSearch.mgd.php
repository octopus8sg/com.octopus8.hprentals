<?php
return [
    [
        'name' => 'SavedSearch_Rentals',
        'entity' => 'SavedSearch',
        'cleanup' => 'unused',
        'update' => 'unmodified',
        'params' => [
            'version' => 4,
            'values' => [
                'name' => 'Rentals',
                'label' => 'All Rentals',
                'form_values' => NULL,
                'mapping_id' => NULL,
                'search_custom_id' => NULL,
                'api_entity' => 'RentalsRental',
                'api_params' => [
                    'version' => 4,
                    'select' => [
                        'id',
                        'RentalsRental_Contact_tenant_id_01.display_name',
                        'admission',
                        'discharge',
                        'first_invoice_id',
                    ],
                    'orderBy' => [],
                    'where' => [],
                    'groupBy' => [],
                    'join' => [
                        [
                            'Contact AS RentalsRental_Contact_tenant_id_01',
                            'LEFT',
                            [
                                'tenant_id',
                                '=',
                                'RentalsRental_Contact_tenant_id_01.id',
                            ],
                        ],
                        [
                            'RentalsInvoice AS RentalsRental_RentalsInvoice_rental_id_01',
                            'LEFT',
                            [
                                'id',
                                '=',
                                'RentalsRental_RentalsInvoice_rental_id_01.rental_id',
                            ],
                            [
                                'RentalsRental_RentalsInvoice_rental_id_01.is_first',
                                '=',
                                TRUE,
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
        'name' => 'SavedSearch_All_Rentals_SearchDisplay_All_Rentals_Table_1',
        'entity' => 'SearchDisplay',
        'cleanup' => 'unused',
        'update' => 'unmodified',
        'params' => [
            'version' => 4,
            'values' => [
                'name' => 'All_Rentals_Table_1',
                'label' => 'All Rentals Table 1',
                'saved_search_id.name' => 'Rentals',
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
                            'label' => 'id',
                            'sortable' => TRUE,
                        ],
                        [
                            'type' => 'field',
                            'key' => 'RentalsRental_Contact_tenant_id_01.display_name',
                            'dataType' => 'String',
                            'label' => 'Rentals Rental tenant_id: Display Name',
                            'sortable' => TRUE,
                        ],
                        [
                            'type' => 'field',
                            'key' => 'admission',
                            'dataType' => 'Date',
                            'label' => 'Admission',
                            'sortable' => TRUE,
                        ],
                        [
                            'type' => 'field',
                            'key' => 'discharge',
                            'dataType' => 'Date',
                            'label' => 'Discharge',
                            'sortable' => TRUE,
                        ],
                        [
                            'type' => 'field',
                            'key' => 'first_invoice_id',
                            'dataType' => 'Integer',
                            'label' => 'first_invoice_id',
                            'sortable' => TRUE,
                        ],
                    ],
                ],
                'acl_bypass' => FALSE,
            ],
        ],
    ],
];