<?php


return [
    'secret'      => env('JWT_SECRET'),
    //Asymmetric key
    'public_key'  => env('JWT_PUBLIC_KEY'),
    'private_key' => env('JWT_PRIVATE_KEY'),
    'password'    => env('JWT_PASSWORD'),
    //JWT time to live
    'ttl'         => env('JWT_TTL', 30 * 36000 * 24),
    //Refresh time to live
    'refresh_ttl' => env('JWT_REFRESH_TTL', 20160),
    //JWT hashing algorithm
    'algo'        => env('JWT_ALGO', 'HS256'),

    'blacklist_storage' => thans\jwt\provider\storage\Tp6::class,
];
