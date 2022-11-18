<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDetPenyesuaianStock extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('det_penyesuaian_stock', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('penyesuaian_stock_id');
            $table->unsignedBigInteger('item_id');
            $table->decimal('qty', 8, 0);
            $table->unsignedBigInteger('satuan_item_id');
            $table->timestamps();

            $table->foreign('penyesuaian_stock_id')->on('penyesuaian_stock')->references('id')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('det_penyesuaian_stock');
    }
}
