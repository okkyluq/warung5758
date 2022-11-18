<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PenyesuaianStockCreate;
use Yajra\DataTables\Facades\Datatables;
use App\Services\PenyesuaianStockService;
use App\PenyesuaianStock;
use App\DetPenyesuaianStock;
use Carbon\Carbon;
use DB;


class PenyesuaianStockController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $penyesuaian = PenyesuaianStock::orderBy('created_at', 'desc');

            return Datatables::of($penyesuaian)
                    ->addIndexColumn()
                    ->addColumn('action', function($data){
                        return '<ul class="icons-list">
                                    <li class="text-primary-600"><a href="'.url("sistem/penyesuaian-stock/".$data->id).'"><i class="icon-eye"></i></a></li>
                                    <li class="text-danger-600"><a id="button_delete" data-id="'.$data->id.'" href="#"><i class="icon-trash"></i></a></li>
                                </ul>';
                    })
                    ->rawColumns(['action'])
                    ->make(true);


        }

        return view('backoffice.page.penyesuaian_stock.index');
    }


    public function create()
    {
        return view('backoffice.page.penyesuaian_stock.create', [
            'kode' => $this->no_pembayaran()
        ]);
    }


    public function store(PenyesuaianStockCreate $request, PenyesuaianStockService $penyesuaianStockService)
    {
        try {
            DB::beginTransaction();

            $transaksi = PenyesuaianStock::create([
                'kode_transaksi' => $request->kode_transaksi,
                'tgl_set' => Carbon::createFromFormat('d/m/Y', $request->tgl_set)->format('Y-m-d'),
            ]);

            $det_penyesuaian = collect($request->list_item)->transform(function($value, $index){
                $new = [
                    'item_id'        => $value['item_id'],
                    'qty'            => $value['qty'],
                    'satuan_item_id' => $value['satuan'],
                ];
                return new DetPenyesuaianStock($new);
            });

            $transaksi->det_penyesuaian_stock()->saveMany($det_penyesuaian);
            $penyesuaianStockService->insertHistoryItem($request->all(), $transaksi);
            $penyesuaianStockService->insertJurnalPenyesuaianStockService($request->all(), $transaksi);


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

            $penyesuaian = PenyesuaianStock::findOrFail($id);
            $penyesuaian->history_item()->delete();
            $penyesuaian->history_jurnal()->delete();
            $penyesuaian->det_penyesuaian_stock()->delete();
            $penyesuaian->delete();
            DB::commit();
            return response()->json([
                'status' => 'sukses',
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e, 400);
        }
    }

    public function no_pembayaran()
    {
        $check = PenyesuaianStock::select('kode_transaksi', 'created_at')->orderBy('created_at', 'desc')->first();
        if (!empty($check)) {
            $bulan_last = Carbon::parse($check->created_at)->format('m');
            $kode_transaksi = $check->kode_transaksi;
        } else {
            $bulan_last = date('m');
            $kode_transaksi = 'PS'.date('ym').'000';
        }
        // PBYR21070001
        // PS21070001

        if ($bulan_last >= date('m')) {
            $lastNoUrut = (int)substr($kode_transaksi, 6, 3);
            $tmp = ((int)$lastNoUrut)+1;
            $kd = 'PS'.date('ym').sprintf("%03s", $tmp);
        } else {
            $lastNoUrut = (int)substr($kode_transaksi, 6, 3);
            $tmp = 000+1;
            $kd = 'PS'.date('ym').sprintf("%03s", $tmp);
        }
        return $kd;
    }


}
