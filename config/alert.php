<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cache Key Prefix
    |--------------------------------------------------------------------------
    |
    | When utilizing a RAM based store such as APC or Memcached, there might
    | be other applications utilizing the same cache. So, we'll specify a
    | value to get prefixed to all our keys so we can avoid collisions.
    |
    */

    'order' => [
        'animation'       => 'bounce',
        'align'           => 'center',
        'text_font'       => 'montserrat',
        'text_align'      => 'regular',
        'logo_align'      => 'top_center',
        'cover'           => 'none',
        'duration'        => 60,
        'text_font_color' => '#00C2FF',
        'text_font_size'  => 12,
        'logo_color'      => '#202029',
        'volume'          => 80,
        'username'        => null,
        'amount'          => null,
        'action'          => null,
        'message'         => null
    ],
    'tipping' => [
        'animation'       => 'bounce',
        'align'           => 'center',
        'text_font'       => 'montserrat',
        'text_align'      => 'regular',
        'logo_align'      => 'top_center',
        'cover'           => 'none',
        'duration'        => 60,
        'text_font_color' => '#00C2FF',
        'text_font_size'  => 12,
        'logo_color'      => '#202029',
        'volume'          => 80,
        'username'        => null,
        'amount'          => null,
        'action'          => null,
        'message'         => null
    ],
    'profanity' => [
        'replace'       => true,
        'replace_with'  => '*******',
        'hide_messages' => true,
        'bad_words'     => true
    ]

];