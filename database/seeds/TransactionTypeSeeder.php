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
            'name' => 'Withdrawal',
            'slug' => 'Withdrawal',
        ]);

        DB::table('transaction_types')->insert([
            'name' => 'Deposit',
            'slug' => 'Deposit',
        ]);

        DB::table('transaction_types')->insert([
            'name' => 'Transfer',
            'slug' => 'Transfer',
        ]);
    }
}
