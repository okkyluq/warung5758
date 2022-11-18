<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableHistoryAkun extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_akun', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('akun_id');
            $table->unsignedBigInteger('historyakunable_id');
            $table->string("historyakunable_type");
            $table->decimal('nominal_debit', 12, 2);
            $table->decimal('nominal_kredit', 12, 2);
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
        Schema::dropIfExists('history_akun');
    }
}
