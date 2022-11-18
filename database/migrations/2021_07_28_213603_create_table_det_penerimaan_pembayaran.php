<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDetPenerimaanPembayaran extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('det_penerimaan_pembayaran', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('penerimaan_pembayaran_id');
            $table->unsignedBigInteger('history_piutang_id')->nullable();
            $table->unsignedBigInteger('akun_id')->nullable();
            $table->string("penerimaan_pembayaran_type"); 
            $table->string('no_ref', 100);
            $table->decimal('jumlah_bayar', 12, 2);
            $table->text('keterangan');
            $table->timestamps();

            $table->foreign('penerimaan_pembayaran_id')->on('penerimaan_pembayaran')->references('id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('history_piutang_id')->on('history_piutang')->references('id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('akun_id')->on('akun')->references('id')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('det_penerimaan_pembayaran');
    }
}
