<?php

namespace App\Http\Controllers;

use App\HistoryKas;
use App\MutasiKas;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Requests\MutasiKasCreate;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class MutasiKasController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()){
            $mutasi = MutasiKas::with(['k_utama', 'k_tujuan'])->orderBy('created_at', 'desc');

            return DataTables::of($mutasi)
                    ->addIndexColumn()
                    ->editColumn('tgl_transaksi', function($data){
                        return date('d/M/Y', strtotime($data->tgl_transaksi));
                    })
                    ->addColumn('utama', function($data){
                        return $data->k_utama->nama_kas;
                    })
                    ->addColumn('tujuan', function($data){
                        return $data->k_tujuan->nama_kas;
                    })
                    ->addCOlumn('jumlah', function($data){
                        return 'Rp.'.number_format($data->nominal_utama, 0);
                    })
                    ->addColumn('action', function($data){
                        return '<ul class="icons-list">
                                    <li class="text-info-600"><a href="'.url("keuangan/mutasi-kas/".$data->id).'"><i class="icon-file-eye"></i></a></li>
                                    <li class="text-danger-600"><a id="button_delete" data-id="'.$data->id.'" href="#"><i class="icon-trash"></i></a></li>
                                </ul>';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('backoffice.page.mutasi_kas.index');
    }


    public function create()
    {
        return view('backoffice.page.mutasi_kas.create', [
            'kode' => $this->kode_transaksi()
        ]);
    }

    public function store(MutasiKasCreate $request)
    {
        try {
            DB::beginTransaction();
            $mutasi = MutasiKas::create([
                'kode_transaksi' => $request->kode_transaksi,
                'tgl_transaksi'  => Carbon::createFromFormat('d/m/Y', $request->tgl_transaksi)->format('Y-m-d'),
                'kas_utama'      => $request->kas_utama,
                'kas_tujuan'     => $request->kas_tujuan,
                'nominal_utama'  => $request->jumlah_1,
                'nominal_tujuan' => $request->jumlah_2
            ]);

            $mutasi->history_kas()->saveMany([
                new HistoryKas([ 'kas_id' => $request->kas_utama, 'nominal_debit' => 0, 'nominal_kredit' => $request->jumlah_1 ]),
                new HistoryKas([ 'kas_id' => $request->kas_tujuan, 'nominal_debit' => $request->jumlah_2, 'nominal_kredit' => 0 ])
            ]);

            DB::commit();
            return response()->json([
                'status' => 'sukses'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e, 400);

        }

        return response()->json($request->all());
    }

    public function show($id)
    {
        $mutasi = MutasiKas::with(['k_utama', 'k_tujuan'])->findOrFail($id);
        return view('backoffice.page.mutasi_kas.detail', [
            'mutasi' => $mutasi
        ]);
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
            $mutasi = MutasiKas::findOrFail($id);
            $mutasi->history_kas()->delete();
            $mutasi->history_jurnal()->delete();
            $mutasi->delete();
            DB::commit();
            return response()->json([
                'status' => 'sukses'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e, 400);
        }
    }

    public function kode_transaksi()
    {
        $check = MutasiKas::select('kode_transaksi', 'created_at')->orderBy('created_at', 'desc')->first();
        if (!empty($check)) {
            $bulan_last = Carbon::parse($check->created_at)->format('m');
            $kode_transaksi = $check->kode_transaksi;
        } else {
            $bulan_last = date('m');
            $kode_transaksi = 'MK'.date('ym').'000';
        }

        if ($bulan_last >= date('m')) {
            $lastNoUrut = (int)substr($kode_transaksi, 6, 3);
            $tmp = ((int)$lastNoUrut)+1;
            $kd = 'MK'.date('ym').sprintf("%03s", $tmp);
        } else {
            $lastNoUrut = (int)substr($kode_transaksi, 6, 3);
            $tmp = 000+1;
            $kd = 'MK'.date('ym').sprintf("%03s", $tmp);
        }
        return $kd;
    }
}
