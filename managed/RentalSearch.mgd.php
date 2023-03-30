<?php
return [
    [
        'name' => 'SavedSearch_Rentals08',
        'entity' => 'SavedSearch',
        'cleanup' => 'always',
        'update' => 'always',
        'params' => [
            'version' => 4,
            'values' => [
                'name' => 'Rentals08',
                'label' => 'Rentals',
                'form_values' => NULL,
                'mapping_id' => NULL,
                'search_custom_id' => NULL,
                'api_entity' => 'RentalsRental',
                'api_params' => [
                    'version' => 4,
                    'select' => [
                        'id',
                        'code',
                        'RentalsRental_Contact_tenant_id_01.display_name',
                        'admission',
                        'discharge',
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
                    ],
                    'having' => [],
                ],
                'expires_date' => NULL,
                'description' => NULL,
            ],
        ],
    ],
    [
        'name' => 'SavedSearch_Rentals08_SearchDisplay_All_Rentals_Table08',
        'entity' => 'SearchDisplay',
        'cleanup' => 'always',
        'update' => 'always',
        'params' => [
            'version' => 4,
            'values' => [
                'name' => 'All_Rentals_Table08',
                'label' => 'All Rentals Table',
                'saved_search_id.name' => 'Rentals08',
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
                            'label' => 'Rental ID',
                            'sortable' => TRUE,
                        ],
                        [
                            'type' => 'field',
                            'key' => 'code',
                            'dataType' => 'String',
                            'label' => 'Code',
                            'sortable' => TRUE,
                        ],
                        [
                            'type' => 'field',
                            'key' => 'RentalsRental_Contact_tenant_id_01.display_name',
                            'dataType' => 'String',
                            'label' => 'Tenant',
                            'sortable' => TRUE,
                            'link' => [
                                'path' => '',
                                'entity' => 'Contact',
                                'action' => 'view',
                                'join' => 'RentalsRental_Contact_tenant_id_01',
                                'target' => '_blank',
                            ],
                            'title' => 'View Tenant',
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
                            'links' => [
                                [
                                    'entity' => '',
                                    'action' => '',
                                    'join' => '',
                                    'target' => 'crm-popup',
                                    'icon' => 'fa-trash-o',
                                    'text' => 'Delete',
                                    'style' => 'danger',
                                    'path' => 'civicrm/rentals/deleterental?id=[id]&action=delete&reset=1',
                                    'condition' => [],
                                ],
                                [
                                    'entity' => 'RentalsRental',
                                    'action' => 'update',
                                    'join' => '',
                                    'target' => 'crm-popup',
                                    'icon' => 'fa-pencil',
                                    'text' => 'View/Edit',
                                    'style' => 'default',
                                    'path' => '',
                                    'condition' => [],
                                ],
                            ],
                            'type' => 'links',
                            'alignment' => 'text-right',
                        ],
                    ],
                ],
                'acl_bypass' => TRUE,
            ],
        ],
    ],
    [
        'name' => 'SavedSearch_Rentals08_SearchDisplay_Tenant_Rentals_Table08',
        'entity' => 'SearchDisplay',
        'cleanup' => 'always',
        'update' => 'always',
        'params' => [
            'version' => 4,
            'values' => [
                'name' => 'Tenant_Rentals_Table08',
                'label' => 'Tenant Rentals Table',
                'saved_search_id.name' => 'Rentals08',
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
                            'key' => 'code',
                            'dataType' => 'String',
                            'label' => 'Code',
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
                            'links' => [
                                [
                                    'entity' => 'RentalsRental',
                                    'action' => 'update',
                                    'join' => '',
                                    'target' => 'crm-popup',
                                    'icon' => 'fa-pencil',
                                    'text' => 'Edit Rentals Rental',
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
                ],
                'acl_bypass' => FALSE,
            ],
        ],
    ],
];