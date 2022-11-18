<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\Datatables;
use App\StockOpname;
use App\SatuanItem;
use App\DetStockOpname;
use App\Http\Requests\StockOpnameCreate;
use Carbon\Carbon;
use App\Services\StockOpnameService;
use DB;

class StockOpnameController extends Controller
{
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $opname = StockOpname::orderBy('created_at', 'desc');

            return Datatables::of($opname)
                    ->addIndexColumn()
                    ->editColumn('tgl_transaksi', function($data){
                        return date('d/M/Y', strtotime($data->tgl_transaksi));
                    })
                    ->addColumn('action', function($data){
                        return '<ul class="icons-list">
                                    <li class="text-primary-600"><a href="'.url("sistem/stock-opname/".$data->id).'"><i class="icon-eye"></i></a></li>
                                    <li class="text-danger-600"><a id="button_delete" data-id="'.$data->id.'" href="#"><i class="icon-trash"></i></a></li>
                                </ul>';
                    })
                    ->rawColumns(['action'])
                    ->make(true); 
        }
        return view('backoffice.page.stock_opname.index');
    }

    
    public function create()
    {
        return view('backoffice.page.stock_opname.create', [
            'kode' => $this->kode_transaksi()
        ]);
    }

   
    public function store(StockOpnameCreate $request, StockOpnameService $stockOpnameService)
    {
        try {
            DB::beginTransaction();
            $transaksi = StockOpname::create([
                'kode_transaksi' => $request->kode_transaksi,
                'tgl_transaksi' => Carbon::createFromFormat('d/m/Y', $request->tgl_transaksi)->format('Y-m-d'),
                'keterangan' => $request->keterangan,
            ]);

            $detail = collect($request->list_item)->transform(function($value, $index){
                $new = [
                    'item_id'        => $value['item_id'],
                    'qty_opname'     => $value['qty_opname'],
                    'qty_program'    => $value['qty_program'],
                    'qty_selisih'    => $value['qty_selisih'],
                    'satuan_item_id' => $value['satuan'],
                ];
                return new DetStockOpname($new);
            });

            $transaksi->det_stock_opname()->saveMany($detail);
            $stockOpnameService->insertHistoryItem($request->all(), $transaksi);
            $stockOpnameService->insertJurnalStockOpname($request->all(), $transaksi);

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

            $opname = StockOpname::findOrFail($id);
            $opname->history_item()->delete();
            $opname->history_jurnal()->delete();
            $opname->det_stock_opname()->delete();
            $opname->delete();
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
        $check = StockOpname::select('kode_transaksi', 'created_at')->orderBy('created_at', 'desc')->first();
        if (!empty($check)) {
            $bulan_last = Carbon::parse($check->created_at)->format('m');
            $kode_transaksi = $check->kode_transaksi;
        } else {
            $bulan_last = date('m');
            $kode_transaksi = 'SO'.date('ym').'000';
        }

        if ($bulan_last >= date('m')) {
            $lastNoUrut = (int)substr($kode_transaksi, 6, 3);
            $tmp = ((int)$lastNoUrut)+1;
            $kd = 'SO'.date('ym').sprintf("%03s", $tmp);
        } else {
            $lastNoUrut = (int)substr($kode_transaksi, 6, 3);
            $tmp = 000+1;
            $kd = 'SO'.date('ym').sprintf("%03s", $tmp);
        }
        return $kd;
    }
}
