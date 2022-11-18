<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDetStockOpname extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('det_stock_opname', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('stock_opname_id');
            $table->unsignedBigInteger('item_id');
            $table->decimal('qty_opname', 8, 0);
            $table->decimal('qty_program', 8, 0);
            $table->decimal('qty_selisih', 8, 0);
            $table->unsignedBigInteger('satuan_item_id');
            $table->timestamps();

            $table->foreign('stock_opname_id')->on('stock_opname')->references('id')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('det_stock_opname');
    }
}
