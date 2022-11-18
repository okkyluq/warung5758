<?php

namespace App\Http\Controllers;

use Yajra\DataTables\Facades\Datatables;
use Illuminate\Http\Request;
use App\PengaturanAkun;
use App\SetNilaiPiutang;
use App\HistoryPiutang;
use App\DetHistoryJurnal;
use App\HistoryJurnal;
use Carbon\Carbon;
use Auth;
use DB;

class SetNilaiAwalPiutangController extends Controller
{
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $set = SetNilaiPiutang::select(['id', 'kode_transaksi', 'tgl_set', 'costumer_id', 'jatuh_tempo', 'total', 'akun_id'])
                    ->with(['costumer'])
                    ->orderBy('created_at');

            return Datatables::of($set)
                    ->addIndexColumn()
                    ->addColumn('costumer', function($data){
                        return $data->costumer->nama_costumer;
                    })
                    ->editColumn('tgl_set', function($data){
                        return date('d/M/Y', strtotime($data->tgl_set)).' '.date('h:i A', strtotime($data->tgl_set));
                    })
                    ->editColumn('total', function($data){
                        return 'Rp.'.number_format($data->total, 0);
                    })
                    ->addColumn('action', function($data){
                        return '<ul class="icons-list">
                                <li class="text-primary-600"><a href="'.url("sistem/set-nilai-piutang/".$data->id)."/edit".'"><i class="icon-pencil5"></i></a></li>
                                <li class="text-danger-600"><a id="button_delete" data-id="'.$data->id.'" href="#"><i class="icon-trash"></i></a></li>
                        </ul>';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        return view('backoffice.page.set_nilai_piutang.index');
    }

    
    public function create()
    {
        $akun_piutang = PengaturanAkun::where('setting', 'piutang')->first();
        return view('backoffice.page.set_nilai_piutang.create', [
            'akun_piutang' => $akun_piutang,
            'kode' => $this->kode_transaksi()
        ]);
    }

    
    public function store(Request $request)
    {
        $this->validate($request, [
            'kode_transaksi' => 'required|unique:set_nilai_piutang,kode_transaksi',
            'tgl_set' => 'required',
            'costumer' => 'required',
            'jatuh_tempo' => 'required',
            'total' => 'required',
            'akun' => 'required',
        ], [
            'kode_transaksi.required' => 'Kode Transaksi Wajib Diisi !',
            'kode_transaksi.unique' => 'Kode Transaksi Sudah Digunakan !',
            'tgl_set.required' => 'Tgl Set Wajib Diisi !',
            'costumer.required' => 'Costumer Wajib Diisi !',
            'jatuh_tempo.required' => 'Jatuh Tempo Wajib Di Isi!',
            'total.required' => 'Total Wajib Diisi !',
            'akun.required' => 'Akun Wajib DIpilih !',
        ]);

        try {
            DB::beginTransaction();
            $set = SetNilaiPiutang::create([
                'kode_transaksi' => $request->kode_transaksi,
                'tgl_set' => date('Y-m-d', strtotime($request->tgl_set)),
                'costumer_id' => $request->costumer,
                'jatuh_tempo' => $request->jatuh_tempo,
                'total' => str_replace(',', '', $request->total),
                'akun_id' => $request->akun,
            ]);
            
            $history = new HistoryPiutang([
                'costumer_id' => $request->costumer,
                'nominal' => str_replace(',', '', $request->total),
                'terbayar' => 0,
                'sisa_pembayaran' => str_replace(',', '', $request->total),
                'status_lunas' => "0",
                'tgl_jatuh_tempo' => Carbon::parse($request->tgl_set)->addDays($request->jatuh_tempo)->format('Y-m-d'),
            ]);

            $set->history_piutang()->save($history);

            $history_jurnal = new HistoryJurnal([ 
                'kode_journal' => app('App\Http\Controllers\HistoryJurnalController')->kode_journal(),
                'tgl_set' => date('Y-m-d', strtotime($request->tgl_set)),
                'autogen' => '1',
                'total_debit' => 0,
                'total_kredit' => 0,
                'keterangan' => 'Set saldo awal Piutang: '.$set->kode_transaksi.' Keterangan : '.$request->keterangan,
            ]);
            $set->history_jurnal()->save($history_jurnal);
 
            $history_jurnal->det_history_jurnal()->saveMany([
                new DetHistoryJurnal([
                    'akun_id' => PengaturanAkun::select(['akun_id'])->where('setting', 'piutang')->first()->akun_id,
                    'nominal_debit' => str_replace(',', '', $request->total),
                    'nominal_kredit' => 0,
                    'keterangan' => '',
                ]),
                new DetHistoryJurnal([
                    'akun_id' => PengaturanAkun::select(['akun_id'])->where('setting', 'saldo_awal')->first()->akun_id,
                    'nominal_debit' => 0,
                    'nominal_kredit' => str_replace(',', '', $request->total),
                    'keterangan' => '',
                ])
            ]);



            DB::commit();
            return redirect('sistem/set-nilai-piutang')->with(['success' => 'Berhasil Menyimpan Data Set Nilai Awal Piutang !']);
        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
            return redirect('sistem/set-nilai-piutang')->with(['error' => 'Gagal Menyimpan Data Set Nilai Awal Piutang !']);
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
        $set = SetNilaiPiutang::findOrFail($id);
        try {
            DB::beginTransaction();
            $set->history_piutang()->delete();
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
        $check = SetNilaiPiutang::select('kode_transaksi', 'created_at')->orderBy('created_at', 'desc')->first();
        if (!empty($check)) {
            $bulan_last = Carbon::parse($check->created_at)->format('m');
            $no_pembelian = $check->kode_transaksi;
        } else {
            $bulan_last = date('m');
            $no_pembelian = 'SP'.date('ym').'000';
        }

        if ($bulan_last >= date('m')) {
            $lastNoUrut = (int)substr($no_pembelian, 6, 3);
            $tmp = ((int)$lastNoUrut)+1;
            $kd = 'SP'.date('ym').sprintf("%03s", $tmp);
        } else {
            $lastNoUrut = (int)substr($no_pembelian, 6, 3);
            $tmp = 000+1;
            $kd = 'SP'.date('ym').sprintf("%03s", $tmp);
        }
        return $kd;
    }
}
