<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMutasiKas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mutasi_kas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode_transaksi', 50);
            $table->date('tgl_transaksi');
            $table->unsignedBigInteger('kas_utama');
            $table->unsignedBigInteger('kas_tujuan');
            $table->decimal('nominal_utama', 12, 0);
            $table->decimal('nominal_tujuan', 12, 0);
            $table->timestamps();

            $table->foreign('kas_utama')->on('kas')->references('id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('kas_tujuan')->on('kas')->references('id')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mutasi_kas');
    }
}
