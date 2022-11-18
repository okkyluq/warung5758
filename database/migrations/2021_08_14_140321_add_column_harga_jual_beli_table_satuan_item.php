<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnHargaJualBeliTableSatuanItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('satuan_item', function (Blueprint $table) {
            $table->decimal('harga_jual', 12, 0)->nullable();
            $table->decimal('harga_beli', 12, 0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('satuan_item', function (Blueprint $table) {
            $table->dropColumn('harga_jual');
            $table->dropColumn('harga_beli');
        });
    }
}
