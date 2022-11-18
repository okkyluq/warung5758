<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDetHistoryJurnal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('det_history_jurnal', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('history_journal_id');
            $table->unsignedBigInteger('akun_id');
            $table->decimal('nominal_debit', 12, 2);
            $table->decimal('nominal_kredit', 12, 2);
            $table->text('keterangan');
            $table->timestamps();

            $table->foreign('history_journal_id')->on('history_jurnal')->references('id')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('det_history_jurnal');
    }
}
