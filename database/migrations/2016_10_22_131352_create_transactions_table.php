<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->integer('status_id')->unsigned();
            $table->integer('type_id')->unsigned();
            $table->string('account_name'); // should not be linked through a FK since a account can be deleted anytime
            $table->string('to_account_name'); // "
            $table->string('payee_name'); // "
            $table->string('category_name'); // "
            $table->decimal('amount');
            $table->text('notes');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->foreign('status_id')->references('id')->on('transaction_status');
            $table->foreign('type_id')->references('id')->on('transaction_types');
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
