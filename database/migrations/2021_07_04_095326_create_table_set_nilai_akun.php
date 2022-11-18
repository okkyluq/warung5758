<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSetNilaiAkun extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('set_nilai_akun', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode_transaksi', 100);
            $table->date('tgl_set');
            $table->unsignedBigInteger('user_id');
            $table->timestamps(); 

            $table->foreign('user_id')->on('users')->references('id')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('set_nilai_akun');
    }
}
