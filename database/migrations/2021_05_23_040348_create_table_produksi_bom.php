<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableProduksiBom extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produksi_bom', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('no_produksi', 100);
            $table->date('tgl_produksi');
            $table->unsignedBigInteger('bom_id');
            $table->decimal('qty', 12, 2);
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('bom_id')->on('bom')->references('id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->on('users')->references('id')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produksi_bom');
    }
}
