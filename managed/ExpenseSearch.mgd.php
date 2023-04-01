<?php
return [
    [
        'name' => 'SavedSearch_Expenses08',
        'entity' => 'SavedSearch',
        'cleanup' => 'unused',
        'update' => 'unmodified',
        'params' => [
            'version' => 4,
            'values' => [
                'name' => 'Expenses08',
                'label' => 'Types',
                'form_values' => NULL,
                'mapping_id' => NULL,
                'search_custom_id' => NULL,
                'api_entity' => 'RentalsExpense',
                'api_params' => [
                    'version' => 4,
                    'select' => [
                        'id',
                        'name',
                        'frequency:label',
                        'is_refund',
                        'is_prorate',
                        'amount',
                    ],
                    'orderBy' => [],
                    'where' => [],
                    'groupBy' => [],
                    'join' => [],
                    'having' => [],
                ],
                'expires_date' => NULL,
                'description' => NULL,
            ],
        ],
    ],
    [
        'name' => 'SavedSearch_Expenses08_SearchDisplay_Expenses_Table08',
        'entity' => 'SearchDisplay',
        'cleanup' => 'unused',
        'update' => 'unmodified',
        'params' => [
            'version' => 4,
            'values' => [
                'name' => 'Expenses_Table08',
                'label' => 'Types Table',
                'saved_search_id.name' => 'Expenses08',
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
                            'key' => 'name',
                            'dataType' => 'String',
                            'label' => 'Name',
                            'sortable' => TRUE,
                        ],
                        [
                            'type' => 'field',
                            'key' => 'frequency:label',
                            'dataType' => 'String',
                            'label' => 'Frequency',
                            'sortable' => TRUE,
                        ],
                        [
                            'type' => 'field',
                            'key' => 'is_refund',
                            'dataType' => 'Boolean',
                            'label' => 'Refund',
                            'sortable' => TRUE,
                        ],
                        [
                            'type' => 'field',
                            'key' => 'is_prorate',
                            'dataType' => 'Boolean',
                            'label' => 'Prorate',
                            'sortable' => TRUE,
                        ],
                        [
                            'type' => 'field',
                            'key' => 'amount',
                            'dataType' => 'Money',
                            'label' => 'Amount',
                            'sortable' => TRUE,
                            'editable' => TRUE,
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
                                    'path' => 'civicrm/rentals/deleteexpense?id=[id]&action=delete&reset=1',
                                    'condition' => [],
                                ],
                                [
                                    'entity' => 'RentalsExpense',
                                    'action' => 'update',
                                    'join' => '',
                                    'target' => 'crm-popup',
                                    'icon' => 'fa-pencil',
                                    'text' => 'Edit',
                                    'style' => 'default',
                                    'path' => '',
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