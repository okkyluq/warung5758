<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDetPembayaran extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('det_pembayaran', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pembayaran_id');
            $table->unsignedBigInteger('history_hutang_id')->nullable();
            $table->unsignedBigInteger('akun_id')->nullable();
            $table->string("pembayaran_type"); 
            $table->string('no_ref', 100);
            $table->decimal('jumlah_bayar', 12, 2);
            $table->text('keterangan');
            $table->timestamps();

            $table->foreign('pembayaran_id')->on('pembayaran')->references('id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('history_hutang_id')->on('history_hutang')->references('id')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('det_pembayaran');
    }
}
