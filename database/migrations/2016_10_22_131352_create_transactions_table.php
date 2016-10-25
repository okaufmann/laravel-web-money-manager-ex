<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id')->unsigned();
            $table->integer('to_account_id')->unsigned();
            $table->integer('status_id')->unsigned();
            $table->integer('type_id')->unsigned();
            $table->integer('payee_id')->unsigned();
            $table->integer('category_id')->unsigned();
            $table->decimal('amount');
            $table->text('notes');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->foreign('account_id')->references('id')->on('accounts');
            $table->foreign('to_account_id')->references('id')->on('accounts');
            $table->foreign('status_id')->references('id')->on('transaction_status');
            $table->foreign('type_id')->references('id')->on('transaction_types');
            $table->foreign('payee_id')->references('id')->on('payees');
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
