<?php

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name'      => $faker->name,
        'email'     => $faker->email,
        'password'  => $password ?: $password = bcrypt('secret'),
        'is_admin'  => true,
        'mmex_guid' => mmex_guid(),
        'locale'    => 'en_US',
        'api_token' => str_random(60),
    ];
});
