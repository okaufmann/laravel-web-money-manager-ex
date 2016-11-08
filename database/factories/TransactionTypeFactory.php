<?php
/**
 * CategoryFactory.php, laravel-money-manager-ex
 *
 * This File belongs to to Project laravel-money-manager-ex
 * @author Oliver Kaufmann <okaufmann91@gmail.com>
 * @version 1.0
 * @package YOUREOACKAGE
 */

$factory->define(App\Models\TransactionType::class, function (Faker\Generator $faker) {
    // $TypeArrayDesc = array ("Withdrawal", "Deposit", "Transfer");
    return [
        'name' => $faker->word,
        'slug' => str_slug($faker->word),
    ];
});
