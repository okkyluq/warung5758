<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableHistoryKas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_kas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('kas_id');
            $table->unsignedBigInteger('historykasable_id');
            $table->string("historykasable_type");
            $table->decimal('nominal_debit', 12, 2);
            $table->decimal('nominal_kredit', 12, 2);
            $table->timestamps();

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
        Schema::dropIfExists('history_kas');
    }
}
