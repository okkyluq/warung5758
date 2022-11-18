<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransaksiPembelian extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_pembelian', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('no_pembelian', 100);
            $table->unsignedBigInteger('supplier_id');
            $table->date('tgl_pembelian');
            $table->unsignedBigInteger('user_id');

            // $table->enum('termin', ['1', '2']);
            // $table->unsignedBigInteger('kas_id');
            // $table->decimal('total', 12, 2);
            // $table->integer('jumlah_hari_tempo')->nullable();
            // $table->date('tgl_jatuh_tempo')->nullable();
            // $table->decimal('uang_muka', 12, 2)->nullable();
            // $table->text('keterangan')->nullable();
            
            
            $table->timestamps();

            $table->foreign('supplier_id')->on('supplier')->references('id')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('transaksi_pembelian');
    }
}
