<?php

return [
    /**
     * Control if the seeder should create a user per role while seeding the data.
     */
    'create_users'    => true,

    /**
     * Control if all the laratrust tables should be truncated before running the seeder.
     */
    'truncate_tables' => true,

    'roles_structure' => [
        'admin'     => [
            'dashboard'        => '',
            'access'           => '',
            'user'             => 'c,r,u,d',
            'role'             => 'c,r,u,d',
            'permission'       => 'c,r,u,d',
            'manage_employer'  => '',
            'employer'         => 'c,r,u,d',
            'subject'          => 'c,r,u,d',
            'institute'        => 'c,r,u,d',
            'manage_candidate' => '',
            'candidate'        => 'c,r,u,d',
            'manage_coach'     => '',
            'coach'            => 'c,r,u,d',
        ],
        'employer'  => [],
        'candidate' => [],
        'coach'     => [],
    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete',
    ],
];
