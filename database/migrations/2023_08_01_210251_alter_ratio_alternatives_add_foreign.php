<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRatioAlternativesAddForeign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ratio_alternatives', function (Blueprint $table) {
            $table->foreign('h_alternative_id')->references('id')->on('alternatives');
            $table->foreign('v_alternative_id')->references('id')->on('alternatives');
            $table->foreign('criteria_id')->references('id')->on('criterias');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ratio_alternatives', function (Blueprint $table) {
            //
        });
    }
}
