<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRatioCriteriasAddForeign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ratio_criterias', function (Blueprint $table) {
            $table->foreign('h_criteria_id')->references('id')->on('criterias');
            $table->foreign('v_criteria_id')->references('id')->on('criterias');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ratio_criterias', function (Blueprint $table) {
            //
        });
    }
}
