<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDataCriteriasAddForeign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_criterias', function (Blueprint $table) {
            $table->foreign('employe_id')->references('id')->on('employes');
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
        Schema::table('data_criterias', function (Blueprint $table) {
            //
        });
    }
}
