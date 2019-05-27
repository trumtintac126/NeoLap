<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_id', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('table_name_id');
            $table->dateTime('delete_at');
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
        Schema::table('table_id', function ($table) {
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
        Schema::dropIfExists('table_id');
    }
}
