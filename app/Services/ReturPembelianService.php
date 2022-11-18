<?php

namespace App\Services;

use App\HistoryItem;
use App\HistoryKas;
use App\PengaturanAkun;
use App\HistoryJurnal;
use App\DetHistoryJurnal;
use Carbon\Carbon;

class ReturPembelianService 
{
    public function insertHistoryItem($request, $transaksi)
    {
        $list_item = collect($request['list_item'])->transform(function($value, $index){
            $new =  [
                'item_id'        => $value['item_id'],
                'satuan_item_id' => $value['satuan_item_id'],
                'qty'            => -$value['qty'],
                'status_in_out'  => '0',
                'harga'          => $value['sub_total'],
            ];
            return new HistoryItem($new);
        }); 

        return $transaksi->history_item()->saveMany($list_item);
    }

    public function insertHistoryKas($request, $transaksi)
    {
        $list_kas = collect($request['list_item'])->transform(function($value, $index){
            $new =  [
                'kas_id'         => $value['kas_id'],
                'nominal_debit'  => $value['sub_total'],
                'nominal_kredit' => 0,
            ];
            return new HistoryKas($new);
        }); 
        
        return $transaksi->history_kas()->saveMany($list_kas);
    }

    public function insertJurnalReturPembelian($request, $transaksi)
    {
        $list_kas = collect($request['list_item'])->transform(function($value, $index){
            $new =  [
                'kas_id'         => $value['kas_id'],
                'sub_total'      => $value['sub_total'],
            ];
            return $new;
        }); 

        
        $history_jurnal = new HistoryJurnal([  
            'kode_journal' => app('App\Http\Controllers\HistoryJurnalController')->kode_journal(),
            'tgl_set' => Carbon::createFromFormat('d/m/Y', $request['tgl_set'])->format('Y-m-d'),
            'autogen' => '1',
            'total_debit' => 0,
            'total_kredit' => 0, 
            'keterangan' => 'Retur Pembelian '.$request['kode_transaksi'],
        ]);

        $transaksi->history_jurnal()->save($history_jurnal);

        return $history_jurnal->det_history_jurnal()->saveMany([
            new DetHistoryJurnal([
                'akun_id' => PengaturanAkun::select(['akun_id'])->where('setting', 'kas')->first()->akun_id,
                'nominal_debit' => $list_kas->sum('sub_total'),
                'nominal_kredit' => 0,
                'keterangan' => '',
            ]),
            new DetHistoryJurnal([
                'akun_id' => PengaturanAkun::select(['akun_id'])->where('setting', 'inventory')->first()->akun_id,
                'nominal_debit' => 0,
                'nominal_kredit' => $list_kas->sum('sub_total'),
                'keterangan' => '',
            ])
        ]);
    }
 
    
}