<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRowNamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('row_names', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('row_name');
            $table->string('type');
            $table->unsignedBigInteger('table_name_id');
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
        Schema::table('row_names', function ($table) {
            $table->foreign('table_name_id')->references('id')->on('table_names');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('row_names');
    }
}
