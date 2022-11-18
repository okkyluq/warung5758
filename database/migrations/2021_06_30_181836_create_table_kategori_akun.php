<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateTableKategoriAkun extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kategori_akun', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('no_kategori');
            $table->string('nama_kategori', 50);
            $table->timestamps();
        });

        DB::table('kategori_akun')->insert([
            [ 'no_kategori' => 1, 'nama_kategori' => 'Harta' ],
            [ 'no_kategori' => 2, 'nama_kategori' => 'Kewajiban' ],
            [ 'no_kategori' => 3, 'nama_kategori' => 'Modal' ],
            [ 'no_kategori' => 4, 'nama_kategori' => 'Pendapatan' ],
            [ 'no_kategori' => 5, 'nama_kategori' => 'Beban' ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kategori_akun');
    }
}
