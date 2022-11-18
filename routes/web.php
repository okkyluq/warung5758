<?php

Auth::routes();

Route::middleware('auth')->group(function ($route) {

    $route->get('/', 'BackOfficeController@dashboard');
    $route->match(['GET', 'POST'], 'ubah-password', 'BackOfficeController@view_ubah_password');
    $route->post('chart-omset-penjualan', 'BackofficeController@chartOmsetPenjualan');
    $route->post('chart-omset-bulan', 'BackofficeController@chartOmsetBulan');
    $route->post('chart-omset-tgl', 'BackofficeController@chartOmsetTgl');

    $route->resource("data-master/kategori", "KategoriController");
    $route->resource("data-master/satuan", "SatuanController");
    $route->resource("data-master/item", "ItemController");
    $route->get('get-stock-minimal', "ItemController@getStockMinimal");
    $route->post('data-master/item/import', "ItemController@import");
    $route->resource("data-master/supplier", "SupplierController");
    $route->resource("data-master/costumer", "CostumerController");
    $route->resource("data-master/akun", "AkunController");
    $route->resource("data-master/kas", "KasController");

    $route->resource("pengaturan/pengaturan-akun", "PengaturanAkunController");
    $route->resource("pengaturan/pengaturan-sistem", "PengaturanSistemController");
    $route->resource("pengaturan/manajemen-user", "ManajemenUserController");

    $route->resource("sistem/stock-opname", "StockOpnameController");
    $route->resource("sistem/penyesuaian-stock", "PenyesuaianStockController");
    $route->resource("sistem/set-nilai-akun", "SetNilaiAwalAkunController");
    $route->resource("sistem/set-nilai-kas", "SetNilaiKasController");
    $route->resource("sistem/set-nilai-item", "SetNilaiItemController");
    $route->resource("sistem/set-nilai-hutang", "SetNilaiAwalHutangController");
    $route->resource("sistem/set-nilai-piutang", "SetNilaiAwalPiutangController");
    $route->resource("sistem/retur/retur-pembelian", "ReturPembelianController");
    $route->get("sistem/retur/pencarian-pembelian", "ReturPembelianController@pencarian_pembelian");
    $route->resource("sistem/retur/retur-penjualan", "ReturPenjualanController");
    $route->get("sistem/retur/pencarian-penjualan", "ReturPenjualanController@pencarian_penjualan");

    $route->resource("keuangan/penerimaan-pembayaran", "PenerimaanPembayaranController");
    $route->get('keuangan/penerimaan-pembayaran/{id}/print', "PenerimaanPembayaranController@print");
    $route->resource("keuangan/pembayaran", "PembayaranController");
    $route->resource("keuangan/lihat-kas", "LihatKasController");
    $route->resource("keuangan/lihat-hutang", "LihatHutangController");
    $route->resource("keuangan/lihat-piutang", "LihatPiutangController");
    $route->resource("keuangan/mutasi-kas", "MutasiKasController");

    $route->get("keuangan/pencarian-pembelian", "PembayaranController@pencarian_hutang");
    $route->get("keuangan/pencarian-penjualan", "PenerimaanPembayaranController@pencarian_piutang");
    $route->get("keuangan/pencarian-akun", "PembayaranController@pencarian_akun");
    $route->get("keuangan/pencarian-retur", "PembayaranController@pencarian_retur");

    $route->resource('akutansi/jurnal', 'HistoryJurnalController');
    $route->get('akutansi/lihat-jurnal/{nojurnal}', 'HistoryJurnalController@jurnal_pdf');

    $route->get('getakunselect2', 'AkunController@getakun_select2');
    $route->get('getakunautocomplete', 'AkunController@getakun_autocomplete');
    $route->get('getkasselect2', 'KasController@getkas_select2');
    $route->get('getkasautocomplete', 'KasController@getkas_autocomplete');

    $route->resource("pembelian", 'TransaksiPembelianController');
    $route->get("pembelian/{id}/print", 'TransaksiPembelianController@cetak');

    $route->resource("penjualan", 'TransaksiPenjualanController');
    $route->get("get-id-penjualan", "TransaksiPenjualanController@no_penjualan");
    $route->get('get-item-penjualan', 'ItemController@get_item_penjualan');
    $route->get("penjualan/{id}/print", 'TransaksiPenjualanController@cetak');

    $route->get('getsupplier', "SupplierController@get_supplier");
    $route->get('getcostumer', "CostumerController@get_costumer");

    $route->get("getsatuan", "SatuanController@get_satuan");
    $route->get("getkategori", "KategoriController@get_kategori");
    $route->get("getitem", "ItemController@getitem");
    $route->get("getitemselect2", "ItemController@getitem_select2");
    $route->get("getbom", "BillOfMaterialController@getbom");

    $route->get("getitemmasukid", "ItemMasukController@getitemmasukid");
    $route->resource("kelola-stock/item-masuk", "ItemMasukController");
    $route->get("getitemkeluarid", "ItemKeluarController@getitemkeluarid");
    $route->resource("kelola-stock/item-keluar", "ItemKeluarController");

    $route->get("getkodemenu", "MenuController@getkodemenu");
    $route->resource('menu/kelola-menu', 'MenuController');

    $route->resource("produksi/bill-of-material", 'BillOfMaterialController');
    $route->get("produksi/bill-of-material/{id}/cetak", 'BillOfMaterialController@cetak');

    $route->resource("produksi/produksi-bom", 'ProduksiBillOfMaterialController');
    $route->get("produksi/produksi-bom/{id}/cetak", 'ProduksiBillOfMaterialController@cetak');
    $route->resource("produksi/laporan-produksi", 'LaporanBillOfMaterialController');

    $route->resource('laporan/stock-item', 'LaporanStockItemController');
    $route->match(['GET', 'POST'], 'laporan/saldo-stock', 'LaporanStockItemController@laporan_saldo_stock');
    $route->match(['GET', 'POST'], 'laporan/kartu-stock', 'LaporanStockItemController@laporan_kartu_stock');
    $route->match(['GET', 'POST'], 'laporan/pembelian', 'LaporanPembelianController@index');
    $route->match(['GET', 'POST'], 'laporan/penjualan', 'LaporanPenjualanController@index');
    $route->match(['GET', 'POST'], 'laporan/laba-rugi', 'LaporanLabaRugiController@index');
    $route->match(['GET', 'POST'], 'laporan/neraca-saldo', 'LaporanNeracaSaldoController@index');


    $route->get('kasir', 'Kasir\KasirPageController@index');
    $route->resource('kasir/penjualan', 'Kasir\PenjualanController');
    $route->resource('kasir/produksi', 'Kasir\ProduksiController');


});
