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
            $table->integer('user_id');
            $table->timestamp('transaction_date')->nullable();
            $table->integer('status_id')->unsigned()->nullable();
            $table->integer('type_id')->unsigned();
            $table->string('account_name'); // should not be linked through a FK since a account can be deleted anytime
            $table->string('to_account_name')->nullable();
            $table->string('payee_name');
            $table->string('category_name');
            $table->string('sub_category_name')->nullable();
            $table->decimal('amount');
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('transaction_status');
            $table->foreign('type_id')->references('id')->on('transaction_types');
            $table->foreign('account_id')->references('id')->on('transaction_types')->onDelete('set null');
            $table->foreign('to_account_id')->references('id')->on('transaction_types')->onDelete('set null');
            $table->foreign('payee_id')->references('id')->on('transaction_types')->onDelete('set null');
            $table->foreign('category_id')->references('id')->on('transaction_types')->onDelete('set null');
            $table->foreign('sub_category_id')->references('id')->on('transaction_types')->onDelete('set null');
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
