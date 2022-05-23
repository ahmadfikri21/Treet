<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task', function (Blueprint $table) {
            $table->id('id_task');
            $table->unsignedBigInteger('kode_pengujian');
            $table->text('deskripsi');
            $table->string('kriteria_task', 255);
            $table->unsignedBigInteger('id_node')->nullable();
            $table->text('direct_path')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();

            // Indexes
            $table->foreign('kode_pengujian')->references('kode_pengujian')->on('tester')->onDelete('cascade');
            $table->foreign('id_node')->references('id_node')->on('tree')->nullOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task');
    }
}
