<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDetReturPenjualan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('det_retur_penjualan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('retur_penjualan_id');
            $table->unsignedBigInteger('kas_id');
            $table->unsignedBigInteger('item_id');
            $table->decimal('qty', 8, 0);
            $table->unsignedBigInteger('satuan_item_id');
            $table->decimal('harga', 12, 0);
            $table->decimal('sub_total', 12, 0);
            $table->timestamps();

            $table->foreign('retur_penjualan_id')->on('retur_penjualan')->references('id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('kas_id')->on('kas')->references('id')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('det_retur_penjualan');
    }
}
