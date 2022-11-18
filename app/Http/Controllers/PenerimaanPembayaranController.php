<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\Datatables;
use App\Services\PenerimaanPembayaranService;
use App\Http\Requests\PenerimaanPembayaranCreate;
use App\PengaturanAkun;
use App\DetPenerimaanPembayaran;
use App\HistoryPiutang;
use App\KategoriAkun;
use App\PenerimaanPembayaran;
use Carbon\Carbon;
use App\Akun;
use DB;

class PenerimaanPembayaranController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()){
            $epenerimaan = PenerimaanPembayaran::select(['id','kode_penerimaan_pembayaran', 'costumer_id', 'tgl_penerimaan_pembayaran', 'total_piutang', 'total_penerimaan_pembayaran', 'kas_id', 'keterangan'])
                            ->with(['det_penerimaan_pembayaran', 'costumer'])
                            ->orderBy('created_at', 'desc');

            return Datatables::of($epenerimaan)
                ->addIndexColumn()
                ->addColumn('costumer', function($data){
                    return $data->costumer ? $data->costumer->nama_costumer : '-';
                })
                ->editColumn('tgl_penerimaan_pembayaran', function($data){
                    return date('d/M/Y', strtotime($data->tgl_penerimaan_pembayaran));
                })
                ->editColumn('total_penerimaan_pembayaran', function($data){
                    return 'Rp.'.number_format($data->total_penerimaan_pembayaran, 0);
                })
                ->addColumn('action', function($data){
                    return '<ul class="icons-list">
                                <li class="text-info-600"><a href="'.url("keuangan/penerimaan-pembayaran/".$data->id).'"><i class="icon-file-eye"></i></a></li>
                                <li class="text-danger-600"><a id="button_delete" data-id="'.$data->id.'" href="#"><i class="icon-trash"></i></a></li>
                            </ul>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('backoffice.page.penerimaan_pembayaran.index');
    }


    public function create()
    {
        $kategori = KategoriAkun::select(['id', 'no_kategori', 'nama_kategori'])->get();
        return view('backoffice.page.penerimaan_pembayaran.create', [
            'kategori' => $kategori,
            'kode' => $this->no_pembayaran()
        ]);
    }


    public function store(PenerimaanPembayaranCreate $request, PenerimaanPembayaranService $penerimaanPembayaranService)
    {
        // return response()->json($request->all());
        try {
            DB::beginTransaction();

            $transaksi = PenerimaanPembayaran::create([
                'kode_penerimaan_pembayaran' => $request->kode_pembayaran,
                'costumer_id' => $request->costumer,
                'tgl_penerimaan_pembayaran' => Carbon::createFromFormat('d/m/Y', $request->tgl_penerimaan)->format('Y-m-d'),
                'total_piutang' => $request->total_piutang,
                'total_penerimaan_pembayaran' => $request->total_pembayaran,
                'kas_id' => $request->kas,
                'keterangan' => $request->keterangan,
            ]);

            $detail = collect($request->list_item)->transform(function($value, $index){
                $new['history_piutang_id']         = $value['history_hutang_id'];
                $new['akun_id']                    = $value['akun_id'];
                $new['penerimaan_pembayaran_type'] = $value['data_type'];
                $new['no_ref']                     = $value['no_ref'];
                $new['jumlah_bayar']               = $value['jumlah_bayar'];
                $new['keterangan']                 = $value['keterangan'];
                return new DetPenerimaanPembayaran($new);
            });

            $transaksi->det_penerimaan_pembayaran()->saveMany($detail);

            $penerimaanPembayaranService->updateHistoryPiutang($request->all(), $transaksi);
            $penerimaanPembayaranService->insertHistoryKas($request->all(), $transaksi);
            $penerimaanPembayaranService->insertJurnalPenerimaanPembayaran($request->all(), $transaksi);

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
        $penerimaan =  PenerimaanPembayaran::with(['det_penerimaan_pembayaran', 'costumer'])->findOrFail($id);
        return view('backoffice.page.penerimaan_pembayaran.detail', [
            'penerimaan' => $penerimaan
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
            $penerimaan =  PenerimaanPembayaran::findOrFail($id);
            foreach ($penerimaan->det_penerimaan_pembayaran as $key => $value) {
                if ($value->penerimaan_pembayaran_type == 'App\HistoryPiutang') {
                    $history = HistoryPiutang::where('id', $value->history_piutang_id)->first();
                    $history->decrement('terbayar', str_replace(',', '', number_format($value->jumlah_bayar)));
                    $history->increment('sisa_pembayaran', str_replace(',', '', number_format($value->jumlah_bayar)));
                    if($history->nominal != $history->terbayar) {
                        $history->update([
                            'status_lunas' => '0'
                        ]);
                    }

                }
            }
            $penerimaan->history_kas()->delete();
            $penerimaan->history_jurnal()->delete();
            $penerimaan->det_penerimaan_pembayaran()->delete();
            $penerimaan->delete();
            DB::commit();
            return response()->json(['deleted' => true ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['code' => 404, 'message' => $e ], 400);
        }
    }


    // public function print($id)
    // {
    //     $penerimaan =  PenerimaanPembayaran::findOrFail($id);
    //     Fpdf::SetTitle('Penerimaan pembayaran '.$penerimaan->kode_penerimaan_pembayaran, false);
    //     Fpdf::AddPage('L', array(215, 139));
    //     Fpdf::SetMargins(10, 50, 20);
    //     Fpdf::SetAutoPageBreak(false);
    //     Fpdf::SetFont('Helvetica','B',12);
    //     Fpdf::Cell(94,6,"JURNAL UMUM", 0,0,'L');
    //     Fpdf::SetFont('Helvetica','BI',12);
    //     Fpdf::Cell(94,6,"WARUNG MAKAN 5758", 0,1,'R');
    //     Fpdf::Line(10, 16, 198, 16);
    //     Fpdf::Line(10, 16.5, 198, 16.5);
    //     Fpdf::setY(18);
    //     Fpdf::SetFont('Helvetica','B',9);
    //     Fpdf::Cell(30,5,"Kode Jurnal", 0,0,'L');
    //     Fpdf::Cell(3,5,":", 0,0,'L');
    //     Fpdf::Cell(30,5, '$history->kode_journal', 0,1,'L');
    //     Fpdf::Cell(30,5,"Hari/Tanggal", 0,0,'L');
    //     Fpdf::Cell(3,5,":", 0,0,'L');
    //     Fpdf::Cell(30,5, "", 0,1,'L');
    //     Fpdf::Cell(30,5,"Tipe Jurnal", 0,0,'L');
    //     Fpdf::Cell(3,5,":", 0,0,'L');
    //     Fpdf::Cell(30,5, "", 0,1,'L');
    //     Fpdf::ln();
    //     Fpdf::SetFont('Helvetica','B',9);
    //     Fpdf::SetFillColor(34, 120, 169);
    //     Fpdf::SetTextColor(255, 255, 255);
    //     Fpdf::SetDrawColor(34, 120, 169);
    //     Fpdf::Cell(10,8,"No.", 1,0,'C', 1);
    //     Fpdf::Cell(20,8,"No Akun", 1,0, 'C', 1);
    //     Fpdf::Cell(45,8,"Nama Akun", 1,0, 'L', 1);
    //     Fpdf::Cell(30,8,"Debit", 1,0, 'R', 1);
    //     Fpdf::Cell(30,8,"Kredit", 1,0, 'R', 1);
    //     Fpdf::Cell(53,8,"Keterangan", 1,0, 'C', 1);
    //     Fpdf::ln();
    //     Fpdf::SetFillColor(221, 236, 255);
    //     Fpdf::SetTextColor(0, 0, 0);
    //     Fpdf::SetDrawColor(221, 236, 255);
    //     // $no = 1;
    //     // foreach ($history->det_history_jurnal as $isi) {
    //     //     Fpdf::SetFont('Helvetica','',9);
    //     //     Fpdf::Cell(10,8, $no++, 1,0,'C', 1);
    //     //     Fpdf::Cell(20,8, $isi->akun->kode_akun, 1,0, 'C', 1);
    //     //     Fpdf::Cell(45,8, $isi->akun->nama_akun, 1,0, 'L', 1);
    //     //     Fpdf::Cell(30,8, 'Rp.'.number_format($isi->nominal_debit, 0), 1,0, 'R', 1);
    //     //     Fpdf::Cell(30,8, 'Rp.'.number_format($isi->nominal_kredit, 0), 1,0, 'R', 1);
    //     //     Fpdf::Cell(53,8, $isi->keterangan, 1,0, 'L', 1);
    //     //     Fpdf::ln();
    //     // }
    //     // Fpdf::SetFont('Helvetica','B',9);
    //     // Fpdf::SetFillColor(34, 120, 169);
    //     // Fpdf::SetTextColor(255, 255, 255);
    //     // Fpdf::SetDrawColor(34, 120, 169);
    //     // Fpdf::Cell(75,8,"TOTAL", 1,0, 'C', 1);
    //     // Fpdf::Cell(30,8, 'Rp.'.number_format($history->det_history_jurnal->sum('nominal_debit'), 0), 1,0, 'R', 1);
    //     // Fpdf::Cell(30,8, 'Rp.'.number_format($history->det_history_jurnal->sum('nominal_debit'), 0), 1,0, 'R', 1);
    //     // Fpdf::Cell(53,8, "", 1,0, 'L', 1);

    //     Fpdf::Output('Jurnal Umum.pdf', 'I', true);


    //     exit;


    // }

    public function pencarian_piutang(Request $request)
    {
        $query = HistoryPiutang::with(['historypiutangable', 'costumer'])
                    ->where('status_lunas', '0');


        return Datatables::of($query)
                ->editColumn('no_penjualan', function($data){
                    $akun = PengaturanAkun::select(['akun_id'])->where('setting', 'piutang')->first();
                    $datax = collect($data);
                    $datax->put('akun_id', $akun->akun_id);
                    return "<a data-detail='".$datax."' href='' class='btn-get'>".$data->historypiutangable->no_penjualan."</a>";
                })
                ->addColumn('no_ref', function($data){
                    return $data->no_ref ? $data->no_ref : '-';
                })
                ->addColumn('costumer', function($data){
                    return $data->costumer->nama_costumer;
                })
                ->editColumn('tgl_penjualan', function($data){
                    return date('d/M/Y', strtotime($data->historypiutangable->tgl_penjualan));
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
                ->rawColumns(['no_penjualan','action'])
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
        $check = PenerimaanPembayaran::select('kode_penerimaan_pembayaran', 'created_at')->orderBy('created_at', 'desc')->first();
        if (!empty($check)) {
            $bulan_last = Carbon::parse($check->created_at)->format('m');
            $kode_penerimaan_pembayaran = $check->kode_penerimaan_pembayaran;
        } else {
            $bulan_last = date('m');
            $kode_penerimaan_pembayaran = 'PBYR'.date('ym').'000';
        }
        // PBYR21070001

        if ($bulan_last >= date('m')) {
            $lastNoUrut = (int)substr($kode_penerimaan_pembayaran, 8, 3);
            $tmp = ((int)$lastNoUrut)+1;
            $kd = 'PBYR'.date('ym').sprintf("%03s", $tmp);
        } else {
            $lastNoUrut = (int)substr($kode_penerimaan_pembayaran, 8, 3);
            $tmp = 000+1;
            $kd = 'PBYR'.date('ym').sprintf("%03s", $tmp);
        }
        return $kd;
    }
}
