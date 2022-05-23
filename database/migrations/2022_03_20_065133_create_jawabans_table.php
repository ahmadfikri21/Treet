<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJawabansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jawaban', function (Blueprint $table) {
            $table->id('id_jawaban');
            $table->unsignedBigInteger('kode_pengujian');
            $table->unsignedBigInteger('id_task');
            $table->unsignedBigInteger('id_partisipan');
            $table->unsignedBigInteger('jawaban')->nullable();
            $table->string('path', 255)->nullable();
            $table->integer('waktu')->nullable();

            // Indexes
            $table->foreign('kode_pengujian')->references('kode_pengujian')->on('tester')->onDelete('cascade');
            $table->foreign('id_task')->references('id_task')->on('task')->onDelete('cascade');
            $table->foreign('id_partisipan')->references('id_partisipan')->on('partisipan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jawaban');
    }
}
