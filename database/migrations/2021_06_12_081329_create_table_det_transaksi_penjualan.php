<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDetTransaksiPenjualan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('det_transaksi_penjualan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('transaksi_penjualan_id');
            $table->unsignedBigInteger('item_id');
            $table->decimal('qty', 12, 2);
            $table->unsignedBigInteger('satuan_item_id');
            $table->decimal('harga', 12, 0);
            $table->decimal('sub_total', 12, 0);
            $table->timestamps();

            $table->foreign('transaksi_penjualan_id')->on('transaksi_penjualan')->references('id')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('det_transaksi_penjualan');
    }
}
