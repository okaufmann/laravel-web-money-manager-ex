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
$factory->define(App\Models\TransactionType::class, function (Faker\Generator $faker) {
    // $TypeArrayDesc = array ("Withdrawal", "Deposit", "Transfer");
    $name = implode('_', $faker->words(2));

    return [
        'name' => $name,
        'slug' => str_slug($name),
    ];
});
