<?php

namespace App\Services;
use App\HistoryItem;
use App\SatuanItem;
use App\HistoryJurnal;
use App\DetHistoryJurnal;
use App\PengaturanAkun;
use Carbon\Carbon;

class PenyesuaianStockService 
{
    public function insertHistoryItem($request, $transaksi)
    {
        
        $list_item = collect($request['list_item'])->transform(function($value, $index){
            $satuan_item = SatuanItem::where('id', $value['satuan'])->first();
            $hpp_unit_low = str_replace(',', '', app('App\Http\Controllers\ItemController')->gethpp($value['item_id']));
            $qty_konverter = $satuan_item->qty_konversi;

            $new =  [
                'item_id'        => $value['item_id'],
                'satuan_item_id' => $value['satuan'],
                'qty'            => $value['qty'],
                'status_in_out'  => '1',
                'harga'          => $hpp_unit_low * $qty_konverter,
            ];
            return new HistoryItem($new);
        }); 

        return $transaksi->history_item()->saveMany($list_item);
    }

    public function insertJurnalPenyesuaianStockService($request, $transaksi)
    {
        $list_item = collect($request['list_item'])->transform(function($value, $index){
            $satuan_item = SatuanItem::where('id', $value['satuan'])->first();
            $hpp_unit_low = str_replace(',', '', app('App\Http\Controllers\ItemController')->gethpp($value['item_id']));
            $qty_konverter = $satuan_item->qty_konversi;

            $new =  [
                'item_id'        => $value['item_id'],
                'satuan_item_id' => $value['satuan'],
                'qty'            => $value['qty'],
                'status_in_out'  => '1',
                'harga'          => $hpp_unit_low * $qty_konverter,
                'total'          => $value['qty'] * ($hpp_unit_low * $qty_konverter),
            ];
            return $new;
        }); 

        
        $history_jurnal = new HistoryJurnal([  
            'kode_journal' => app('App\Http\Controllers\HistoryJurnalController')->kode_journal(),
            'tgl_set' => Carbon::createFromFormat('d/m/Y', $request['tgl_set'])->format('Y-m-d'),
            'autogen' => '1',
            'total_debit' => $list_item->sum('total'),
            'total_kredit' => $list_item->sum('total'), 
            'keterangan' => 'Penyesuaian Stock '.$transaksi->kode_transaksi,
        ]);
        $transaksi->history_jurnal()->save($history_jurnal);
        return $history_jurnal->det_history_jurnal()->saveMany([
            new DetHistoryJurnal([
                'akun_id' => PengaturanAkun::select(['akun_id'])->where('setting', 'inventory')->first()->akun_id,
                'nominal_debit' => $list_item->sum('total'),
                'nominal_kredit' => 0,
                'keterangan' => '',
            ]),
            new DetHistoryJurnal([
                'akun_id' => PengaturanAkun::select(['akun_id'])->where('setting', 'p_k_stok')->first()->akun_id,
                'nominal_debit' => 0,
                'nominal_kredit' => $list_item->sum('total'),
                'keterangan' => '',
            ])
        ]);
    }
}