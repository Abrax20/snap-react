<?php 
// Main Server variables
    // Color variables
    $color = [
        'primary' => '#387ef5',
        'secondary' => '#32db64',
        'danger' => '#f53d3d',
        'light' => '#f4f4f4',
        'dark' => '#222'
    ];
    // End Color variables

    $domain = 'http://localhost/app/';
    $mysql = [
        'adress' => 'localhost',
        'port' => '',
        'user' => 'root',
        'passwort' => '',
        'database' => 'snapreact'
    ];
    $counter_mysql = [
        'adress' => 'localhost',
        'port' => '',
        'user' => 'root',
        'passwort' => '',
        'database' => 'snapreact_counter'
    ];

// End Main variables
// SystemTable variables
    $systemtable = [
        'tables' => [
            'variables' => ['sn_user_list', 'sn_register_pin_list', 'sn_token_list', 'sn_user_settings_list', 'sn_user_register_on_network_data'],
            'sn_user_list' => [
                'variables' => ['id', 'email', 'username', 'password', 'created_on', 'Confirmed'],
                'id' => 'id',
                'email' => 'string',
                'username' => 'string',
                'password' => 'string',
                'created_on' => 'string',
                'Confirmed' => 'string',
            ],
            'sn_register_pin_list' => [
                'variables' => ['id', 'owner_id', 'pin'],
                'id' => 'id',
                'owner_id' => 'number',
                'pin' => 'number',
            ],
            'sn_token_list' => [
                'variables' => ['id', 'owner_id', 'deviceid', 'deviceplatform', 'devicemodel', 'deviceversion', 'auth_tocken'],
                'id' => 'id',
                'owner_id' => 'number',
                'deviceid' => 'string',
                'deviceplatform' => 'string',
                'devicemodel' => 'string',
                'deviceversion' => 'string',
                'auth_tocken' => 'string',
            ],
        ]
    ];
// End SystemTable variables
// CounterTable variables
    $countertable = [
        'tables' => [
        ]
    ];
// End CounterTable variables
// Accountsettings variables
// number, string, boolean
    $accountvariables = [
        'variables' => ['account_settings', 'account_variables'],
        'account_variables' => [
            '' => [''],
            '' => ''
        ],
        'account_settings' => [
            'variables' => ['use_pin', 'auto_synchronize'],            
            'use_pin' => 'boolean',
            'use_auto_synchronize' => 'boolean'
        ]
    ];
// End Accountsettings variables
// Main variables
    $main = [
        'system' => 'main.php',
        'server' => [
            'variables' => ['world'],
            'world' => [
                'server_title' => '',
                'server_description' => '',
                'server_icon' => '',
                'server_type' => '',
                'server_url' => $domain 
            ]
        ],
        'pages' => [
            'auth' => 'auth.php',
            'account_management' => 'account/'
        ],
        'email' =>[
            'color' => [
                'h1' => 'black',
                'h2' => 'black',
                'h3' => 'black',
                'h4' => 'black',
                'h5' => 'black',
                'h6' => 'black',
                'p' => 'black',
                'background' => 'white'
            ]
        ]
    ];