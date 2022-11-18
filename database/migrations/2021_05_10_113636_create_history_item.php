<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoryItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_item', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('satuan_item_id');
            $table->decimal('qty', 12, 2);
            $table->unsignedBigInteger('historyable_id');
            $table->string("historyable_type");
            $table->enum('status_in_out', ['0', '1']);
            $table->timestamps();

            $table->foreign('item_id')->on('item')->references('id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('satuan_item_id')->on('satuan_item')->references('id')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */ 
    public function down()
    {
        Schema::dropIfExists('history_item');
    }
}
