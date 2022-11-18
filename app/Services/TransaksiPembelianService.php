<?php

namespace App\Services;
use App\HistoryItem;
use App\HistoryKas;
use App\HistoryHutang;
use App\HistoryJurnal;
use App\PengaturanAkun;
use App\DetHistoryJurnal;
use App\Pembayaran;
use App\DetPembayaran;
use App\Satuan;
use App\SatuanItem;
use Carbon\Carbon;

class TransaksiPembelianService
{

    public function insertHistoryItem($request, $transaksi)
    {
        $list_item = collect($request['list_item'])->transform(function($value, $index){
            $new =  [
                'item_id'        => $value['item_id'],
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
        if($request['termin'] == '2') {
            $sisa_pembayaran = str_replace(',', '', $request['total']) - str_replace(',', '', $request['uang_muka']);

            return $transaksi->history_hutang()->save(
                new HistoryHutang([
                    'supplier_id' => $request['supplier'],
                    'nominal' => str_replace(',', '', $request['total']),
                    'terbayar' => str_replace(',', '', $request['uang_muka']),
                    'sisa_pembayaran' => $sisa_pembayaran,
                    'status_lunas' => "0",
                    'tgl_jatuh_tempo' => Carbon::createFromFormat('d/m/Y', $request['tgl_jatuh_tempo'])->format('Y-m-d')
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
                'nominal_kredit' => $request['termin'] == '1' ?  str_replace(',', '', $request['total']) : str_replace(',', '', $request['uang_muka']),
            ])
        );
    }

    public function insertJurnalTransaksiPembelianCash($request, $transaksi)
    {
        $history_jurnal = new HistoryJurnal([
            'kode_journal' => app('App\Http\Controllers\HistoryJurnalController')->kode_journal(),
            'tgl_set' => Carbon::createFromFormat('d/m/Y', $request['tgl_pembelian'])->format('Y-m-d'),
            'autogen' => '1',
            'total_debit' => str_replace(',', '', $request['total']),
            'total_kredit' => str_replace(',', '', $request['total']),
            'keterangan' => 'Pembelian '.$transaksi->no_pembelian,
        ]);
        $transaksi->history_jurnal()->save($history_jurnal);
        return $history_jurnal->det_history_jurnal()->saveMany([
            new DetHistoryJurnal([
                'akun_id' => PengaturanAkun::select(['akun_id'])->where('setting', 'inventory')->first()->akun_id,
                'nominal_debit' => str_replace(',', '', $request['total']),
                'nominal_kredit' => 0,
                'keterangan' => '',
            ]),
            new DetHistoryJurnal([
                'akun_id' => PengaturanAkun::select(['akun_id'])->where('setting', 'kas')->first()->akun_id,
                'nominal_debit' => 0,
                'nominal_kredit' => str_replace(',', '', $request['total']),
                'keterangan' => '',
            ])
        ]);
    }

    public function insertJurnalTransaksiPembelianCredit($request, $transaksi)
    {
        $history_jurnal = new HistoryJurnal([
            'kode_journal' => app('App\Http\Controllers\HistoryJurnalController')->kode_journal(),
            'tgl_set' => Carbon::createFromFormat('d/m/Y', $request['tgl_pembelian'])->format('Y-m-d'),
            'autogen' => '1',
            'total_debit' => str_replace(',', '', $request['total']),
            'total_kredit' => str_replace(',', '', $request['total']),
            'keterangan' => 'Pembelian '.$transaksi->no_pembelian,
        ]);

        $transaksi->history_jurnal()->save($history_jurnal);
        $history_jurnal->det_history_jurnal()->saveMany([
            new DetHistoryJurnal([
                'akun_id' => PengaturanAkun::select(['akun_id'])->where('setting', 'inventory')->first()->akun_id,
                'nominal_debit' => str_replace(',', '', $request['total']),
                'nominal_kredit' => 0,
                'keterangan' => '',
            ]),
            new DetHistoryJurnal([
                'akun_id' => PengaturanAkun::select(['akun_id'])->where('setting', 'hutang')->first()->akun_id,
                'nominal_debit' => 0,
                'nominal_kredit' => str_replace(',', '', $request['total']),
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
            'tgl_pembayaran' => Carbon::createFromFormat('d/m/Y', $request['tgl_pembelian'])->format('Y-m-d'),
            'total_hutang' => str_replace(',', '', $request['total']),
            'total_pembayaran' => str_replace(',', '', $request['uang_muka'])
        ]);
        $pembayaran->det_pembayaran()->save(
            new DetPembayaran([
                'pembayaran_type' => 'App\TransaksiPembelian',
                'no_ref' => $transaksi->no_pembelian,
                'jumlah_bayar' => str_replace(',', '', $request['uang_muka']),
                'keterangan' => 'Pembayaran Uang Muka Pembelian '.$transaksi->no_pembelian,
            ])
        );
    }

    public function autoInsertJurnalPembayaran($request, $transaksi)
    {
        // // pembayaran uang muka
        $history_jurnal_2 = new HistoryJurnal([
            'kode_journal' => app('App\Http\Controllers\HistoryJurnalController')->kode_journal(),
            'tgl_set' => Carbon::createFromFormat('d/m/Y', $request['tgl_pembelian'])->format('Y-m-d'),
            'autogen' => '1',
            'total_debit' => 0,
            'total_kredit' => 0,
            'keterangan' => 'Pembayaran Uang Muka Dari Transaksi Pembelian '.$transaksi->no_pembelian,
        ]);
        $transaksi->history_jurnal()->save($history_jurnal_2);
        $history_jurnal_2->det_history_jurnal()->saveMany([
            new DetHistoryJurnal([
                'akun_id' => PengaturanAkun::select(['akun_id'])->where('setting', 'deposit_supplier')->first()->akun_id,
                'nominal_debit' => str_replace(',', '', $request['uang_muka']),
                'nominal_kredit' => 0,
                'keterangan' => 'Untuk Bayar',
            ]),
            new DetHistoryJurnal([
                'akun_id' => PengaturanAkun::select(['akun_id'])->where('setting', 'kas')->first()->akun_id,
                'nominal_debit' => 0,
                'nominal_kredit' => str_replace(',', '', $request['uang_muka']),
                'keterangan' => 'Cara Bayar',
            ])
        ]);
        // pembayaran uang muka
        $history_jurnal_3 = new HistoryJurnal([
            'kode_journal' => app('App\Http\Controllers\HistoryJurnalController')->kode_journal(),
            'tgl_set' => Carbon::createFromFormat('d/m/Y', $request['tgl_pembelian'])->format('Y-m-d'),
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
                'nominal_kredit' => str_replace(',', '', $request['uang_muka']),
                'keterangan' => 'Untuk Bayar : Uang Muka Transaksi '.$transaksi->no_pembelian,
            ]),
            new DetHistoryJurnal([
                'akun_id' => PengaturanAkun::select(['akun_id'])->where('setting', 'hutang')->first()->akun_id,
                'nominal_debit' => str_replace(',', '', $request['uang_muka']),
                'nominal_kredit' => 0,
                'keterangan' => 'Untuk Bayar : Pembelian Transaksi '.$transaksi->no_pembelian,
            ])
        ]);
    }





}
