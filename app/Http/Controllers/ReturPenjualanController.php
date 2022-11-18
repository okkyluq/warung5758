<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\Datatables;
use App\Http\Requests\ReturPenjualanCreate;
use App\Services\ReturPenjualanService;
use App\TransaksiPenjualan;
use App\ReturPenjualan;
use App\DetReturPenjualan;
use Carbon\Carbon;
use DB;


class ReturPenjualanController extends Controller
{
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $retur = ReturPenjualan::with(['det_retur_penjualan'])
                            ->orderBy('created_at', 'desc');
            return Datatables::of($retur)
                        ->addIndexColumn()
                        ->editColumn('tgl_transaksi', function($data){
                            return date('d/m/Y', strtotime($data->tgl_transaksi));
                        })
                        ->addColumn('action', function($data){
                            return '<ul class="icons-list">
                                    <li class="text-primary-600"><a href="'.url("sistem/retur/retur-penjualan/".$data->id).'"><i class="icon-eye"></i></a></li>
                                    <li class="text-danger-600"><a id="button_delete" data-id="'.$data->id.'" href="#"><i class="icon-trash"></i></a></li>
                                </ul>';
                        })
                        ->make(true);
            
        }
        return view('backoffice.page.retur_penjualan.index');
    }

    
    public function create()
    {
        return view('backoffice.page.retur_penjualan.create', [
            'kode' => $this->kode_transaksi()
        ]);
    }

    
    public function store(ReturPenjualanCreate $request, ReturPenjualanService $returPenjualanService)
    {   
        try {
            DB::beginTransaction();
            $transaksi = ReturPenjualan::create([
                'kode_transaksi' => $request->kode_transaksi,
                'tgl_transaksi' => Carbon::createFromFormat('d/m/Y', $request->tgl_set)->format('Y-m-d'),
                'costumer_id' => $request->costumer,
                'keterangan' => $request->keterangan,
            ]);

            $list_item = collect($request->list_item)->transform(function($value, $index){
                $new = [
                    'kas_id'         => $value['kas_id'],
                    'item_id'        => $value['item_id'],
                    'qty'            => $value['qty'],
                    'satuan_item_id' => $value['satuan_item_id'],
                    'harga'          => $value['harga'],
                    'sub_total'      => $value['sub_total'],
                ];
                return new DetReturPenjualan($new);        
            });

            $transaksi->det_retur_penjualan()->saveMany($list_item);
            $returPenjualanService->insertHistoryItem($request->all(), $transaksi); 
            $returPenjualanService->insertHistoryKas($request->all(), $transaksi); 
            $returPenjualanService->insertJurnalReturPenjualan($request->all(), $transaksi); 

            DB::commit();
            return response()->json([
                'status' => 'sukses',
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e, 400); 
        }
       
       
        return response()->json($request->all());
    }

    
    public function show($id)
    {
        //
    }

   
    public function edit($id)
    {
        //
    }

    
    public function update(Request $request, $id)
    {
        //
    }

    
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $retur = ReturPenjualan::findOrFail($id);
            $retur->history_item()->delete();
            $retur->history_kas()->delete();
            $retur->history_jurnal()->delete();
            $retur->det_retur_penjualan()->delete();
            $retur->delete();
            DB::commit();  
            return response()->json([
                'status' => 'sukses',
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e, 400); 
        }
    }

    public function kode_transaksi()
    {
        $check = ReturPenjualan::select('kode_transaksi', 'created_at')->orderBy('created_at', 'desc')->first();
        if (!empty($check)) {
            $bulan_last = Carbon::parse($check->created_at)->format('m');
            $kode_transaksi = $check->kode_transaksi;
        } else {
            $bulan_last = date('m');
            $kode_transaksi = 'RJ'.date('ym').'000';
        }

        if ($bulan_last >= date('m')) {
            $lastNoUrut = (int)substr($kode_transaksi, 6, 3);
            $tmp = ((int)$lastNoUrut)+1;
            $kd = 'RJ'.date('ym').sprintf("%03s", $tmp);
        } else {
            $lastNoUrut = (int)substr($kode_transaksi, 6, 3);
            $tmp = 000+1;
            $kd = 'RJ'.date('ym').sprintf("%03s", $tmp);
        }
        return $kd;
    }

    public function pencarian_penjualan(Request $request)
    {
        $transaksi = TransaksiPenjualan::select(['no_penjualan', 'costumer_id', 'tgl_penjualan', 'id', 'kas_id'])
                        ->with(['costumer', 'det_transaksi_penjualan.item', 'det_transaksi_penjualan.satuan_item.satuan'])
                        ->orderBy('created_at', 'desc');
 
        return Datatables::of($transaksi)
            ->editColumn('no_penjualan', function($data){
                return "<a data-detail='".$data."' href='' class='btn-get'>".$data->no_penjualan."</a>";
            })
            ->addColumn('costumer', function($data){
                return $data->costumer->nama_costumer;
            })
            ->editColumn('tgl_penjualan', function($data){
                return date('d/M/Y', strtotime($data->tgl_penjualan));
            })
            ->addColumn('total', function($data){
                return "Rp. ".number_format($data->det_transaksi_penjualan->sum('sub_total'));
            })
            ->addColumn('action', function($data){
                return '<ul class="icons-list">
                            <li class="text-primary-600"><a href="" class="btn-get"><i class="icon-checkbox-checked"></i></a></li>
                        </ul>';
            })
            ->rawColumns(['action', 'no_penjualan']) 
            ->make(true);

    }
}
