<?php
/**
 * CategoryFactory.php, laravel-money-manager-ex
 *
 * This File belongs to to Project laravel-money-manager-ex
 * @author Oliver Kaufmann <okaufmann91@gmail.com>
 * @version 1.0
 * @package YOUREOACKAGE
 */

$factory->define(App\Models\TransactionStatus::class, function (Faker\Generator $faker) {

    /**
     * $StatusArrayDesc = array ("None", "Reconciled", "Void", "Follow Up", "Duplicate");
     * $StatusArrayDB = array ("", "R", "V", "F", "D");
     */
    return [
        'name' => $faker->word,
        'slug' => str_slug($faker->word),
    ];
});