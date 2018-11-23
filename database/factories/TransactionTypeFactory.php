<?php

$factory->define(App\Models\TransactionType::class, function (Faker\Generator $faker) {
    // $TypeArrayDesc = array ("Withdrawal", "Deposit", "Transfer");
    $name = implode('_', $faker->words(2));

    return [
        'name' => $name,
        'slug' => str_slug($name),
    ];
});
