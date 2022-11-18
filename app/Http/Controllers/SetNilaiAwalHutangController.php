<?php

namespace App\Http\Controllers;

use Yajra\DataTables\Facades\Datatables;
use Illuminate\Http\Request;
use App\PengaturanAkun;
use App\SetNilaiHutang;
use App\HistoryHutang;
use App\HistoryJurnal;
use App\DetHistoryJurnal;
use Carbon\Carbon;
use Auth;
use DB;

class SetNilaiAwalHutangController extends Controller
{
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $set = SetNilaiHutang::select(['id', 'kode_transaksi', 'tgl_set', 'supplier_id', 'jatuh_tempo', 'total', 'akun_id'])
                    ->with(['supplier'])
                    ->orderBy('created_at');

            return Datatables::of($set)
                    ->addIndexColumn()
                    ->addColumn('supplier', function($data){
                        return $data->supplier->nama_supplier;
                    })
                    ->editColumn('tgl_set', function($data){
                        return date('d/M/Y', strtotime($data->tgl_set)).' '.date('h:i A', strtotime($data->tgl_set));
                    })
                    ->editColumn('total', function($data){
                        return 'Rp.'.number_format($data->total, 0);
                    })
                    ->addColumn('action', function($data){
                        return '<ul class="icons-list">
                                <li class="text-primary-600"><a href="'.url("sistem/set-nilai-hutang/".$data->id)."/edit".'"><i class="icon-pencil5"></i></a></li>
                                <li class="text-danger-600"><a id="button_delete" data-id="'.$data->id.'" href="#"><i class="icon-trash"></i></a></li>
                        </ul>';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        return view('backoffice.page.set_nilai_hutang.index');
    }

    
    public function create()
    {
        $akun_hutang = PengaturanAkun::where('setting', 'hutang')->first();
        return view('backoffice.page.set_nilai_hutang.create', [
            'akun_hutang' => $akun_hutang,
            'kode' => $this->kode_transaksi()
        ]);
    }

    
    public function store(Request $request)
    {
        $this->validate($request, [
            'kode_transaksi' => 'required|unique:set_nilai_hutang,kode_transaksi',
            'tgl_set' => 'required',
            'supplier' => 'required',
            'jatuh_tempo' => 'required',
            'total' => 'required',
            'akun' => 'required',
        ], [
            'kode_transaksi.required' => 'Kode Transaksi Wajib Diisi !',
            'kode_transaksi.unique' => 'Kode Transaksi Sudah Digunakan !',
            'tgl_set.required' => 'Tgl Set Wajib Diisi !',
            'supplier.required' => 'Supplier Wajib Diisi !',
            'jatuh_tempo.required' => 'Jatuh Tempo Wajib Di Isi!',
            'total.required' => 'Total Wajib Diisi !',
            'akun.required' => 'Akun Wajib DIpilih !',
        ]);

        try {
            DB::beginTransaction();
            $set = SetNilaiHutang::create([
                'kode_transaksi' => $request->kode_transaksi,
                'tgl_set' => date('Y-m-d', strtotime($request->tgl_set)),
                'supplier_id' => $request->supplier,
                'jatuh_tempo' => $request->jatuh_tempo,
                'total' => str_replace(',', '', $request->total),
                'akun_id' => $request->akun,
                'keterangan' => $request->keterangan,
            ]);
            
            $history = new HistoryHutang([
                'supplier_id' => $request->supplier,
                'nominal' => str_replace(',', '', $request->total),
                'terbayar' => 0,
                'sisa_pembayaran' => str_replace(',', '', $request->total),
                'status_lunas' => "0",
                'tgl_jatuh_tempo' => Carbon::parse($request->tgl_set)->addDays($request->jatuh_tempo)->format('Y-m-d'),
            ]);
            $set->history_hutang()->save($history);


            $history_jurnal = new HistoryJurnal([ 
                'kode_journal' => app('App\Http\Controllers\HistoryJurnalController')->kode_journal(),
                'tgl_set' => date('Y-m-d', strtotime($request->tgl_set)),
                'autogen' => '1',
                'total_debit' => 0,
                'total_kredit' => 0,
                'keterangan' => 'Set saldo awal Hutang: '.$set->kode_transaksi.' Keterangan : '.$request->keterangan,
            ]);
            $set->history_jurnal()->save($history_jurnal);
 
            $history_jurnal->det_history_jurnal()->saveMany([
                new DetHistoryJurnal([
                    'akun_id' => PengaturanAkun::select(['akun_id'])->where('setting', 'saldo_awal')->first()->akun_id,
                    'nominal_debit' => str_replace(',', '', $request->total),
                    'nominal_kredit' => 0,
                    'keterangan' => '',
                ]),
                new DetHistoryJurnal([
                    'akun_id' => PengaturanAkun::select(['akun_id'])->where('setting', 'hutang')->first()->akun_id,
                    'nominal_debit' => 0,
                    'nominal_kredit' => str_replace(',', '', $request->total),
                    'keterangan' => '',
                ])
            ]);
            

            DB::commit();
            return redirect('sistem/set-nilai-hutang')->with(['success' => 'Berhasil Menyimpan Data Set Nilai Awal Hutang !']);
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return redirect('sistem/set-nilai-hutang')->with(['error' => 'Gagal Menyimpan Data Set Nilai Awal Hutang !']);
        }
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
        $set = SetNilaiHutang::findOrFail($id);
        try {
            DB::beginTransaction();
            $set->history_hutang()->delete();
            $set->history_jurnal()->delete();
            $set->delete();
            DB::commit();
            return response()->json(['success' => true], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e, 400);
        }
    }

    public function kode_transaksi()
    {
        $check = SetNilaiHutang::select('kode_transaksi', 'created_at')->orderBy('created_at', 'desc')->first();
        if (!empty($check)) {
            $bulan_last = Carbon::parse($check->created_at)->format('m');
            $no_pembelian = $check->kode_transaksi;
        } else {
            $bulan_last = date('m');
            $no_pembelian = 'SH'.date('ym').'000';
        }

        if ($bulan_last >= date('m')) {
            $lastNoUrut = (int)substr($no_pembelian, 6, 3);
            $tmp = ((int)$lastNoUrut)+1;
            $kd = 'SH'.date('ym').sprintf("%03s", $tmp);
        } else {
            $lastNoUrut = (int)substr($no_pembelian, 6, 3);
            $tmp = 000+1;
            $kd = 'SH'.date('ym').sprintf("%03s", $tmp);
        }
        return $kd;
    }
}
