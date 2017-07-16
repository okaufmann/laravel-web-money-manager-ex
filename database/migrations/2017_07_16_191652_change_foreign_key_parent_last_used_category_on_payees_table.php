<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeForeignKeyParentLastUsedCategoryOnPayeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payees', function (Blueprint $table) {
            $table->dropForeign(['last_category_id']);
            $table->foreign('last_category_id')->references('id')->on('categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payees', function (Blueprint $table) {
            $table->dropForeign(['last_category_id']);
            $table->foreign('last_category_id')->references('id')->on('categories');
        });
    }
}
