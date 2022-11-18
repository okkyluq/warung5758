<?php

namespace App\Services;

use App\HistoryPiutang; 
use App\HistoryKas;
use App\HistoryJurnal;
use App\DetHistoryJurnal;
use App\PengaturanAkun;
use Carbon\Carbon;

class PenerimaanPembayaranService
{
    public function updateHistoryPiutang($request, $transaksi)
    {
        foreach (collect($request['list_item']) as $key => $value) {
            if($value['data_type'] == 'App\HistoryPiutang'){
                $history = HistoryPiutang::where('id', $value['data_id'])->first();
                $history->increment('terbayar', $value['jumlah_bayar']);
                $history->decrement('sisa_pembayaran', $value['jumlah_bayar']);
                if($history->nominal == $history->terbayar) {
                    $history->update([
                        'status_lunas' => '1'
                    ]);
                }
            }
        } 
    }

    public function insertHistoryKas($request, $transaksi)
    {
        $transaksi->history_kas()->save(
            new HistoryKas([
                'kas_id' => $request['kas'],
                'nominal_debit' => str_replace(',', '', $request['total_pembayaran']),
                'nominal_kredit' => 0,
            ])
        ); 
    }

    public function insertJurnalPenerimaanPembayaran($request, $transaksi)
    {
        // jurnal pembayaran
        // dd($request);
        $history_jurnal = new HistoryJurnal([  
            'kode_journal' => app('App\Http\Controllers\HistoryJurnalController')->kode_journal(),
            'tgl_set' => Carbon::createFromFormat('d/m/Y', $request['tgl_penerimaan'])->format('Y-m-d'),
            'autogen' => '1',
            'total_debit' => 0,
            'total_kredit' => 0, 
            'keterangan' => 'Pembayaran '.$request['kode_pembayaran'],
        ]);
        $transaksi->history_jurnal()->save($history_jurnal);
        
        $detail_jurnal = collect($request['list_item'])->transform(function($value, $index){
            $new['akun_id']          = $value['akun_id'];
            $new['nominal_debit']    = 0;
            $new['nominal_kredit']   = $value['jumlah_bayar'];
            $new['keterangan']       = $value['keterangan'];
            return new DetHistoryJurnal($new);
        })->push(
            new DetHistoryJurnal([
                'akun_id' => PengaturanAkun::select(['akun_id'])->where('setting', 'kas')->first()->akun_id,
                'nominal_debit' => str_replace(',', '', $request['total_pembayaran']),
                'nominal_kredit' => 0,
                'keterangan' => 'Cara Bayar',
            ])
        ); 

        $history_jurnal->det_history_jurnal()->saveMany($detail_jurnal);
    }
}