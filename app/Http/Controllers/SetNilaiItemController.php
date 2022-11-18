<?php

namespace App\Http\Controllers;

use Yajra\DataTables\Facades\Datatables;
use Illuminate\Http\Request;
use App\DetSetNilaiItem;
use App\SetNilaiItem;
use App\HistoryItem;
use App\HistoryJurnal;
use App\DetHistoryJurnal;
use App\PengaturanAkun;
use App\SatuanItem;
use App\Satuan;
use Carbon\Carbon;
use Auth;
use DB;

class SetNilaiItemController extends Controller
{
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            
            $set = SetNilaiItem::select(['id', 'kode_transaksi', 'tgl_set', 'user_id'])->orderBy('created_at', 'desc');

            return Datatables::of($set)
                    ->addIndexColumn()
                    ->editColumn('tgl_set', function($data){
                        return date('d/M/Y', strtotime($data->tgl_set)).' '.date('h:i A', strtotime($data->tgl_set));
                    })
                    ->addColumn('action', function($data){
                        return '<ul class="icons-list">
                                <li class="text-primary-600"><a href="'.url("sistem/set-nilai-item/".$data->id)."/edit".'"><i class="icon-pencil5"></i></a></li>
                                <li class="text-danger-600"><a id="button_delete" data-id="'.$data->id.'" href="#"><i class="icon-trash"></i></a></li>
                        </ul>';
                    })
                    ->make(true);

        }

        return view('backoffice.page.set_nilai_item.index');
    }

   
    public function create()
    {
        return view('backoffice.page.set_nilai_item.create', [
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
            'list_item.*.item_id' => 'required',
            'list_item.*.satuan' => 'required',
            'list_item.*.qty' => 'required|gt:0',
            'list_item.*.hpp' => 'required|gt:0',
        ];
        $message = [
            'kode_transaksi.required' => 'Kode Transaksi Wajib Disi !',
            'tgl_set.required' => 'Tanggal Wajib Diisi',
            'list_item.required' => 'Item Minimal 1'
        ];
        foreach ($request->list_item as $key => $value) {
            $baris = $key+1;
            $message['list_item.'.$key.'.item_id.required'] = 'Baris Ke-'.$baris.' Item Belum Dipilih ';
            $message['list_item.'.$key.'.satuan.required'] = 'Baris Ke-'.$baris.' Satuan Belum Diset ';
            $message['list_item.'.$key.'.qty.required'] = 'Baris Ke-'.$baris.' Qty Tidak Boleh Kosong ';
            $message['list_item.'.$key.'.qty.gt'] = 'Baris Ke-'.$baris.' Qty Tidak Boleh Kosong ';
            $message['list_item.'.$key.'.hpp.required'] = 'Baris Ke-'.$baris.' HPP Wajib Di isi ';
            $message['list_item.'.$key.'.hpp.gt'] = 'Baris Ke-'.$baris.' Item Harus Memiliki HPP ';
        }
        $this->validate($request, $rules, $message);

        

        try {
            DB::beginTransaction();
            
            $set = SetNilaiItem::create([
                'kode_transaksi' => $request->kode_transaksi,
                'tgl_set' => date('Y-m-d', strtotime($request->tgl_set)),
                'user_id' => Auth::user()->id,
            ]);
    
            $detail = collect($request->list_item)->transform(function($value, $index){
                $satuan = Satuan::where('satuan', $value['satuan'])->first();
                $satuan_item = SatuanItem::where('item_id', $value['item_id'])->where('satuan_id', $satuan->id)->first();
                $new['item_id']           = $value['item_id'];
                $new['qty']               = $value['qty'];
                $new['satuan_item_id']    = $satuan_item->id;
                $new['hpp']               = $value['hpp'];
                $new['sub_total']         = $value['qty'] * $value['hpp'];
                return new DetSetNilaiItem($new);
            }); 
    
            $set->det_set_nilai_item()->saveMany($detail);

            // buat insert history item
            $det_history = collect($request->list_item)->transform(function($value, $index){
                $satuan = Satuan::where('satuan', $value['satuan'])->first();
                $satuan_item = SatuanItem::where('item_id', $value['item_id'])->where('satuan_id', $satuan->id)->first();
                $new['item_id']           = $value['item_id'];
                $new['qty']               = $value['qty'];
                $new['satuan_item_id']    = $satuan_item->id;
                $new['status_in_out']     = '1';
                $new['harga']             = $value['hpp'];
                return new HistoryItem($new);
            }); 
            $set->history_item()->saveMany($det_history);

            // buat insert history jurnal
            $history_jurnal = new HistoryJurnal([ 
                'kode_journal' => app('App\Http\Controllers\HistoryJurnalController')->kode_journal(),
                'tgl_set' => date('Y-m-d', strtotime($request->tgl_set)),
                'autogen' => '1',
                'total_debit' => 0,
                'total_kredit' => 0,
                'keterangan' => 'Set Nilai awal Item: '.$set->kode_transaksi,
            ]);
            $set->history_jurnal()->save($history_jurnal);

            $detail_jurnal_sum = collect($request->list_item)->transform(function($value, $index){
                $new['sub_total']  = $value['qty'] * $value['hpp'];
                return $new;
            }); 
    
            $history_jurnal->det_history_jurnal()->saveMany([
                new DetHistoryJurnal([
                    'akun_id' => PengaturanAkun::select(['akun_id'])->where('setting', 'inventory')->first()->akun_id,
                    'nominal_debit' => str_replace(',', '', $detail_jurnal_sum->sum('sub_total')),
                    'nominal_kredit' => 0,
                    'keterangan' => '',
                ]),
                new DetHistoryJurnal([
                    'akun_id' => PengaturanAkun::select(['akun_id'])->where('setting', 'saldo_awal')->first()->akun_id,
                    'nominal_debit' => 0,
                    'nominal_kredit' => str_replace(',', '', $detail_jurnal_sum->sum('sub_total')),
                    'keterangan' => '',
                ])
            ]);

            DB::commit();  
            return response()->json([
                'status' => 'sukses',
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
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
        $set = SetNilaiItem::findOrFail($id);
        try {
            DB::beginTransaction();
            $set->history_item()->delete();
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
        $check = SetNilaiItem::select('kode_transaksi', 'created_at')->orderBy('created_at', 'desc')->first();
        if (!empty($check)) {
            $bulan_last = Carbon::parse($check->created_at)->format('m');
            $no_pembelian = $check->kode_transaksi;
        } else {
            $bulan_last = date('m');
            $no_pembelian = 'SI'.date('ym').'000';
        }

        if ($bulan_last >= date('m')) {
            $lastNoUrut = (int)substr($no_pembelian, 6, 3);
            $tmp = ((int)$lastNoUrut)+1;
            $kd = 'SI'.date('ym').sprintf("%03s", $tmp);
        } else {
            $lastNoUrut = (int)substr($no_pembelian, 6, 3);
            $tmp = 000+1;
            $kd = 'SI'.date('ym').sprintf("%03s", $tmp);
        }
        return $kd;
    }
}
