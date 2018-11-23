<?php
/**
 * CategoryFactory.php, laravel-money-manager-ex.
 *
 * This File belongs to to Project laravel-money-manager-ex
 *
 * @author Oliver Kaufmann <okaufmann91@gmail.com>
 *
 * @version 1.0
 */
$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    $guid = mmex_guid();
    \Log::debug(sprintf('creating new user with uuid: `%s`', $guid));


    return [
        'name'      => $faker->name,
        'email'     => $faker->email,
        'password'  => bcrypt('secret'),
        'is_admin'  => true,
        'mmex_guid' => $guid,
        'locale'    => 'en_US',
        'api_token' => str_random(60),
    ];
});
