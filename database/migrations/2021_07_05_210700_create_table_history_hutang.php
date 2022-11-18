<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableHistoryHutang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_hutang', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('historyhutangable_id');
            $table->string("historyhutangable_type");
            $table->decimal('nominal', 12, 2);
            $table->decimal('terbayar', 12, 2);
            $table->decimal('sisa_pembayaran', 12, 2);
            $table->enum('status_lunas', ['0', '1']);
            $table->date('tgl_jatuh_tempo');
            $table->timestamps();

            $table->foreign('supplier_id')->on('supplier')->references('id')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('history_hutang');
    }
}
