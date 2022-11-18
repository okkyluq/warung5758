<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetTransaksiPembelian extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('det_transaksi_pembelian', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('transaksi_pembelian_id');
            $table->unsignedBigInteger('item_id');
            $table->decimal('qty', 12, 2);
            $table->unsignedBigInteger('satuan_item_id');
            $table->decimal('harga', 12, 0);
            $table->decimal('sub_total', 12, 0);
            $table->timestamps();

            $table->foreign('transaksi_pembelian_id')->on('transaksi_pembelian')->references('id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('item_id')->on('item')->references('id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('satuan_item_id')->on('satuan_item')->references('id')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('det_transaksi_pembelian');
    }
}
