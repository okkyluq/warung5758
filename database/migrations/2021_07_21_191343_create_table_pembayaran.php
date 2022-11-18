<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePembayaran extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode_pembayaran', 100);
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->date('tgl_pembayaran');
            $table->decimal('total_hutang', 12, 2);
            $table->decimal('total_pembayaran', 12, 2);
            $table->unsignedBigInteger('kas_id');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('supplier_id')->on('supplier')->references('id')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('pembayaran');
    }
}
