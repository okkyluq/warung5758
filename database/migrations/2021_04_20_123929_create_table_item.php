<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode_item', 30);
            $table->string('barcode', 30)->nullable();
            $table->string('nama_item', 100);
            $table->enum('tipe_item', ['0', '1', '2']);
            $table->enum('opsi_jual', ['0', '1']);

            $table->unsignedBigInteger('satuan_penjualan');
            $table->unsignedBigInteger('satuan_pembelian');
            $table->unsignedBigInteger('satuan_stock');
            
            $table->text('gambar_item')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('satuan_penjualan')->on('satuan')->references('id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('satuan_pembelian')->on('satuan')->references('id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('satuan_stock')->on('satuan')->references('id')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('item');
    }
}
