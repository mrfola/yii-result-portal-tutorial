<?php

return [
    'updateResult' => [
        'type' => 2,
        'description' => 'Update result',
    ],
    'viewResultDashboard' => [
        'type' => 2,
        'description' => 'View Result Dashboard',
    ],
    'admin' => [
        'type' => 1,
        'ruleName' => 'userType',
        'children' => [
            'updateResult',
            'viewResultDashboard',
        ],
    ],
    'user' => [
        'type' => 1,
        'ruleName' => 'userType',
    ],
];
