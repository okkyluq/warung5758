<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditColumnSetNilaiKas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('det_set_nilai_kas');
        Schema::table('set_nilai_kas', function (Blueprint $table) {
            $table->unsignedBigInteger('kas_id');
            $table->decimal('nominal', 12, 2);
            $table->text('keterangan');

            $table->foreign('kas_id')->on('kas')->references('id')->onDlete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('set_nilai_kas', function (Blueprint $table) {
            $table->dropForeign(['kas_id']);

            $table->dropColumn('kas_id');
            $table->dropColumn('nominal');
            $table->dropColumn('keterangan');
        });
    }
}
