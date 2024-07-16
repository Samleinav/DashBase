<?php

return [
    [
        'name' => 'Dashboard',
        'flag' => 'dashboard.index',
    ],

    [
        'name' => 'Profile',
        'flag' => 'user.profile',
    ],
        [
            'name' => 'Edit',
            'flag' => 'user.edit-account',
            'parent_flag' => 'user.profile',
        ],

    [
        'name' => 'Settings',
        'flag' => 'settings.index',
    ],
        [
            'name' => 'Notifications',
            'flag' => 'notifications.index',
            'parent_flag' => 'settings.index',
        ],
            [
                "name" => 'edit',
                "flag" => 'notifications.edit',
                "parent_flag" => 'notifications.index',
            ],
            [
                'name' => 'delete',
                'flag' => 'notifications.destroy',
                'parent_flag' => 'notifications.index',
            ],
        [
            'name' => 'Messages',
            'flag' => 'message.index',
            'parent_flag' => 'settings.index',
        ],
            [
                "name" => 'edit',
                "flag" => 'message.edit',
                "parent_flag" => 'message.index',
            ],
            [
                'name' => 'delete',
                'flag' => 'message.destroy',
                'parent_flag' => 'message.index',
            ],
   
    
    [
        'name' => 'Customers',
        'flag' => 'customers.index',
    ],
        [
            'name' => 'Create',
            'flag' => 'customers.create',
            'parent_flag' => 'customers.index',
        ],
        [
            'name' => 'Edit',
            'flag' => 'customers.edit',
            'parent_flag' => 'customers.index',
        ],
        [
            'name' => 'Delete',
            'flag' => 'customers.destroy',
            'parent_flag' => 'customers.index',
        ],
    [
        'name' => 'Roles',
        'flag' => 'roles.index',
    ],
        [
            'name' => 'Create',
            'flag' => 'roles.create',
            'parent_flag' => 'roles.index',
        ],
        [
            'name' => 'Edit',
            'flag' => 'roles.edit',
            'parent_flag' => 'roles.index',
        ],
        [
            'name' => 'Delete',
            'flag' => 'roles.destroy',
            'parent_flag' => 'roles.index',
        ],

    
];
