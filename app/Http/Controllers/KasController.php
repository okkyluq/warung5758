<?php

namespace App\Http\Controllers;

use Yajra\DataTables\Facades\Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Kas;
use Carbon\Carbon;

class KasController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $kas = Kas::select(['id', 'kode_kas', 'nama_kas', 'type_kas', 'akun_id'])->with(['history_kas'])
                    ->when($request->order[0]['column'] == 2, function($query) use ($request) {
                        $query->orderBy('type_kas', $request->order[0]['dir']);
                    })
                    ->orderBy('created_at', 'desc');

            return Datatables::of($kas)
                    ->addIndexColumn()
                    ->editColumn('type_kas', function($data){
                        if ($data->type_kas == '1') {
                            return '<span class="text-success"><i class="icon-wallet text-success"></i> Tunai</span>';
                        } else {
                            return '<span class="text-info"><i class="icon-wallet text-info"></i> Bank</span>';
                        }
                    })
                    ->addColumn('saldo', function($data){
                        $saldo = $data->history_kas->sum('nominal_debit') - $data->history_kas->sum('nominal_kredit');
                        return 'Rp. '.number_format($saldo, 0);
                    })
                    ->addColumn('total_giro_keluar', function($data){
                        $giro = $data->history_kas->sum('nominal_kredit');
                        return 'Rp. '.number_format($giro, 0);
                    })
                    ->addColumn('action', function($data){
                        return '
                         <div style="padding-left:5px;">
                                <a href="'.url("data-master/kas/".$data->id)."/edit".'"><i class="icon-pencil5 text-success"></i></a>
                                <button class="btn-link" id="button_delete" data-id="'.$data->id.'"  type="button"><i class="icon-trash text-danger"></i></button>
                        </div>
                        ';
                    })
                    ->rawColumns(['type_kas', 'action'])
                    ->make(true);

        }

        return view('backoffice.page.kas.index');
    }


    public function create()
    {
        return view('backoffice.page.kas.create', [
            'kode_kas' => $this->kode_kas()
        ]);
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'kode_kas' => 'required',
            'nama_kas' => 'required|unique:kas,nama_kas',
            'type_kas' => 'required',
            'akun_id' => 'required',
        ], [
            'kode_kas.required' => 'Kode Kas Wajib Di Isi !',
            'nama_kas.required' => 'Nama Kas Wajib Di Isi !',
            'nama_kas.unique' => 'Nama Kas Sudah Digunakan !',
            'type_kas.required' => 'Tipe Kas Wajib Dipilih !',
            'akun_id.required' => 'Akun Wajib Dipilih !'
        ]);

        try {
            $kas = Kas::create([
                'kode_kas' => $request->kode_kas,
                'nama_kas' => $request->nama_kas,
                'type_kas' => $request->type_kas,
                'akun_id' => $request->akun_id,
            ]);
            return redirect('data-master/kas')->with(['success' => 'Berhasil Menyimpan Data']);
        } catch (\Exception $e) {
            return redirect('data-master/kas')->with(['error' => 'Ada Masalah Saat Menyimpan Data']);
        }

        dd($request->all());
    }


    public function show($id)
    {
        $kas = Kas::with(['history_kas'])->findOrFail($id);
        dd(count($kas->history_kas));
    }


    public function edit($id)
    {
        $kas = Kas::with(['akun'])->findOrFail($id);
        return view('backoffice.page.kas.edit', [
            'kas' => $kas
        ]);
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nama_kas' => 'required|unique:kas,nama_kas,'.$id,
        ], [
            'nama_kas.required' => 'Nama Kas Wajib Di Isi !',
            'nama_kas.unique' => 'Nama Kas Sudah Digunakan !',
        ]);

        try {
            Kas::findOrFail($id)->update([
                'nama_kas' => $request->nama_kas,
            ]);
            return redirect('data-master/kas')->with(['success' => 'Berhasil Update Data']);
        } catch (\Exception $e) {
            return redirect('data-master/kas')->with(['error' => 'Ada Masalah Saat Update Data']);
        }
    }


    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $kas = Kas::with(['history_kas'])->findOrFail($id);
            if(count($kas->history_kas) > 0){
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak Dapat Menghapus Karena Kas Sudah Memiliki Data Transaksi !',
                ], 405);
            }
            $kas->delete();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil Menghapus Data Kas !',
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e
            ], 400);
        }
    }

    public function kode_kas()
    {
        $check = Kas::select('kode_kas', 'created_at')->orderBy('created_at', 'desc')->first();
        if (!empty($check)) {
            $bulan_last = Carbon::parse($check->created_at)->format('m');
            $no_item = $check->kode_kas;
        } else {
            $bulan_last = date('m');
            $no_item = 'KAS'.date('ym').'000';
        }

        if ($bulan_last >= date('m')) {
            $lastNoUrut = (int)substr($no_item, 7, 4);
            $tmp = ((int)$lastNoUrut)+1;
            $kd = 'KAS'.date('ym').sprintf("%03s", $tmp);
        } else {
            $lastNoUrut = (int)substr($no_item, 7, 4);
            $tmp = 000+1;
            $kd = 'KAS'.date('ym').sprintf("%03s", $tmp);
        }
        return $kd;
    }

    public function getkas_autocomplete(Request $request)
    {
        $search = $request->get('query');
        $result = Kas::select(['id', 'kode_kas', 'nama_kas', 'type_kas', 'akun_id'])
                    ->where('nama_kas','LIKE',"%$search%")
                    ->take('5')
                    ->get();
        foreach($result as $data) {
            $output['suggestions'][] = [
                'value'       => $data['kode_kas'].' - '.$data['nama_kas'],
                'id'          => $data['id'],
                'item' => $data,
            ];
        }

        if (! empty($output)) {
            echo json_encode($output);
        }
    }

    public function getkas_select2(Request $request)
    {
        $data = [];
        if($request->has('q')){
            $search = $request->q;
            $data = Kas::where('nama_kas', 'LIKE', '%'.$search.'%')
                        ->when($request->has('status_header') && $request->status_header != "", function($query){
                            $query->where('status_header', '1');
                        })
                        ->when($request->has('status_pembayaran') && $request->status_pembayaran != "", function($query) use ($request){
                            $query->where('status_pembayaran', $request->status_pembayaran);
                        })
                        ->when($request->has('category') && $request->category != "", function($query) use ($request){
                                $query->where('kategori_akun_id', $request->category);
                        })
                        ->get();
        }
        return response()->json($data);
    }
}
