<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\Datatables;
use App\Http\Requests\ReturPembelianCreate;
use App\Services\ReturPembelianService;
use App\TransaksiPembelian;
use App\ReturPembelian;
use App\DetReturPembelian;
use Carbon\Carbon;
use DB;

class ReturPembelianController extends Controller
{
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $retur = ReturPembelian::with(['det_retur_pembelian'])
                            ->orderBy('created_at', 'desc');
            return Datatables::of($retur)
                        ->addIndexColumn()
                        ->editColumn('tgl_transaksi', function($data){
                            return date('d/m/Y', strtotime($data->tgl_transaksi));
                        })
                        ->addColumn('action', function($data){
                            return '<ul class="icons-list">
                                    <li class="text-primary-600"><a href="'.url("sistem/retur/retur-pembelian/".$data->id).'"><i class="icon-eye"></i></a></li>
                                    <li class="text-danger-600"><a id="button_delete" data-id="'.$data->id.'" href="#"><i class="icon-trash"></i></a></li>
                                </ul>';
                        })
                        ->make(true);
            
        }
        return view('backoffice.page.retur_pembelian.index');
    }

    
    public function create()
    {
        return view('backoffice.page.retur_pembelian.create', [
            'kode' => $this->kode_transaksi()
        ]);
    }

    
    public function store(ReturPembelianCreate $request, ReturPembelianService $returPembelianService)
    {
        try {
            DB::beginTransaction();
            $transaksi = ReturPembelian::create([
                'kode_transaksi' => $request->kode_transaksi,
                'tgl_transaksi' => Carbon::createFromFormat('d/m/Y', $request->tgl_set)->format('Y-m-d'),
                'supplier_id' => $request->supplier,
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
                return new DetReturPembelian($new);        
            });

            $transaksi->det_retur_pembelian()->saveMany($list_item);
            $returPembelianService->insertHistoryItem($request->all(), $transaksi); 
            $returPembelianService->insertHistoryKas($request->all(), $transaksi); 
            $returPembelianService->insertJurnalReturPembelian($request->all(), $transaksi); 

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
            $retur = ReturPembelian::findOrFail($id);
            $retur->history_item()->delete();
            $retur->history_kas()->delete();
            $retur->history_jurnal()->delete();
            $retur->det_retur_pembelian()->delete();
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
        $check = ReturPembelian::select('kode_transaksi', 'created_at')->orderBy('created_at', 'desc')->first();
        if (!empty($check)) {
            $bulan_last = Carbon::parse($check->created_at)->format('m');
            $kode_transaksi = $check->kode_transaksi;
        } else {
            $bulan_last = date('m');
            $kode_transaksi = 'RB'.date('ym').'000';
        }

        if ($bulan_last >= date('m')) {
            $lastNoUrut = (int)substr($kode_transaksi, 6, 3);
            $tmp = ((int)$lastNoUrut)+1;
            $kd = 'RB'.date('ym').sprintf("%03s", $tmp);
        } else {
            $lastNoUrut = (int)substr($kode_transaksi, 6, 3);
            $tmp = 000+1;
            $kd = 'RB'.date('ym').sprintf("%03s", $tmp);
        }
        return $kd;
    }


    public function pencarian_pembelian(Request $request)
    {
        $transaksi = TransaksiPembelian::select(['no_pembelian', 'supplier_id', 'tgl_pembelian', 'id', 'kas_id'])
                        ->with(['supplier', 'det_transaksi_pembelian.item', 'det_transaksi_pembelian.satuan_item.satuan'])
                        ->orderBy('created_at', 'desc');
 
        return Datatables::of($transaksi)
            ->editColumn('no_pembelian', function($data){
                return "<a data-detail='".$data."' href='' class='btn-get'>".$data->no_pembelian."</a>";
            })
            ->addColumn('supplier', function($data){
                return $data->supplier->nama_supplier;
            })
            ->editColumn('tgl_pembelian', function($data){
                return date('d/M/Y', strtotime($data->tgl_pembelian));
            })
            ->addColumn('total', function($data){
                return "Rp. ".number_format($data->det_transaksi_pembelian->sum('sub_total'));
            })
            ->addColumn('action', function($data){
                return '<ul class="icons-list">
                            <li class="text-primary-600"><a href="" class="btn-get"><i class="icon-checkbox-checked"></i></a></li>
                        </ul>';
            })
            ->rawColumns(['action', 'no_pembelian']) 
            ->make(true);


    }
}
