<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDetsetNilaiAkun extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('det_set_nilai_akun', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('set_nilai_akun_id');
            $table->unsignedBigInteger('akun_id');
            $table->decimal('nominal_debit', 12, 2);
            $table->decimal('nominal_kredit', 12, 2);
            $table->timestamps();

            $table->foreign('set_nilai_akun_id')->on('set_nilai_akun')->references('id')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('det_set_nilai_akun');
    }
}
