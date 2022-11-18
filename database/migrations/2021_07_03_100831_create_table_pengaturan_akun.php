<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePengaturanAkun extends Migration
{
    /**
     * Run the migrations. 
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengaturan_akun', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('setting', 100);
            $table->unsignedBigInteger('akun_id');
            $table->string('value', 100)->nullable();
            $table->string('kode', 100)->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('pengaturan_akun');
    }
}
