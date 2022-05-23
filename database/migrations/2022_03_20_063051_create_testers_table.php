<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tester', function (Blueprint $table) {
            $table->unsignedBigInteger("id_user");
            $table->bigIncrements("kode_pengujian");
            $table->string("kode_string",255);
            $table->string("nama_website",255)->nullable();
            $table->text("scope_pengujian")->nullable();
            $table->string("profil_partisipan",255)->nullable();
            $table->integer("minimal_partisipan")->nullable();
            $table->date("mulai_pengujian")->nullable();
            $table->date("akhir_pengujian")->nullable();
            $table->integer("status_pengujian")->default(0);

            // Indexes
            $table->unique("kode_pengujian");
            $table->dropPrimary("partisipan_kode_pengujian_primary");
            $table->primary("id_user");
            $table->foreign('id_user')->references('id_user')->on('user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tester');
    }
}
