<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableItemAkutansi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */ 
    public function up()
    {
        Schema::create('item_akutansi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('akun_pembelian');
            $table->unsignedBigInteger('akun_hpp');
            $table->unsignedBigInteger('akun_penjualan');
            $table->unsignedBigInteger('akun_retur_penjualan');
            $table->timestamps();

            $table->foreign('item_id')->on('item')->references('id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('akun_pembelian')->on('akun')->references('id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('akun_hpp')->on('akun')->references('id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('akun_penjualan')->on('akun')->references('id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('akun_retur_penjualan')->on('akun')->references('id')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_akutansi');
    }
}
