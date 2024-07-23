<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Time And Date API Config Parameters
    |--------------------------------------------------------------------------
    |
    */

    'dstlist' => [
        'url' => env('TIMEANDDATE_DSTLIST_URL')
    ],
    'access_key'   => env('TIMEANDDATE_ACCESS_KEY'),
    'secret_key'   => env('TIMEANDDATE_SECRET_KEY'),
    'version'      => 1,
    'pretty_print' => 1,
    'out'          => 'js',
    'lang'         => 'en',
    'time_changes' => 1,
    'only_dst'     => 1,
    'list_places'  => 0
];
