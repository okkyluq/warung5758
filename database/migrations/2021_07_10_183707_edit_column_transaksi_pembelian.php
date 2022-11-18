<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditColumnTransaksiPembelian extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaksi_pembelian', function (Blueprint $table) {
            $table->enum('termin', ['1', '2']);
            $table->unsignedBigInteger('kas_id');
            $table->decimal('total', 12, 2);
            $table->integer('jumlah_hari_tempo')->nullable();
            $table->date('tgl_jatuh_tempo')->nullable();
            $table->decimal('uang_muka', 12, 2)->nullable();
            $table->text('keterangan')->nullable();

            $table->foreign('kas_id')->on('kas')->references('id')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaksi_pembelian', function (Blueprint $table) {
            $table->dropColumn(['termin', 'kas_id', 'total', 'jumlah_hari_tempo', 'tgl_jatuh_tempo', 'uang_muka', 'keterangan']);
            $table->dropForeign(['kas_id']);
        });
    }
}
