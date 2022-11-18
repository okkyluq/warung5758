<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableReturPenjualan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retur_penjualan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode_transaksi', 50);
            $table->date('tgl_transaksi');
            $table->unsignedBigInteger('costumer_id');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('costumer_id')->on('costumer')->references('id')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('retur_penjualan');
    }
}
