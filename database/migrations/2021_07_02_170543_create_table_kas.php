<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableKas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode_kas', 100);
            $table->string('nama_kas', 100);
            $table->enum('type_kas', ['1', '2']); // bank, tunai
            $table->unsignedBigInteger('akun_id');
            $table->timestamps();

            $table->foreign('akun_id')->on('akun')
                    ->references('id')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kas');
    }
}
