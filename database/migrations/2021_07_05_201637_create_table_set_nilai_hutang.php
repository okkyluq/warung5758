<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSetNilaiHutang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('set_nilai_hutang', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode_transaksi', 50);
            $table->date('tgl_set');
            $table->unsignedBigInteger('supplier_id');
            $table->integer('jatuh_tempo');
            $table->decimal('total', 12, 2);
            $table->unsignedBigInteger('akun_id');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('supplier_id')->on('supplier')->references('id')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('set_nilai_hutang');
    }
}
