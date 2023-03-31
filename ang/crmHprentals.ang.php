<?php
// This file declares an Angular module which can be autoloaded
// in CiviCRM. See also:
// \https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_angularModules/n
return [
    'js' => [
        'ang/crmHprentals.js',
        'ang/crmHprentals/*.js',
        'ang/crmHprentals/*/*.js',
        'ang/*.js',
    ],
    'css' => [
        'ang/crmHprentals.css',
    ],
    'partials' => [
        'ang/crmHprentals',
    ],
    'requires' => [
        'crmUi',
        'crmUtil',
        'ngRoute',

    ],
    'settings' => [],
];
