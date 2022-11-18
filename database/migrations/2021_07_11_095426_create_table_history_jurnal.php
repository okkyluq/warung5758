<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableHistoryJurnal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_jurnal', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode_journal', 50);
            $table->date('tgl_set');
            $table->enum('autogen', ['0', '1']);

            $table->unsignedBigInteger('historyjurnalable_id');
            $table->string("historyjurnalable_type");

            $table->decimal('total_debit', 12, 2);
            $table->decimal('total_kredit', 12, 2);
            $table->text('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('history_jurnal');
    }
}
