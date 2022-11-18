<?php

namespace App\Services\API;
use App\HistoryItem;
use App\HistoryKas;
use App\HistoryPiutang;
use App\HistoryJurnal;
use App\PengaturanAkun;
use App\DetHistoryJurnal;
use App\PenerimaanPembayaran;
use App\DetPenerimaanPembayaran;

class TransaksiPenjualanService
{

    public function insertHistoryItem($request, $transaksi)
    {
        $det_history = collect($request['list_item'])->transform(function($value, $index){
            $new =  [
                'item_id'        => $value['item_id'],
                'satuan_item_id' => $value['satuan'],
                'qty'            => -$value['qty'],
                'status_in_out'  => '0',
                'harga'          => $value['harga']
            ];
            return new HistoryItem($new);
        });

        return $transaksi->history_item()->saveMany($det_history);

    }

    public function insertHistoryHutang($request, $transaksi)
    {
        // cek jika credit
        if($request['termin'] == '2') {
            $sisa_pembayaran = $request['total'] - $request['uang_muka'];
            $history_piutang = new HistoryPiutang([
                'costumer_id' => $request['costumer'],
                'nominal' => $request['total'],
                'terbayar' => $request['uang_muka'],
                'sisa_pembayaran' => $sisa_pembayaran,
                'status_lunas' => "0",
                'tgl_jatuh_tempo' => $request['tgl_jatuh_tempo'],
            ]);
            $transaksi->history_piutang()->save($history_piutang);
        }
    }

    public function insertHistoryKas($request, $transaksi)
    {
        $history_kas = new HistoryKas([
            'kas_id' => $request['kas'],
            'nominal_debit' => $request['termin'] == '1' ?  $request['total'] : $request['uang_muka'],
            'nominal_kredit' => 0
        ]);
        $transaksi->history_kas()->save($history_kas);
    }

    public function insertJurnalTransaksiPenjualanCash($request, $transaksi)
    {
        $history_jurnal_1 = new HistoryJurnal([
            'kode_journal' => app('App\Http\Controllers\HistoryJurnalController')->kode_journal(),
            'tgl_set' => $request['tgl_penjualan'],
            'autogen' => '1',
            'total_debit' => $request['total'],
            'total_kredit' => $request['total'],
            'keterangan' => 'Penjualan '.$transaksi->no_penjualan,
        ]);
        $transaksi->history_jurnal()->save($history_jurnal_1);
        $history_jurnal_1->det_history_jurnal()->saveMany([
            new DetHistoryJurnal([
                'akun_id' => PengaturanAkun::select(['akun_id'])->where('setting', 'kas')->first()->akun_id,
                'nominal_debit' => $request['total'],
                'nominal_kredit' => 0,
                'keterangan' => '',
            ]),
            new DetHistoryJurnal([
                'akun_id' => PengaturanAkun::select(['akun_id'])->where('setting', 'penjualan')->first()->akun_id,
                'nominal_debit' => 0,
                'nominal_kredit' => $request['total'],
                'keterangan' => '',
            ])
        ]);
    }

    public function insertJurnalTransaksiPenjualanCredit($request, $transaksi)
    {
        $history_jurnal_1 = new HistoryJurnal([
            'kode_journal' => app('App\Http\Controllers\HistoryJurnalController')->kode_journal(),
            'tgl_set' => $request['tgl_penjualan'],
            'autogen' => '1',
            'total_debit' => $request['total'],
            'total_kredit' => $request['total'],
            'keterangan' => 'Penjualan '.$transaksi->no_penjualan,
        ]);
        $transaksi->history_jurnal()->save($history_jurnal_1);


        $history_jurnal_1->det_history_jurnal()->saveMany([
            new DetHistoryJurnal([
                'akun_id' => PengaturanAkun::select(['akun_id'])->where('setting', 'piutang')->first()->akun_id,
                'nominal_debit' => $request['total'],
                'nominal_kredit' => 0,
                'keterangan' => '',
            ]),
            new DetHistoryJurnal([
                'akun_id' => PengaturanAkun::select(['akun_id'])->where('setting', 'penjualan')->first()->akun_id,
                'nominal_debit' => 0,
                'nominal_kredit' => $request['total'],
                'keterangan' => '',
            ])
        ]);
    }

