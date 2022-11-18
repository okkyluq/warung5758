<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('login', 'API\UserLoginController@login');

Route::group(['middleware' => 'jwt.verify'], function (){
	Route::get('user', 'API\UserLoginController@get_user_info');

    Route::get('data-master/getlistitem', 'API\ItemController@get_list_item');
    Route::get('data-master/getitem', 'API\ItemController@get_item');
	Route::get('data-master/getkodeitem', 'API\ItemController@kode_item');
	Route::get('data-master/getsatuan', 'API\SatuanController@get_satuan');
	Route::get('data-master/get-akun', 'API\AkunController@get_akun');
    Route::get('data-master/getsupplier', 'API\SupplierController@get_supplier');
    Route::get('data-master/getcostumer', 'API\CostumerController@get_costumer');
    Route::get('data-master/getkas', 'API\KasController@get_kas');
	Route::get('get-pengaturan-akun', 'API\PengaturanSistemController@get_pengaturan_akun');


	Route::resource('data-master/item', 'API\ItemController');



    Route::get('transaksi-pembelian/getkodetransaksi', 'API\TransaksiPembelianController@get_kode_transaksi');
    Route::resource('transaksi-pembelian', 'API\TransaksiPembelianController');


    Route::get('transaksi-penjualan/getkodetransaksi', 'API\TransaksiPenjualanController@get_kode_transaksi');
    Route::resource('transaksi-penjualan', 'API\TransaksiPenjualanController');

	Route::post('logout', 'API\UserLoginController@logout');
});
