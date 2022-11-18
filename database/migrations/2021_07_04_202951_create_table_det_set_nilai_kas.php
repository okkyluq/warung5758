<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDetSetNilaiKas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('det_set_nilai_kas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('set_nilai_kas_id');
            $table->unsignedBigInteger('kas_id');
            $table->decimal('nominal_debit', 12, 2);
            $table->decimal('nominal_kredit', 12, 2);
            $table->timestamps();

            $table->foreign('set_nilai_kas_id')->on('set_nilai_kas')->references('id')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('det_set_nilai_kas');
    }
}
