<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSitusLpseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('situs_lpse', function (Blueprint $table) {
            $table->increments('id_situs');
            $table->string('nama_situs');
            $table->string('link');
            $table->string('kategori')->default('instansi');
            $table->enum('status',['on','off'])->default('off');
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('situs_lpse');
    }
}
