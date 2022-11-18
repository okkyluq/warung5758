<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDetSetNilaiItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('det_set_nilai_item', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('set_nilai_item_id');
            $table->unsignedBigInteger('item_id');
            $table->decimal('qty', 12, 2);
            $table->unsignedBigInteger('satuan_item_id');
            $table->decimal('hpp', 12, 0);
            $table->decimal('sub_total', 12, 0);
            $table->timestamps();

            $table->foreign('set_nilai_item_id')->on('set_nilai_item')->references('id')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('det_set_nilai_item');
    }
}
