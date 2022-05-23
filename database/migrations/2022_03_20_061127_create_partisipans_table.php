<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartisipansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partisipan', function (Blueprint $table) {
            $table->unsignedBigInteger("id_user");
            $table->bigIncrements("id_partisipan");

            // Indexes
            $table->unique("id_partisipan");
            $table->dropPrimary("partisipan_id_partisipan_primary");
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
        Schema::dropIfExists('partisipan');
    }
}
