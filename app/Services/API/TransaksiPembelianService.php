<?php

namespace App\Services\API;
use App\HistoryItem;
use App\HistoryKas;
use App\HistoryHutang;
use App\HistoryJurnal;
use App\PengaturanAkun;
use App\DetHistoryJurnal;
use App\Pembayaran;
use App\DetPembayaran;

class TransaksiPembelianService
{

    public function insertHistoryItem($request, $transaksi)
    {
        $list_item = collect($request['list_item'])->transform(function($value, $index){
            $new =  [
                'item_id'        => $value['item'],
                'satuan_item_id' => $value['satuan'],
                'qty'            => $value['qty'],
                'status_in_out'  => '1',
                'harga'          => $value['harga']
            ];
            return new HistoryItem($new);
        });

        return $transaksi->history_item()->saveMany($list_item);

    }

    public function insertHistoryHutang($request, $transaksi)
    {
        if($request['termin'] == 2) {
            return $transaksi->history_hutang()->save(
                new HistoryHutang([
                    'supplier_id' => $request['supplier'],
                    'nominal' => $request['total'],
                    'terbayar' => $request['uang_muka'],
                    'sisa_pembayaran' => $request['total']- $request['uang_muka'],
                    'status_lunas' => "0",
                    'tgl_jatuh_tempo' => $request['tgl_jatuh_tempo']
                ])
            );
        }
    }

    public function insertHistoryKas($request, $transaksi)
    {
        return $transaksi->history_kas()->save(
            new HistoryKas([
                'kas_id' => $request['kas'],
                'nominal_debit' => 0,
                'nominal_kredit' => $request['termin'] == 1 ?  $request['total'] : $request['uang_muka'],
            ])
        );
    }

    public function insertJurnalTransaksiPembelianCash($request, $transaksi)
    {
        $history_jurnal = new HistoryJurnal([
            'kode_journal' => app('App\Http\Controllers\HistoryJurnalController')->kode_journal(),
            'tgl_set' => $request['tgl_pembelian'],
            'autogen' => '1',
            'total_debit' => $request['total'],
            'total_kredit' => $request['total'],
            'keterangan' => 'Pembelian '.$transaksi->no_pembelian,
        ]);
        $transaksi->history_jurnal()->save($history_jurnal);
        return $history_jurnal->det_history_jurnal()->saveMany([
            new DetHistoryJurnal([
                'akun_id' => PengaturanAkun::select(['akun_id'])->where('setting', 'inventory')->first()->akun_id,
                'nominal_debit' => $request['total'],
                'nominal_kredit' => 0,
                'keterangan' => '',
            ]),
            new DetHistoryJurnal([
                'akun_id' => PengaturanAkun::select(['akun_id'])->where('setting', 'kas')->first()->akun_id,
                'nominal_debit' => 0,
                'nominal_kredit' => $request['total'],
                'keterangan' => '',
            ])
        ]);
    }

    public function insertJurnalTransaksiPembelianCredit($request, $transaksi)
    {
        $history_jurnal = new HistoryJurnal([
            'kode_journal' => app('App\Http\Controllers\HistoryJurnalController')->kode_journal(),
            'tgl_set' => $request['tgl_pembelian'],
            'autogen' => '1',
            'total_debit' => $request['total'],
            'total_kredit' => $request['total'],
            'keterangan' => 'Pembelian '.$transaksi->no_pembelian,
        ]);

        $transaksi->history_jurnal()->save($history_jurnal);
        $history_jurnal->det_history_jurnal()->saveMany([
            new DetHistoryJurnal([
                'akun_id' => PengaturanAkun::select(['akun_id'])->where('setting', 'inventory')->first()->akun_id,
                'nominal_debit' => $request['total'],
                'nominal_kredit' => 0,
                'keterangan' => '',
            ]),
            new DetHistoryJurnal([
                'akun_id' => PengaturanAkun::select(['akun_id'])->where('setting', 'hutang')->first()->akun_id,
                'nominal_debit' => 0,
                'nominal_kredit' => $request['total'],
                'keterangan' => '',
            ])
        ]);

    }


    public function autoInsertPembayaran($request, $transaksi)
    {
        $pembayaran = Pembayaran::create([
            'kode_pembayaran' => app('App\Http\Controllers\PembayaranController')->no_pembayaran(),
            'supplier_id' => $request['supplier'],
            'kas_id' => $request['kas'],
            'tgl_pembayaran' => $request['tgl_pembelian'],
            'total_hutang' => $request['total'],
            'total_pembayaran' => $request['uang_muka']
        ]);
        $pembayaran->det_pembayaran()->save(
            new DetPembayaran([
                'pembayaran_type' => 'App\TransaksiPembelian',
                'no_ref' => $transaksi->no_pembelian,
                'jumlah_bayar' => $request['uang_muka'],
                'keterangan' => 'Pembayaran Uang Muka Pembelian '.$transaksi->no_pembelian,
            ])
        );
    }

    public function autoInsertJurnalPembayaran($request, $transaksi)
    {
        // // pembayaran uang muka
        $history_jurnal_2 = new HistoryJurnal([
            'kode_journal' => app('App\Http\Controllers\HistoryJurnalController')->kode_journal(),
            'tgl_set' => $request['tgl_pembelian'],
            'autogen' => '1',
            'total_debit' => 0,
            'total_kredit' => 0,
            'keterangan' => 'Pembayaran Uang Muka Dari Transaksi Pembelian '.$transaksi->no_pembelian,
        ]);
        $transaksi->history_jurnal()->save($history_jurnal_2);
        $history_jurnal_2->det_history_jurnal()->saveMany([
            new DetHistoryJurnal([
                'akun_id' => PengaturanAkun::select(['akun_id'])->where('setting', 'deposit_supplier')->first()->akun_id,
                'nominal_debit' => $request['uang_muka'],
                'nominal_kredit' => 0,
                'keterangan' => 'Untuk Bayar',
            ]),
            new DetHistoryJurnal([
                'akun_id' => PengaturanAkun::select(['akun_id'])->where('setting', 'kas')->first()->akun_id,
                'nominal_debit' => 0,
                'nominal_kredit' => $request['uang_muka'],
                'keterangan' => 'Cara Bayar',
            ])
        ]);
        // pembayaran uang muka
        $history_jurnal_3 = new HistoryJurnal([
            'kode_journal' => app('App\Http\Controllers\HistoryJurnalController')->kode_journal(),
            'tgl_set' => $request['tgl_pembelian'],
            'autogen' => '1',
            'total_debit' => 0,
            'total_kredit' => 0,
            'keterangan' => 'Pembayaran Uang Muka Dari Transaksi Pembelian '.$transaksi->no_pembelian,
        ]);
        $transaksi->history_jurnal()->save($history_jurnal_3);
        $history_jurnal_3->det_history_jurnal()->saveMany([
            new DetHistoryJurnal([
                'akun_id' => PengaturanAkun::select(['akun_id'])->where('setting', 'deposit_supplier')->first()->akun_id,
                'nominal_debit' => 0,
                'nominal_kredit' => $request['uang_muka'],
                'keterangan' => 'Untuk Bayar : Uang Muka Transaksi '.$transaksi->no_pembelian,
            ]),
            new DetHistoryJurnal([
                'akun_id' => PengaturanAkun::select(['akun_id'])->where('setting', 'hutang')->first()->akun_id,
                'nominal_debit' => $request['uang_muka'],
                'nominal_kredit' => 0,
                'keterangan' => 'Untuk Bayar : Pembelian Transaksi '.$transaksi->no_pembelian,
            ])
        ]);
    }





}
