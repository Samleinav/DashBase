<?php

return [
    [
        'name' => 'Dashplugins',
        'flag' => 'dashplugin.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'dashplugin.create',
        'parent_flag' => 'dashplugin.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'dashplugin.edit',
        'parent_flag' => 'dashplugin.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'dashplugin.destroy',
        'parent_flag' => 'dashplugin.index',
    ],

    
    [
        'name' => 'Customers',
        'flag' => 'customer.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'customer.create',
        'parent_flag' => 'customer.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'customer.edit',
        'parent_flag' => 'customer.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'customer.destroy',
        'parent_flag' => 'customer.index',
    ],

    
    [
        'name' => 'Features',
        'flag' => 'feature.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'feature.create',
        'parent_flag' => 'feature.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'feature.edit',
        'parent_flag' => 'feature.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'feature.destroy',
        'parent_flag' => 'feature.index',
    ],

    [
        'name' => 'Services',
        'flag' => 'service.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'service.create',
        'parent_flag' => 'service.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'service.edit',
        'parent_flag' => 'service.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'service.destroy',
        'parent_flag' => 'service.index',
    ],

    
    [
        'name' => 'Taxes',
        'flag' => 'tax.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'tax.create',
        'parent_flag' => 'tax.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'tax.edit',
        'parent_flag' => 'tax.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'tax.destroy',
        'parent_flag' => 'tax.index',
    ],

    
    [
        'name' => 'Invoice Template',
        'flag' => 'invoice.template',
        'parent_flag' => 'dashplugin.settings',
    ],

    [
        'name' => 'Coupons',
        'flag' => 'coupons.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'coupons.create',
        'parent_flag' => 'coupons.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'coupons.edit',
        'parent_flag' => 'coupons.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'coupons.destroy',
        'parent_flag' => 'coupons.index',
    ],
    [
        'name' => 'Dash Settings',
        'flag' => 'dashplugin.settings',
    ],
];
