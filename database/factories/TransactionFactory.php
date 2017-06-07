<?php
/**
 * TransactionFactory.php, laravel-money-manager-ex.
 *
 * This File belongs to to Project laravel-money-manager-ex
 *
 * @author Oliver Kaufmann <okaufmann91@gmail.com>
 *
 * @version 1.0
 */
$factory->define(App\Models\Transaction::class, function (Faker\Generator $faker) {
    return [
        'status_id' => function () {
            return factory(App\Models\TransactionStatus::class)->create()->id;
        },
        'type_id' => function () {
            return factory(App\Models\TransactionType::class)->create()->id;
        },
        'account_name'      => $faker->word,
        'to_account_name'   => null,
        'payee_name'        => $faker->name,
        'category_name'     => $faker->words(2, true),
        'sub_category_name' => $faker->words(2, true),
        'amount'            => $faker->randomFloat(2,-1000,1000),
        'notes'             => $faker->text,
    ];
});
