<?php

$factory->define(App\Models\Account::class, function (Faker\Generator $faker) {
    return [
        'name'    => $faker->name,
        'user_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
    ];
});
