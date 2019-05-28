<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRowValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('row_values', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('row_id');
            $table->text('value');
            $table->string('hash');
            $table->timestamps();
        });
        Schema::table('row_values', function ($table) {
            $table->foreign('row_id')->references('id')->on('row_names');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('row_values');
    }
}
