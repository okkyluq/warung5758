<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePenerimaanPembayaran extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penerimaan_pembayaran', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode_penerimaan_pembayaran', 100);
            $table->unsignedBigInteger('costumer_id')->nullable();
            $table->date('tgl_penerimaan_pembayaran');
            $table->decimal('total_piutang', 12, 2);
            $table->decimal('total_penerimaan_pembayaran', 12, 2);
            $table->unsignedBigInteger('kas_id');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('costumer_id')->on('costumer')->references('id')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('penerimaan_pembayaran');
    }
}
