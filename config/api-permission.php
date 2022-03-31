<?php

use App\Models\User;

return [
    User::ROLE_ADMIN => ['*'],
    User::ROLE_WRITER => [
        'posts.show',
        'posts.update',
        'posts.create',
        'posts.destroy',
    ],
    User::ROLE_SUBSCRIBER => [
        'posts.show',
    ]
];
