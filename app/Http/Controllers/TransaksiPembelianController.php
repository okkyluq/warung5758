<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransaksiPembelianRequest;
use App\Services\TransaksiPembelianService;
use Yajra\DataTables\Facades\Datatables;
use App\DetTransaksiPembelian;
use Illuminate\Http\Request;
use App\TransaksiPembelian;
use App\PengaturanSistem;
use App\HistoryItem;
use App\SatuanItem;
use Carbon\Carbon;
use App\Satuan;
use Auth;
use Fpdf;
use DB;

class TransaksiPembelianController extends Controller
{

    public function index(Request $request)
    {
        if($request->ajax()){
            $transaksi = TransaksiPembelian::select(['no_pembelian', 'supplier_id', 'tgl_pembelian', 'id'])
                        ->with(['supplier', 'det_transaksi_pembelian'])
                        ->when($request->has('supplier') AND $request->supplier != '', function($query) use ($request){
                            $query->where('supplier_id', $request->supplier);
                        })
                        ->when($request->has('tgl') AND $request->tgl != '', function($query) use ($request){
                            $tgl = explode(' - ', $request->tgl);
                            $tgl_start = Carbon::createFromFormat('d/m/Y', $tgl[0])->format('Y-m-d');
                            $tgl_end = Carbon::createFromFormat('d/m/Y', $tgl[1])->format('Y-m-d');
                            $query->whereBetween('tgl_pembelian', [$tgl_start, $tgl_end]);
                        })
                        ->orderBy('created_at', 'desc');

             return Datatables::of($transaksi)
                ->addColumn('supplier', function($data){
                    return $data->supplier->nama_supplier;
                })
                ->editColumn('tgl_pembelian', function($data){
                    return date('d/M/Y', strtotime($data->tgl_pembelian)).' '.date('h:i A', strtotime($data->tgl_pembelian));
                })
                ->addColumn('total', function($data){
                    return "Rp. ".number_format($data->det_transaksi_pembelian->sum('sub_total'));
                })
                ->addColumn('action', function($data){
                    return '<ul class="icons-list">
                                <li class="text-info-600"><a href="'.url("pembelian/".$data->id)."/print".'" target="_blank"><i class="icon-printer2"></i></a></li>
                                <li class="text-danger-600"><a id="button_delete" data-id="'.$data->id.'" href="#"><i class="icon-trash"></i></a></li>
                            </ul>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('backoffice.page.transaksi_pembelian.index');
    }


    public function create()
    {
        $kode = $this->no_pembelian();
        return view('backoffice.page.transaksi_pembelian.create', [
            'kode' => $kode,
            'supplier_default' => PengaturanSistem::where('setting', 'supplier_default')->pluck('value')->first()
        ]);
    }


    public function store(TransaksiPembelianRequest $request, TransaksiPembelianService $transaksiPembelianService)
    {
        try {
            DB::beginTransaction();
            $transaksi = TransaksiPembelian::create([
                'no_pembelian' => $request->no_pembelian,
                'tgl_pembelian' => Carbon::createFromFormat('d/m/Y', $request->tgl_pembelian)->format('Y-m-d'),
                'supplier_id' => $request->supplier,
                'user_id' => Auth::user()->id,
                'termin' => $request->termin,
                'kas_id' => $request->kas,
                'total' => str_replace(',', '', $request->total),
                'jumlah_hari_tempo' => $request->hari_jatuh_tempo,
                'tgl_jatuh_tempo' => $request->tgl_jatuh_tempo != '' ? Carbon::createFromFormat('d/m/Y', $request->tgl_pembelian)->addDays($request->hari_jatuh_tempo)->format('Y-m-d') : null,
                'uang_muka' => !empty($request->uang_muka) ? str_replace(',', '', $request->uang_muka) : null,
                'keterangan' => $request->keterangan,
            ]);
            $det_transaksi_pembelian = collect($request->list_item)->transform(function($value, $index){
                $new = [
                    'item_id'        => $value['item_id'],
                    'qty'            => $value['qty'],
                    'satuan_item_id' => $value['satuan'],
                    'status_in_out'  => '1',
                    'harga'          => $value['harga'],
                    'sub_total'      => $value['harga'] * $value['qty'],
                ];
                return new DetTransaksiPembelian($new);
            });
            $transaksi->det_transaksi_pembelian()->saveMany($det_transaksi_pembelian);
            $transaksiPembelianService->insertHistoryItem($request->all(), $transaksi);

            $transaksiPembelianService->insertHistoryHutang($request->all(), $transaksi);
            $transaksiPembelianService->insertHistoryKas($request->all(), $transaksi);
            // history jurnal 1.Cash , 2.Credit
            if($request->termin == '2') {
                // pembelian secara credit
                $transaksiPembelianService->insertJurnalTransaksiPembelianCredit($request->all(), $transaksi);
                if (str_replace(',', '', $request->uang_muka) != "") {
                    $transaksiPembelianService->autoInsertPembayaran($request->all(), $transaksi);
                    $transaksiPembelianService->autoInsertJurnalPembayaran($request->all(), $transaksi);
                }
            } else {
                // pembelian secara cash
                $transaksiPembelianService->insertJurnalTransaksiPembelianCash($request->all(), $transaksi);
            }
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
        $transaksi =  TransaksiPembelian::with(['supplier', 'det_transaksi_pembelian.satuan_item', 'det_transaksi_pembelian.item.satuan_item.satuan'])->findOrFail($id);
        return view('backoffice.page.transaksi_pembelian.edit', ['transaksi' => $transaksi]);
    }


    public function update(Request $request, $id)
    {
        $request->request->add(['list_item' => json_decode($request->list_item, true)]);
        $rules = [
            'no_pembelian' => 'required|unique:transaksi_pembelian,no_pembelian,'.$id,
            'tgl_pembelian' => 'required|date',
            'supplier' => 'required',
            'list_item' => 'required',
            'list_item.*.item_id' => 'required',
            'list_item.*.satuan' => 'required',
            'list_item.*.qty' => 'required|gt:0',
            'list_item.*.harga' => 'required|gt:0',
        ];
        $message = [
            'no_pembelian.required' => 'No Pembelian Wajib Di isi',
            'no_pembelian.unique' => 'No Pembelian Sudah Digunakan',
            'supplier.required' => 'Supplier Belum Dipilih',
            'tgl_pembelian.required' => 'Tanggal Masuk Wajib Di isi',
            'list_item.required' => 'Item Minimal 1 ',
        ];
        foreach ($request->list_item as $key => $value) {
            $baris = $key+1;
            $message['list_item.'.$key.'.item_id.required'] = 'Baris Ke-'.$baris.' Item Belum Dipilih ';
            $message['list_item.'.$key.'.satuan.required'] = 'Baris Ke-'.$baris.' Satuan Belum Diset ';
            $message['list_item.'.$key.'.qty.required'] = 'Baris Ke-'.$baris.' Qty Tidak Boleh Kosong ';
            $message['list_item.'.$key.'.qty.gt'] = 'Baris Ke-'.$baris.' Qty Tidak Boleh Kosong ';
            $message['list_item.'.$key.'.harga.required'] = 'Baris Ke-'.$baris.' Harga Wajib Di isi ';
            $message['list_item.'.$key.'.harga.gt'] = 'Baris Ke-'.$baris.' Item Harus Memiliki Harga ';
        }
        $this->validate($request, $rules, $message);

        $transaksi = TransaksiPembelian::findOrFail($id);

        try {
            DB::beginTransaction();

            $transaksi->update([
                'no_pembelian' => $request->no_pembelian,
                'tgl_pembelian' => date('Y-m-d', strtotime($request->tgl_pembelian)),
                'supplier_id' => $request->supplier,
                'user_id' => Auth::user()->id
            ]);

            $itemIds = [];
            $itemIdh = [];

            foreach ($request->list_item as $isi) {
                $satuan = Satuan::where('satuan', $isi['satuan'])->first();
                $satuan_item = SatuanItem::where('item_id', $isi['item_id'])->where('satuan_id', $satuan->id)->first();

                if(isset($isi["id"]) && $isi["id"] != "") {

                    $itemIds[] = $isi['id'];
                    $itemIdh[] = $isi['item_id'];

                    $transaksi->det_transaksi_pembelian()->whereId($isi["id"])->update([
                        'item_id' => $isi["item_id"],
                        'qty' => $isi["qty"],
                        'satuan_item_id'=> $satuan_item->id,
                        'harga' => $isi["harga"],
                        'sub_total' => $isi["qty"] * $isi["harga"],
                    ]);

                    $transaksi->history_item()
                    ->where('item_id', $isi["item_id"])
                    ->where('historyable_id', $transaksi->id)
                    ->where('historyable_type', 'App\TransaksiPembelian')
                    ->update([
                       'item_id' => $isi['item_id'],
                       'satuan_item_id' => $satuan_item->id,
                       'qty' => 0 - $isi['qty'],
                       'status_in_out' => '0'
                    ]);

                } else {
                    $det_transaksi = new DetTransaksiPembelian;
                    $det_transaksi->item_id = $isi["item_id"];
                    $det_transaksi->qty = $isi["qty"];
                    $det_transaksi->satuan_item_id = $satuan_item->id;
                    $det_transaksi->harga = $isi["harga"];
                    $det_transaksi->sub_total = $isi["qty"] * $isi["harga"];
                    $transaksi->det_transaksi_pembelian()->save($det_transaksi);


                    $history = new HistoryItem;
                    $history->item_id = $isi["item_id"];
                    $history->satuan_item_id = $satuan_item->id;
                    $history->qty = 0 - $isi["qty"];
                    $history->status_in_out = '0';
                    $transaksi->history_item()->save($history);

                    $itemIds[] = $det_transaksi->id;
                    $itemIdh[] = $history->item_id;
                }

            }

            $transaksi->det_transaksi_pembelian()->where('transaksi_pembelian_id', $transaksi->id)->whereNotIn('id', $itemIds)->delete();

            $transaksi->history_item()->where('historyable_id', $transaksi->id)->where('historyable_type', 'App\TransaksiPembelian')
            ->whereNotIn('item_id', $itemIdh)->delete();


            DB::commit();
            return response()->json([
                'status' => 'sukses',
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e, 400);
        }
    }


    public function destroy($id)
    {
        $transaksi =  TransaksiPembelian::findOrFail($id);

        try {
            $transaksi->history_item()->delete();
            $transaksi->history_hutang()->delete();
            $transaksi->history_kas()->delete();
            $transaksi->history_jurnal()->delete();
            $transaksi->delete();
            return response()->json(['deleted' => true ]);
        } catch (\Exception $e) {
            return response()->json(['code' => 404, 'message' => $e ], 400);
        }
    }

    public function no_pembelian()
    {
        $check = TransaksiPembelian::select('no_pembelian', 'created_at')->orderBy('created_at', 'desc')->first();
        if (!empty($check)) {
            $bulan_last = Carbon::parse($check->created_at)->format('m');
            $no_pembelian = $check->no_pembelian;
        } else {
            $bulan_last = date('m');
            $no_pembelian = 'PB'.date('ym').'000';
        }

        if ($bulan_last >= date('m')) {
            $lastNoUrut = (int)substr($no_pembelian, 6, 3);
            $tmp = ((int)$lastNoUrut)+1;
            $kd = 'PB'.date('ym').sprintf("%03s", $tmp);
        } else {
            $lastNoUrut = (int)substr($no_pembelian, 6, 3);
            $tmp = 000+1;
            $kd = 'PB'.date('ym').sprintf("%03s", $tmp);
        }
        return $kd;
    }

    public function cetak(Request $request, $id)
    {
        $transaksi = TransaksiPembelian::with(['det_transaksi_pembelian.item.satuan_item.satuan'])->findOrFail($id);
        Fpdf::SetTitle('Faktur Pembelian '.$transaksi->kode_transaksi, false);
        Fpdf::AddPage('P', "A4");
        Fpdf::SetMargins(10, 50, 20);
        Fpdf::SetAutoPageBreak(false);
        Fpdf::SetFont('Helvetica','B',12);
        Fpdf::Cell(94,6,"FAKTUR PEMBELIAN", 0,0,'L');
        Fpdf::SetFont('Helvetica','BI',12);
        Fpdf::Cell(94,6,"WARUNG MAKAN 5758", 0,1,'R');
        Fpdf::Line(10, 16, 198, 16);
        Fpdf::Line(10, 16.5, 198, 16.5);
        Fpdf::setY(18);
        Fpdf::SetFont('Helvetica','B',9);
        Fpdf::Cell(30,5,"No. Pembelian", 0,0,'L');
        Fpdf::Cell(3,5,":", 0,0,'L');
        Fpdf::Cell(30,5, $transaksi->no_pembelian, 0,1,'L');
        Fpdf::Cell(30,5,"Hari/Tanggal", 0,0,'L');
        Fpdf::Cell(3,5,":", 0,0,'L');
        Fpdf::Cell(30,5, Carbon::setLocale('id')->parse($transaksi->tgl_pembelian)->translatedFormat('l, d M Y'), 0,1,'L');
        Fpdf::Cell(30,5,"Supplier", 0,0,'L');
        Fpdf::Cell(3,5,":", 0,0,'L');
        Fpdf::Cell(30,5, $transaksi->supplier->nama_supplier, 0,1,'L');
        Fpdf::ln();
        Fpdf::SetFont('Helvetica','B',9);
        Fpdf::SetFillColor(34, 120, 169);
        Fpdf::SetTextColor(255, 255, 255);
        Fpdf::SetDrawColor(34, 120, 169);
        Fpdf::Cell(10,8,"No.", 1,0,'C', 1);
        Fpdf::Cell(78,8,"Nama Item.", 1,0, 'L', 1);
        Fpdf::Cell(20,8,"Qty", 1,0, 'C', 1);
        Fpdf::Cell(25,8,"Satuan", 1,0, 'C', 1);
        Fpdf::Cell(25,8,"Harga", 1,0, 'C', 1);
        Fpdf::Cell(30,8,"Sub Total", 1,1, 'C', 1);
        Fpdf::setY(47);
        Fpdf::SetFillColor(221, 236, 255);
        Fpdf::SetTextColor(0, 0, 0);
        Fpdf::SetDrawColor(221, 236, 255);
        for ($i=0; $i <28 ; $i++) {
            Fpdf::Cell(10,7,"", 1,0,'C', 1);
            Fpdf::Cell(78,7,"", 1,0, 'L', 1);
            Fpdf::Cell(20,7,"", 1,0, 'C', 1);
            Fpdf::Cell(25,7,"", 1,0, 'C', 1);
            Fpdf::Cell(25,7,"", 1,0, 'C', 1);
            Fpdf::Cell(30,7,"", 1,0, 'C', 1);
            Fpdf::ln();
        }
        Fpdf::setY(47);
        $no = 1;
        foreach ($transaksi->det_transaksi_pembelian as $isi) {
            Fpdf::SetFont('Helvetica','',9);
            Fpdf::Cell(10,7, $no++, 1,0,'C', 1);
            Fpdf::Cell(78,7, $isi->item->nama_item, 1,0, 'L', 1);
            Fpdf::Cell(20,7, $isi->qty, 1,0, 'C', 1);
            Fpdf::Cell(25,7, $isi->satuan_item->satuan->satuan, 1,0, 'C', 1);
            Fpdf::Cell(25,7, number_format($isi->harga, 0), 1,0, 'R', 1);
            Fpdf::Cell(30,7, number_format($isi->sub_total, 0), 1,0, 'R', 1);
            Fpdf::ln();
        }
        Fpdf::setY(244);
        Fpdf::SetFont('Helvetica','B',9);
        Fpdf::SetFillColor(34, 120, 169);
        Fpdf::SetTextColor(255, 255, 255);
        Fpdf::SetDrawColor(34, 120, 169);
        Fpdf::Cell(158,7, "TOTAL", 1,0,'C', 1);
        Fpdf::Cell(30,7, "Rp. ".number_format($transaksi->det_transaksi_pembelian->sum('sub_total'), 0), 1,1, 'R', 1);
        Fpdf::SetFillColor(255, 255, 255);
        Fpdf::SetTextColor(0, 0, 0);
        Fpdf::SetDrawColor(255, 255, 255);
        Fpdf::Cell(158+30,7, 'Terbilang :'.$this->penyebut($transaksi->det_transaksi_pembelian->sum('sub_total')), 1,1, 'L', 1);


        Fpdf::ln();
        Fpdf::SetFont('Helvetica','B',10);
        Fpdf::Cell(68.3,6, 'Costumer',0,0,'C');
        Fpdf::Cell(68.3,6, "",0,0,'L');
        Fpdf::Cell(68.3,6, 'Kasir',0,1,'C');
        Fpdf::ln();
        Fpdf::ln();
        Fpdf::SetFont('Helvetica','BU',10);
        Fpdf::Cell(68.3,6, $transaksi->supplier->nama_supplier,0,0,'C');
        Fpdf::Cell(68.3,6, "",0,0,'L');
        Fpdf::Cell(68.3,6, $transaksi->user->name,0,1,'C');


        Fpdf::Output('Faktur_pembelian.pdf', 'I', true);


        exit;

    }

    public function penyebut($nilai)
    {
        $nilai = abs($nilai);
        $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        $temp = "";
        if ($nilai < 12) {
            $temp = " ". $huruf[$nilai];
        } else if ($nilai <20) {
            $temp = $this->penyebut($nilai - 10). " belas";
        } else if ($nilai < 100) {
            $temp = $this->penyebut($nilai/10)." puluh". $this->penyebut($nilai % 10);
        } else if ($nilai < 200) {
            $temp = " seratus" . $this->penyebut($nilai - 100);
        } else if ($nilai < 1000) {
            $temp = $this->penyebut($nilai/100) . " ratus" . $this->penyebut($nilai % 100);
        } else if ($nilai < 2000) {
            $temp = " seribu" . $this->penyebut($nilai - 1000);
        } else if ($nilai < 1000000) {
            $temp = $this->penyebut($nilai/1000) . " ribu" . $this->penyebut($nilai % 1000);
        } else if ($nilai < 1000000000) {
            $temp = $this->penyebut($nilai/1000000) . " juta" . $this->penyebut($nilai % 1000000);
        } else if ($nilai < 1000000000000) {
            $temp = $this->penyebut($nilai/1000000000) . " milyar" . $this->penyebut(fmod($nilai,1000000000));
        } else if ($nilai < 1000000000000000) {
            $temp = $this->penyebut($nilai/1000000000000) . " trilyun" . $this->penyebut(fmod($nilai,1000000000000));
        }
        return $temp;
    }
}
