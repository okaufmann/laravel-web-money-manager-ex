<?php

use Illuminate\Database\Seeder;

class TransactionStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('transaction_status')->insert([
            'name' => trans('Reconciled'),
            'slug' => 'R',
        ]);

        DB::table('transaction_status')->insert([
            'name' => trans('Void'),
            'slug' => 'V',
        ]);

        DB::table('transaction_status')->insert([
            'name' => trans('Follow Up'),
            'slug' => 'F',
        ]);

        DB::table('transaction_status')->insert([
            'name' => trans('Duplicate'),
            'slug' => 'D',
        ]);
    }
}
