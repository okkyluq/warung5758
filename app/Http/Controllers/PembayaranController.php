<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\Datatables;
use App\DetHistoryJurnal;
use App\PengaturanAkun;
use App\DetPembayaran;
use App\HistoryHutang;
use App\HistoryJurnal;
use App\HistoryKas;
use App\KategoriAkun;
use App\Pembayaran;
use Carbon\Carbon;
use App\Akun;
use File;
use Auth;
use DB;

class PembayaranController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $pembayaran = Pembayaran::select(['id','kode_pembayaran', 'supplier_id', 'kas_id', 'tgl_pembayaran', 'total_hutang', 'total_pembayaran'])
                            ->with(['det_pembayaran', 'supplier'])
                            ->orderBy('created_at', 'desc');

            return Datatables::of($pembayaran)
                ->addIndexColumn()
                ->addColumn('supplier', function($data){
                    return $data->supplier ? $data->supplier->nama_supplier : '-';
                })
                ->editColumn('tgl_pembayaran', function($data){
                    return date('d/M/Y', strtotime($data->tgl_pembayaran));
                })
                ->editColumn('total_pembayaran', function($data){
                    return 'Rp.'.number_format($data->total_pembayaran, 0);
                })
                ->addColumn('action', function($data){
                    return '<ul class="icons-list">
                                <li class="text-info-600"><a href="'.url("keuangan/pembayaran/".$data->id).'"><i class="icon-file-eye"></i></a></li>
                                <li class="text-danger-600"><a id="button_delete" data-id="'.$data->id.'" href="#"><i class="icon-trash"></i></a></li>
                            </ul>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('backoffice.page.pembayaran.index');
    }


    public function create()
    {
        $kategori = KategoriAkun::select(['id', 'no_kategori', 'nama_kategori'])->get();
        return view('backoffice.page.pembayaran.create', [
            'kategori' => $kategori,
            'kode' => $this->no_pembayaran()
        ]);
    }


    public function store(Request $request)
    {
        $request->request->add(['list_item' => json_decode($request->list_item, true)]);
        $rules = [
            'kode_pembayaran' => 'required|unique:pembayaran,kode_pembayaran',
            'tgl_pembayaran' => 'required',
            'list_item' => 'required',
            'list_item.*.data_id' => 'required',
            'list_item.*.jumlah_bayar' => 'required|gt:0|numeric',
            'total_hutang' => 'required|same:total_pembayaran|numeric|gt:0',
            'total_pembayaran' => 'required|same:total_hutang',
            'kas' => 'required',
        ];
        $message = [
            'kode_pembayaran.required' => 'Kode Pembayaran Wajib Di isi',
            'kode_pembayaran.unique' => 'Kode Pembayaran Sudah Digunakan',
            'tgl_pembayaran.required' => 'Tanggal Pembayaran Wajib Di isi',
            'kas.required' => 'Kas Wajib Dipilih',
            'total_hutang.required' => 'Total Hutang Wajib Terisi',
            'total_hutang.same' => 'Total Hutang Harus Sesuai Dengan Total Pembayaran',
            'total_hutang.numeric' => 'Total Hutang Wajib Angka',
            'total_hutang.gt' => 'Total Hutang Tidak Boleh 0',
            'total_pembayaran.required' => 'Total Pembayaran Wajib Terisi',
            'total_pembayaran.same' => 'Total Pembayaran Harus Sesuai Dengan Total Hutang',
            'total_pembayaran.numeric' => 'Total Pembayaran Wajib Angka',
            'total_pembayaran.gt' => 'Total Pembayaran Tidak Boleh 0',
            'list_item.required' => 'Data Pembayaran Minimal 1 ',
        ];
        foreach ($request->list_item as $key => $value) {
            $baris = $key+1;
            $message['list_item.'.$key.'.data_id.required'] = 'Baris Ke-'.$baris.' Item Belum Dipilih ';
            $message['list_item.'.$key.'.jumlah_bayar.required'] = 'Baris Ke-'.$baris.' Jumlah Bayar Wajib Di isi ';
            $message['list_item.'.$key.'.jumlah_bayar.gt'] = 'Baris Ke-'.$baris.' Jumlah Bayar Tidak Boleh 0 ';
            $message['list_item.'.$key.'.jumlah_bayar.numeric'] = 'Baris Ke-'.$baris.' Jumlah Bayar Wajib Angka ';
        }
        $this->validate($request, $rules, $message);

        try {
            DB::beginTransaction();

            $transaksi = Pembayaran::create([
                'kode_pembayaran' => $request->kode_pembayaran,
                'supplier_id' => $request->supplier,
                'kas_id' => $request->kas,
                'tgl_pembayaran' => Carbon::createFromFormat('d/m/Y', $request->tgl_pembayaran)->format('Y-m-d'),
                'total_hutang' => $request->total_hutang,
                'total_pembayaran' => $request->total_pembayaran,
            ]);

            $detail = collect($request->list_item)->transform(function($value, $index){
                $new['pembayaran_id']       = $value['data_id'];
                $new['pembayaran_type']     = $value['data_type'];
                $new['history_hutang_id']   = $value['history_hutang_id'];
                $new['akun_id']             = $value['akun_id'];
                $new['no_ref']              = $value['no_ref'];
                $new['jumlah_bayar']        = $value['jumlah_bayar'];
                $new['keterangan']          = $value['keterangan'];
                return new DetPembayaran($new);
            });

            $transaksi->det_pembayaran()->saveMany($detail);

            // proses mengurangi hutang & ubah status
            foreach (collect($request->list_item) as $key => $value) {
                if($value['data_type'] == 'App\HistoryHutang'){
                    $history = HistoryHutang::where('id', $value['data_id'])->first();
                    $history->increment('terbayar', $value['jumlah_bayar']);
                    $history->decrement('sisa_pembayaran', $value['jumlah_bayar']);
                    if($history->nominal == $history->terbayar) {
                        $history->update([
                            'status_lunas' => '1'
                        ]);
                    }
                }

            }
            // history kas
            $transaksi->history_kas()->save(
                new HistoryKas([
                    'kas_id' => $request->kas,
                    'nominal_debit' => 0,
                    'nominal_kredit' => str_replace(',', '', $request->total_pembayaran),
                ])
            );

            // jurnal pembayaran
            $history_jurnal = new HistoryJurnal([
                'kode_journal' => app('App\Http\Controllers\HistoryJurnalController')->kode_journal(),
                'tgl_set' => Carbon::createFromFormat('d/m/Y', $request->tgl_pembayaran)->format('Y-m-d'),
                'autogen' => '1',
                'total_debit' => 0,
                'total_kredit' => 0,
                'keterangan' => 'Pembayaran '.$transaksi->kode_pembayaran,
            ]);
            $transaksi->history_jurnal()->save($history_jurnal);

            $detail_jurnal = collect($request->list_item)->transform(function($value, $index){
                $new['akun_id']          = $value['akun_id'];
                $new['nominal_debit']    = $value['jumlah_bayar'];
                $new['nominal_kredit']   = 0;
                $new['keterangan']       = $value['keterangan'];
                return new DetHistoryJurnal($new);
            })->push(
                new DetHistoryJurnal([
                    'akun_id' => PengaturanAkun::select(['akun_id'])->where('setting', 'kas')->first()->akun_id,
                    'nominal_debit' => 0,
                    'nominal_kredit' => str_replace(',', '', $request->total_pembayaran),
                    'keterangan' => 'Cara Bayar',
                ])
            );

            $history_jurnal->det_history_jurnal()->saveMany($detail_jurnal);

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
        $pembayaran =  Pembayaran::with(['det_pembayaran', 'supplier'])->findOrFail($id);
        return view('backoffice.page.pembayaran.detail', [
            'pembayaran' => $pembayaran
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
            $pembayaran =  Pembayaran::findOrFail($id);
            foreach ($pembayaran->det_pembayaran as $key => $value) {
                if ($value->pembayaran_type == 'App\HistoryHutang') {
                    $history = HistoryHutang::where('id', $value->history_hutang_id)->first();
                    $history->decrement('terbayar', str_replace(',', '', number_format($value->jumlah_bayar)));
                    $history->increment('sisa_pembayaran', str_replace(',', '', number_format($value->jumlah_bayar)));
                    if($history->nominal != $history->terbayar) {
                        $history->update([
                            'status_lunas' => '0'
                        ]);
                    }

                }
            }
            $pembayaran->history_kas()->delete();
            $pembayaran->history_jurnal()->delete();
            $pembayaran->det_pembayaran()->delete();
            $pembayaran->delete();
            return response()->json(['deleted' => true ]);
        } catch (\Exception $e) {
            return response()->json(['code' => 404, 'message' => $e ], 400);
        }
    }

    public function pencarian_hutang(Request $request)
    {
        $query = HistoryHutang::with(['historyhutangable', 'supplier'])
                    ->where('status_lunas', '0');


        return Datatables::of($query)
                ->editColumn('no_pembelian', function($data){
                    $akun = PengaturanAkun::select(['akun_id'])->where('setting', 'hutang')->first();
                    $datax = collect($data);
                    $datax->put('akun_id', $akun->akun_id);
                    return "<a data-detail='".$datax."' href='' class='btn-get'>".$data->historyhutangable->no_pembelian."</a>";
                })
                ->addColumn('no_ref', function($data){
                    return $data->no_ref ? $data->no_ref : '-';
                })
                ->addColumn('supplier', function($data){
                    return $data->supplier->nama_supplier;
                })
                ->editColumn('tgl_pembelian', function($data){
                    return date('d/M/Y', strtotime($data->historyhutangable->tgl_pembelian));
                })
                ->editColumn('total', function($data){
                    return 'Rp. '.number_format($data->nominal, 0);
                })
                ->addColumn('terbayar', function($data){
                    return 'Rp. '.number_format($data->terbayar, 0);
                })
                ->addColumn('sisa_pembayaran', function($data){
                    return 'Rp. '.number_format($data->sisa_pembayaran, 0);
                })
                ->addColumn('action', function($data){
                    return '
                    <ul class="icons-list">
                        <li class="text-primary-600"><a href="" class="btn-get"><i class="icon-checkbox-checked"></i></a></li>
                    </ul>';
                })
                ->rawColumns(['no_pembelian','action'])
                ->make(true);
    }

    public function pencarian_akun(Request $request)
    {
        $query = Akun::with(['kategori'])
                    ->select(['id', 'kategori_akun_id', 'kode_akun', 'nama_akun', 'status_header', 'status_pembayaran', 'default_saldo', 'parent_id'])
                    ->when($request->has('kode_akun') && $request->kode_akun != "", function($query) use ($request){
                        $query->where('kode_akun', $request->kode_akun);
                    })
                    ->when($request->has('jenis_akun') && $request->jenis_akun != "", function($query) use ($request){
                        $query->where('kategori_akun_id', $request->jenis_akun);
                    })
                    ->when($request->has('nama_akun') && $request->nama_akun != "", function($query) use ($request){
                        $query->where('nama_akun', 'like', '%'.$request->nama_akun.'%');
                    });

        return Datatables::of($query)
                    ->addColumn('kategori', function($data){
                        return "<a data-detail='".$data."' href='' class='btn-get'>".$data->kategori->nama_kategori."</a>";
                    })
                    ->addColumn('action', function($data){
                        return '
                        <ul class="icons-list">
                            <li class="text-primary-600"><a href="" class="btn-get"><i class="icon-checkbox-checked"></i></a></li>
                        </ul>';
                    })
                    ->rawColumns(['kategori','action'])
                    ->make(true);

    }

    public function no_pembayaran()
    {
        $check = Pembayaran::select('kode_pembayaran', 'created_at')->orderBy('created_at', 'desc')->first();
        if (!empty($check)) {
            $bulan_last = Carbon::parse($check->created_at)->format('m');
            $kode_pembayaran = $check->kode_pembayaran;
        } else {
            $bulan_last = date('m');
            $kode_pembayaran = 'BYR'.date('ym').'000';
        }
        // BYR21070001

        if ($bulan_last >= date('m')) {
            $lastNoUrut = (int)substr($kode_pembayaran, 7, 3);
            $tmp = ((int)$lastNoUrut)+1;
            $kd = 'BYR'.date('ym').sprintf("%03s", $tmp);
        } else {
            $lastNoUrut = (int)substr($kode_pembayaran, 7, 3);
            $tmp = 000+1;
            $kd = 'BYR'.date('ym').sprintf("%03s", $tmp);
        }
        return $kd;
    }
}
