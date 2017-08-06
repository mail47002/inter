<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'facebook' => [
        'client_id' => '291074594418092',
        'client_secret' => 'e9fc1d6ca69407ede526905605f0e875',
        'redirect' => 'http://inter.local/registruotis/facebook/callback', // change on production
    ],

    'google' => [
        'client_id'     => '724297532619-fjjsr38iqcf55r5ohir0eqilfbpj4236.apps.googleusercontent.com',
        'client_secret' => 'Z3VhGOiKY4PXuGigj6ATM0XO',
        'redirect' => 'http://inter.local/registruotis/google/callback', // change on production
    ],

];
