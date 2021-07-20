<?php

return [
    /*
    |--------------------------------------------------------------------------
    | User Model
    |--------------------------------------------------------------------------
    |
    | This is the User model used by Initials Avatar Generator.
    |
    */
    'user_model' => \App\Models\User::class,

    /*
    |--------------------------------------------------------------------------
    | Users Table
    |--------------------------------------------------------------------------
    |
    | This is the users table name used by Initials Avatar Generator.
    |
    */
    'users_table' => 'users',

    /*
    |--------------------------------------------------------------------------
    | Default Avatar Size
    |--------------------------------------------------------------------------
    |
    | Size of avatar image in pixels.
    |
    */
    'size' => 500,

    /*
    |--------------------------------------------------------------------------
    | Default Font Colour
    |--------------------------------------------------------------------------
    |
    | Font colour of avatar initials.
    |
    */
    'font_colour' => 'ffffff',

    /*
    |--------------------------------------------------------------------------
    | Uppercase Initials
    |--------------------------------------------------------------------------
    |
    | If initials should be uppercase.
    |
    */
    'uppercase' => true,

    /*
    |--------------------------------------------------------------------------
    | Initials Bold
    |--------------------------------------------------------------------------
    |
    | If initials should be bold.
    |
    */
    'bold' => true,

    /*
    |--------------------------------------------------------------------------
    | Background Colour Ranges
    |--------------------------------------------------------------------------
    |
    | Background colour ranges that can be used to generate avatar.
    |
    */
    'colour_range' => [
        'ff5622',
        '8057ff',
        '4d88ff',
        'ff4169',
        '673ab7',
        '03a9f4',
        '26c5da',
        '00ac7c',
        'c0ca33',
        'ffb201',
    ],

    /*
    |--------------------------------------------------------------------------
    | File Format
    |--------------------------------------------------------------------------
    |
    | Decide if the API should return SVG or PNG.
    |
    */
    'file_format' => 'png',

    /*
    |--------------------------------------------------------------------------
    | Circle Avatar
    |--------------------------------------------------------------------------
    |
    | Boolean specifying if the returned avatar should be a circle.
    |
    */
    'rounded' => false,

    'models' => [

        /*
         * When using the "HasNote" trait from this package, we need to know which
         * Eloquent model should be used to retrieve your notes. Of course, it
         * is often just the "Note" model but you may use whatever you like.
         *
         * The model you want to use as a "Note" model needs to extend the
         * `DanJamesMills\Notes\Models\Note` model.
         */

        'user' => \App\Models\User::class,

    ],
];
