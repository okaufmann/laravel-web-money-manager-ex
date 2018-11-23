<?php

$factory->define(App\Models\TransactionStatus::class, function (Faker\Generator $faker) {

    /*
     * $StatusArrayDesc = array ("None", "Reconciled", "Void", "Follow Up", "Duplicate");
     * $StatusArrayDB = array ("", "R", "V", "F", "D");
     */
    $name = implode('_', $faker->words(2));

    return [
        'name' => $name,
        'slug' => str_slug($name),
    ];
});
