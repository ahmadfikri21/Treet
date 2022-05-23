<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTreesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tree', function (Blueprint $table) {
            $table->id('id_node');
            $table->unsignedBigInteger('kode_pengujian');
            $table->string('nama_node',255);
            $table->unsignedBigInteger('parent_node')->nullable();
            $table->integer('urutan');

            // indexes
            $table->foreign('kode_pengujian')->references('kode_pengujian')->on('tester')->onDelete('cascade');
            $table->foreign('parent_node')->references('id_node')->on('tree')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tree');
    }
}
