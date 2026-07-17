<?php

return [
    'home' => [
        'label' => 'Home',
        'route' => 'home',
    ],

    'workreport' => [
        'label' => 'Work Report',
        'route' => 'workreport.index',
        'children' => [
            'workreport.index' => 'List',
            'workreport.create' => 'Create',
            'workreport.show' => 'Detail',
            'workreport.edit' => 'Edit',
        ]
    ],

    'users' => [
        'label' => 'Manage User',
        'route' => 'users.index',
        'children' => [
            'users.index' => 'List'
        ]
    ]
];
