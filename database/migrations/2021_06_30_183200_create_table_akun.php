<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAkun extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('akun', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('kategori_akun_id');
            $table->string('kode_akun', 20);
            $table->string('nama_akun', 100);
            $table->enum('status_header', ['0', '1']); // ya dan tidak
            $table->enum('status_pembayaran', ['0', '1']); // ya dan tidak
            $table->enum('default_saldo', ['1', '2']); //debit dan kredit
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->timestamps();

            $table->foreign('kategori_akun_id')->on('kategori_akun')->references('id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('parent_id')->on('akun')->references('id')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('akun');
    }
}
