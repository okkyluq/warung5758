<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDetBillOfMaterial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('det_bom', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('bom_id');
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('satuan_item_id');
            $table->decimal('qty', 12, 2);
            $table->timestamps();

            $table->foreign('bom_id')->on('bom')->references('id')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('det_bom');
    }
}
