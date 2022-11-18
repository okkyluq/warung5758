<?php

namespace App\Http\Controllers;

use Yajra\DataTables\Facades\Datatables;
use Illuminate\Http\Request;
use App\SetNilaiKas;
use App\DetSetNilaiKas;
use App\Kas;
use App\HistoryKas;
use App\HistoryJurnal;
use App\PengaturanAkun;
use App\DetHistoryJurnal;
use Carbon\Carbon; 
use Auth;
use DB;

class SetNilaiKasController extends Controller
{
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $set = SetNilaiKas::select(['id', 'kode_transaksi', 'tgl_set', 'user_id'])->orderBy('created_at', 'desc');

            return Datatables::of($set)
                    ->addIndexColumn()
                    ->editColumn('tgl_set', function($data){
                        return date('d/M/Y', strtotime($data->tgl_set)).' '.date('h:i A', strtotime($data->tgl_set));
                    })
                    ->addColumn('action', function($data){
                        return '<ul class="icons-list">
                                <li class="text-primary-600"><a href="'.url("sistem/set-nilai-kas/".$data->id)."/edit".'"><i class="icon-pencil5"></i></a></li>
                                <li class="text-danger-600"><a id="button_delete" data-id="'.$data->id.'" href="#"><i class="icon-trash"></i></a></li>
                        </ul>';
                    })
                    ->make(true);
                    
        }

        return view('backoffice.page.set_nilai_kas.index');
    }

    
    public function create()
    {
        return view('backoffice.page.set_nilai_kas.create', [
            'kode' => $this->kode_transaksi()
        ]);
    } 

    
    public function store(Request $request)
    {
        $this->validate($request, [
            'kode_transaksi' => 'required',
            'tgl_set' => 'required',
            'kas' => 'required',
            'nominal' => 'required',
        ], [
            'kode_transaksi.required' => 'Kode Transaksi Wajib Terisi !',
            'tgl_set.required' => 'Tanggal Set Wajib Terisi !',
            'kas.required' => 'Kas Wajib DIpilih !',
            'nominal.required' => 'Nominal Wajib Terisi !',
        ]);
        

        try {
            DB::beginTransaction();

            $set = SetNilaiKas::create([
                'kode_transaksi' => $request->kode_transaksi,
                'tgl_set' => date('Y-m-d', strtotime($request->tgl_set)),
                'kas_id' => $request->kas,
                'nominal' => str_replace(',', '', $request->nominal),
                'keterangan' => $request->keterangan == '' ? '-' : $request->keterangan,
                'user_id' => Auth::user()->id,
            ]);

            // insert history kas
            $history_kas = new HistoryKas([
                'kas_id' => $request->kas,
                'nominal_debit' => str_replace(',', '', $request->nominal),
                'nominal_kredit' => 0,
            ]);
            $set->history_kas()->save($history_kas);


            // insert history jurnal
            $history_jurnal = new HistoryJurnal([ 
                'kode_journal' => app('App\Http\Controllers\HistoryJurnalController')->kode_journal(),
                'tgl_set' => date('Y-m-d', strtotime($request->tgl_set)),
                'autogen' => '1',
                'total_debit' => 0,
                'total_kredit' => 0,
                'keterangan' => 'Set saldo awal Kas: '.$set->kode_transaksi.' Keterangan : '.$request->keterangan,
            ]);
            $set->history_jurnal()->save($history_jurnal);
 
            $history_jurnal->det_history_jurnal()->saveMany([
                new DetHistoryJurnal([
                    'akun_id' => Kas::select(['id', 'akun_id'])->where('id', $request->kas)->first()->akun_id,
                    'nominal_debit' => str_replace(',', '', $request->nominal),
                    'nominal_kredit' => 0,
                    'keterangan' => '',
                ]),
                new DetHistoryJurnal([
                    'akun_id' => PengaturanAkun::select(['akun_id'])->where('setting', 'saldo_awal')->first()->akun_id,
                    'nominal_debit' => 0,
                    'nominal_kredit' => str_replace(',', '', $request->nominal),
                    'keterangan' => '',
                ])
            ]);




            DB::commit();
            return redirect('sistem/set-nilai-kas')->with(['success' => 'Berhasil Menyimpan Transaksi Set Nilai Awal Kas']);
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return response()->json($e, 400); 
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
        $set = SetNilaiKas::findOrFail($id);
        try {
            DB::beginTransaction();
            $set->history_kas()->delete();
            $set->history_jurnal()->delete();
            $set->delete();
            DB::commit();
            return response()->json(['status' => 'success'], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json($e, 400);
        }
    }

    public function kode_transaksi()
    {
        $check = SetNilaiKas::select('kode_transaksi', 'created_at')->orderBy('created_at', 'desc')->first();
        if (!empty($check)) {
            $bulan_last = Carbon::parse($check->created_at)->format('m');
            $no_pembelian = $check->kode_transaksi;
        } else {
            $bulan_last = date('m');
            $no_pembelian = 'SK'.date('ym').'000';
        }

        if ($bulan_last >= date('m')) {
            $lastNoUrut = (int)substr($no_pembelian, 6, 3);
            $tmp = ((int)$lastNoUrut)+1;
            $kd = 'SK'.date('ym').sprintf("%03s", $tmp);
        } else {
            $lastNoUrut = (int)substr($no_pembelian, 6, 3);
            $tmp = 000+1;
            $kd = 'SK'.date('ym').sprintf("%03s", $tmp);
        }
        return $kd;
    }
}
