<?php

namespace App\Http\Controllers;

use Yajra\DataTables\Facades\Datatables;
use Illuminate\Http\Request;
use App\SetNilaiAkun;
use App\DetSetNilaiAkun;
use App\HistoryAkun;
use Carbon\Carbon; 
use Auth;
use DB;

class SetNilaiAwalAkunController extends Controller
{
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $set = SetNilaiAkun::select(['id', 'kode_transaksi', 'tgl_set', 'user_id'])->orderBy('created_at', 'desc');

            return Datatables::of($set)
                    ->addIndexColumn()
                    ->editColumn('tgl_set', function($data){
                        return date('d/M/Y', strtotime($data->tgl_set)).' '.date('h:i A', strtotime($data->tgl_set));
                    })
                    ->addColumn('action', function($data){
                        return '<ul class="icons-list">
                                <li class="text-primary-600"><a href="'.url("sistem/set-nilai-akun/".$data->id)."/edit".'"><i class="icon-pencil5"></i></a></li>
                                <li class="text-danger-600"><a id="button_delete" data-id="'.$data->id.'" href="#"><i class="icon-trash"></i></a></li>
                        </ul>';
                    })
                    ->make(true);
                    
        }

        return view('backoffice.page.set_nilai_akun.index');
    }

    
    public function create()
    {
        return view('backoffice.page.set_nilai_akun.create', [
            'kode' => $this->kode_transaksi()
        ]);
    }

    
    public function store(Request $request)
    {
        $request->request->add(['list_item' => json_decode($request->list_item, true)]);
        
        $rules = [
            'kode_transaksi' => 'required',
            'tgl_set' => 'required',
            'list_item' => 'required',
            'list_item.*.akun_id' => 'required',
            'list_item.*.nominal_debit' => 'required_if:nominal_kredit,==,nominal_debit',
            'list_item.*.nominal_kredit' => 'required_if:nominal_debit,==,nominal_kredit',
        ];
        $message = [
            'kode_transaksi.required' => 'Kode Transaksi Wajib Disi !',
            'tgl_set.required' => 'Tanggal Wajib Diisi',
            'list_item.required' => 'Akun Minimal 1'
        ];
        foreach ($request->list_item as $key => $value) {
            $baris = $key+1;
            $message['list_item.'.$key.'.akun_id.required'] = 'Baris Ke-'.$baris.' Akun Belum Dipilih ';
        }

        $this->validate($request, $rules, $message);

        try {
            DB::beginTransaction();

            $set = SetNilaiAkun::create([
                'kode_transaksi' => $request->kode_transaksi,
                'tgl_set' => date('Y-m-d', strtotime($request->tgl_set)),
                'user_id' => Auth::user()->id,
            ]);
            
            $detail = collect($request->list_item)->transform(function($value, $index){
                $new['akun_id']           = $value['akun_id'];
                $new['nominal_debit']     = $value['nominal_debit'];
                $new['nominal_kredit']    = $value['nominal_kredit'];
                return new DetSetNilaiAkun($new);
            }); 
    
            $set->det_set_nilai_akun()->saveMany($detail);
            
            $det_history = collect($request->list_item)->transform(function($value, $index){
                $new['akun_id']           = $value['akun_id'];
                $new['nominal_debit']     = $value['nominal_debit'];
                $new['nominal_kredit']    = $value['nominal_kredit'];
                return new HistoryAkun($new);
            }); 
            
            // buat insert history item
            $set->history_item()->saveMany($det_history);
            DB::commit();
            return response()->json([
                'status' => 'sukses',
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e, 400); 
        }
        

        dd($request->all());
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
        $set = SetNilaiAkun::findOrFail($id);
        try {
            DB::beginTransaction();
            $set->history_kas()->delete();
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
        $check = SetNilaiAkun::select('kode_transaksi', 'created_at')->orderBy('created_at', 'desc')->first();
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
