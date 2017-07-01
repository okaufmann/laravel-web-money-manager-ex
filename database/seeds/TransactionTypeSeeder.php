<?php

use Illuminate\Database\Seeder;

class TransactionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('transaction_types')->insert([
            'name' => trans('Withdrawal'),
            'slug' => trans('Withdrawal'),
        ]);

        DB::table('transaction_types')->insert([
            'name' => trans('Deposit'),
            'slug' => trans('Deposit'),
        ]);

        DB::table('transaction_types')->insert([
            'name' => trans('Transfer'),
            'slug' => trans('Transfer'),
        ]);
    }
}