    public function insertJurnalHppTransaksiPenjualan($request, $transaksi)
    {
        $det_hpp = collect($request['list_item'])->transform(function($value, $index){
            $new['hpp']               = $value['qty'] * str_replace(',', '', app('App\Http\Controllers\ItemController')->gethpp($value['item_id']));
            $new['item_id']           = $value['item_id'];
            $new['qty']               = $value['qty'];
            $new['satuan_item_id']    = $value['satuan'];
            $new['harga']             = $value['harga'];
            $new['sub_total']         = $value['qty'] * $value['harga'];
            return $new;
        });

        $history_jurnal_2 = new HistoryJurnal([
            'kode_journal' => app('App\Http\Controllers\HistoryJurnalController')->kode_journal(),
            'tgl_set' => $request['tgl_penjualan'],
            'autogen' => '1',
            'total_debit' => str_replace(',', '', $det_hpp->sum('hpp')),
            'total_kredit' => str_replace(',', '', $det_hpp->sum('hpp')),
            'keterangan' => 'HPP '.$transaksi->no_penjualan,
        ]);
        $transaksi->history_jurnal()->save($history_jurnal_2);

        $history_jurnal_2->det_history_jurnal()->saveMany([
            new DetHistoryJurnal([
                'akun_id' => PengaturanAkun::select(['akun_id'])->where('setting', 'hpp')->first()->akun_id,
                'nominal_debit' => str_replace(',', '', $det_hpp->sum('hpp')), //harga pokok penjualan produk
                'nominal_kredit' => 0,
                'keterangan' => '',
            ]),
            new DetHistoryJurnal([
                'akun_id' => PengaturanAkun::select(['akun_id'])->where('setting', 'inventory')->first()->akun_id,
                'nominal_debit' => 0,
                'nominal_kredit' => str_replace(',', '', $det_hpp->sum('hpp')), //harga pokok penjualan produk
                'keterangan' => '',
            ])
        ]);
    }

    public function autoInsertPenerimaanPembayaran($request, $transaksi)
    {
        $penerimaan = PenerimaanPembayaran::create([
            'kode_penerimaan_pembayaran' => app('App\Http\Controllers\PenerimaanPembayaranController')->no_pembayaran(),
            'costumer_id' => $request['costumer'],
            'tgl_penerimaan_pembayaran' =>  $request['tgl_penjualan'],
            'total_piutang' => $request['total'],
            'total_penerimaan_pembayaran' => $request['uang_muka'],
            'kas_id' => $request['kas'],
        ]);
        $penerimaan->det_penerimaan_pembayaran()->save(
            new DetPenerimaanPembayaran([
                'penerimaan_pembayaran_type' => 'App\TransaksiPenjualan',
                'no_ref' => $transaksi->no_penjualan,
                'jumlah_bayar' => $request['uang_muka'],
                'keterangan' => 'Pembayaran Uang Muka Penjualan '.$transaksi->no_penjualan,
            ])
        );
    }

    public function autoInsertJurnalPenerimaanPembayaran($request, $transaksi)
    {
        // pembayaran uang muka
        $history_jurnal_3 = new HistoryJurnal([
            'kode_journal' => app('App\Http\Controllers\HistoryJurnalController')->kode_journal(),
            'tgl_set' => $request['tgl_penjualan'],
            'autogen' => '1',
            'total_debit' => $request['uang_muka'],
            'total_kredit' => $request['uang_muka'],
            'keterangan' => 'Pembayaran Uang Muka Dari Transaksi Penjualan '.$transaksi->no_penjualan,
        ]);
        $transaksi->history_jurnal()->save($history_jurnal_3);
        $history_jurnal_3->det_history_jurnal()->saveMany([
            new DetHistoryJurnal([
                'akun_id' => PengaturanAkun::select(['akun_id'])->where('setting', 'kas')->first()->akun_id,
                'nominal_debit' => $request['uang_muka'],
                'nominal_kredit' => 0,
                'keterangan' => 'Cara Bayar : Pembelian Transaksi '.$transaksi->no_penjualan,
            ]),
            new DetHistoryJurnal([
                'akun_id' => PengaturanAkun::select(['akun_id'])->where('setting', 'deposit_costumer')->first()->akun_id,
                'nominal_debit' => 0,
                'nominal_kredit' => str_replace(',', '', $request['uang_muka']),
                'keterangan' => 'Untuk Bayar : Uang Muka Transaksi '.$transaksi->no_penjualan,
            ]),
        ]);

        // deposit untuk piutang costumer
        $history_jurnal_4 = new HistoryJurnal([
            'kode_journal' => app('App\Http\Controllers\HistoryJurnalController')->kode_journal(),
            'tgl_set' => $request['tgl_penjualan'],
            'autogen' => '1',
            'total_debit' => $request['uang_muka'],
            'total_kredit' => $request['uang_muka'],
            'keterangan' => 'Penerimaan Pembayaran '.$transaksi->no_penjualan,
        ]);
        $transaksi->history_jurnal()->save($history_jurnal_4);
        $history_jurnal_4->det_history_jurnal()->saveMany([
            new DetHistoryJurnal([
                'akun_id' => PengaturanAkun::select(['akun_id'])->where('setting', 'deposit_costumer')->first()->akun_id,
                'nominal_debit' => $request['uang_muka'],
                'nominal_kredit' => 0,
                'keterangan' => 'Untuk Bayar : Uang Muka Transaksi Penjualan '.$transaksi->no_penjualan,
            ]),
            new DetHistoryJurnal([
                'akun_id' => PengaturanAkun::select(['akun_id'])->where('setting', 'piutang')->first()->akun_id,
                'nominal_debit' => 0,
                'nominal_kredit' => $request['uang_muka'],
                'keterangan' => 'Untuk Bayar : Uang Muka Transaksi Penjualan '.$transaksi->no_penjualan,
            ]),
        ]);
    }





}
