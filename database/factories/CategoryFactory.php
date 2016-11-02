<?php
/**
 * CategoryFactory.php, laravel-money-manager-ex
 *
 * This File belongs to to Project laravel-money-manager-ex
 * @author Oliver Kaufmann <okaufmann91@gmail.com>
 * @version 1.0
 * @package YOUREOACKAGE
 */

$factory->define(App\Models\Category::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->name,
    ];
});