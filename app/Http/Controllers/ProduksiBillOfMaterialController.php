<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\Datatables;
use App\Satuan;
use App\SatuanItem;
use App\BillOfMaterial;
use App\ProduksiBom;
use App\DetProduksiBom;
use App\HistoryItem;
use Carbon\Carbon;
use Auth;
use DB;
use File;
use Fpdf;


class ProduksiBillOfMaterialController extends Controller
{
   
    public function index(Request $request)
    {
        if($request->ajax()){
            $produksi = ProduksiBom::select(['id', 'no_produksi', 'bom_id', 'qty', 'tgl_produksi'])
            ->with(['bom.item'])
            ->orderBy('created_at', 'desc');

            return Datatables::of($produksi)
                ->addColumn('item', function($data){
                    return $data->bom->item->nama_item;
                })
                ->editColumn('tgl_produksi', function($data){
                    return Carbon::createFromFormat('Y-m-d', $data->tgl_produksi)->format('l, d M Y h:i');
                })
                ->editColumn('qty', function($data){
                    return number_format($data->qty).' '.$data->bom->satuan_item->satuan->satuan;
                })
                ->addColumn('action', function($data){
                    return '<ul class="icons-list">
                                <li class="text-info-600"><a target="_blank" href="'.url("produksi/produksi-bom/".$data->id)."/cetak".'"><i class="icon-printer2"></i></a></li>
                                <li class="text-danger-600"><a id="button_delete" data-id="'.$data->id.'" href="#"><i class="icon-trash"></i></a></li>
                            </ul>';
                })
                ->rawColumns(['action']) 
                ->make(true);
        }

        return view('backoffice.page.produksi_bom.index');
    }

    
    public function create()
    {
        return view('backoffice.page.produksi_bom.create', [
            'no_produksi' => $this->no_produksi()
        ]);
    }

    
    public function store(Request $request)
    {

        $request->request->add(['list_bahan' => json_decode($request->list_bahan, true)]);
        $rules = [
            'no_produksi' => 'required|unique:produksi_bom,no_produksi',
            'tgl_produksi' => 'required|date',
            'bom_id' => 'required',
            'qty' => 'required|gt:0',
            'list_bahan' => 'required',
            'list_bahan.*.item_id' => 'required',
            'list_bahan.*.satuan' => 'required',
            'list_bahan.*.qty' => 'required|gt:0',
        ];
        $message = [
            'no_produksi.required' => 'No Produksi Wajib Di isi',
            'no_produksi.unique' => 'No Produksi Sudah Digunakan',
            'bom_id.required' => 'BOM Belum Dipilih',
            'tgl_produksi.required' => 'Tanggal Produksi Wajib Di isi',
            'qty.required' => 'Qty Belum DIisi',
            'list_bahan.required' => 'Item Minimal 1 ',
        ];

        foreach ($request->list_bahan as $key => $value) {
            $baris = $key+1;
            $message['list_bahan.'.$key.'.item_id.required'] = 'Baris Ke-'.$baris.' Item Belum Dipilih ';
            $message['list_bahan.'.$key.'.satuan.required'] = 'Baris Ke-'.$baris.' Satuan Belum Diset ';
            $message['list_bahan.'.$key.'.qty.required'] = 'Baris Ke-'.$baris.' Qty Tidak Boleh Kosong ';
            $message['list_bahan.'.$key.'.qty.gt'] = 'Baris Ke-'.$baris.' Qty Tidak Boleh Kosong ';
        }

        $this->validate($request, $rules, $message);

        try {
            DB::beginTransaction();
             
            $produksi = ProduksiBom::create([
                'no_produksi' => $request->no_produksi,
                'tgl_produksi' => date('Y-m-d', strtotime($request->tgl_produksi)),
                'bom_id' => $request->bom_id,
                'qty' => $request->qty,
                'user_id' => Auth::user()->id
            ]);
    
            $detail = collect($request->list_bahan)->transform(function($value, $index){
                $satuan = Satuan::where('satuan', $value['satuan'])->first();
                $satuan_item = SatuanItem::where('item_id', $value['item_id'])->where('satuan_id', $satuan->id)->first();
                $new['item_id']           = $value['item_id'];
                $new['qty']               = $value['qty'];
                $new['satuan_item_id']    = $satuan_item->id;
                return new DetProduksiBom($new);
            }); 

            $produksi->det_produksi_bom()->saveMany($detail);
            
            $det_history_bahan = collect($request->list_bahan)->transform(function($value, $index){
                $satuan = Satuan::where('satuan', $value['satuan'])->first();
                $satuan_item = SatuanItem::where('item_id', $value['item_id'])->where('satuan_id', $satuan->id)->first();
                $new['item_id']           = $value['item_id'];
                $new['qty']               = 0 - $value['qty'];
                $new['satuan_item_id']    = $satuan_item->id;
                $new['status_in_out']     = '0';
                return new HistoryItem($new);
            }); 

            // buat insert history item
            $produksi->history_item()->saveMany($det_history_bahan);

            // insert hasil produksi ke history
            $get_item_id_of_bom = BillOfMaterial::where('id', $request->bom_id)->first(); 
            $det_history_produksi = new HistoryItem;
            $det_history_produksi->item_id = $get_item_id_of_bom->item_id;
            $det_history_produksi->qty = $request->qty;
            $det_history_produksi->satuan_item_id = $request->satuan_item_id;
            $det_history_produksi->status_in_out = '1';
            $det_history_produksi->historyable_id = $produksi->id;
            $det_history_produksi->historyable_type = 'App\ProduksiBom';
            $det_history_produksi->save();

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
        $produksi = ProduksiBom::findOrFail($id);
        return view('backoffice.page.produksi_bom.edit', ['produksi' =>  $produksi]);
    }

    
    public function update(Request $request, $id)
    {
        //
    }

    
    public function destroy($id)
    {
        $produksi = ProduksiBom::findOrFail($id);
        try {
            DB::beginTransaction();
            $produksi->history_item()->delete();
            $produksi->delete();
             
            DB::commit();  
            return response()->json(['code' => 404, 'message' => $e ], 400);
        } catch (\Exception $th) {
            DB::commit();  
            return response()->json(['deleted' => true ]); 
        }
    }

    public function no_produksi()
    {
        $check = ProduksiBom::select('no_produksi', 'created_at')->orderBy('created_at', 'desc')->first();
        if (!empty($check)) {
            $bulan_last = Carbon::parse($check->created_at)->format('m');
            $no_produksi = $check->no_produksi;
        } else {
            $bulan_last = date('m');
            $no_produksi = 'PM'.date('ym').'000';
        }

        if ($bulan_last >= date('m')) {
            $lastNoUrut = (int)substr($no_produksi, 6, 3);
            $tmp = ((int)$lastNoUrut)+1;
            $kd = 'PM'.date('ym').sprintf("%03s", $tmp);
        } else {
            $lastNoUrut = (int)substr($no_produksi, 6, 3);
            $tmp = 000+1;
            $kd = 'PM'.date('ym').sprintf("%03s", $tmp);
        }
        return $kd;
    }

    public function cetak(Request $request, $id)
    {
        $produksi = ProduksiBom::findOrFail($id);
        Fpdf::SetTitle('Laporan Detail Produksi '.$produksi->no_produksi, false);
        Fpdf::AddPage('P', "A4");
        Fpdf::SetMargins(10, 50, 20);
        Fpdf::SetAutoPageBreak(false);
        Fpdf::SetFont('Helvetica','B',12);
        Fpdf::Cell(94,6,"DETAIL PRODUKSI", 0,0,'L');
        Fpdf::SetFont('Helvetica','BI',12);
        Fpdf::Cell(94,6,"WARUNG MAKAN 5758", 0,1,'R');
        Fpdf::Line(10, 16, 198, 16);
        Fpdf::Line(10, 16.5, 198, 16.5);
        Fpdf::setY(18);
        Fpdf::SetFont('Helvetica','B',9);
        Fpdf::Cell(30,5,"No. Produksi", 0,0,'L');
        Fpdf::Cell(3,5,":", 0,0,'L');
        Fpdf::Cell(30,5, $produksi->no_produksi, 0,1,'L');
        Fpdf::Cell(30,5,"B.o.M", 0,0,'L');
        Fpdf::Cell(3,5,":", 0,0,'L');
        Fpdf::Cell(30,5, $produksi->bom->item->nama_item, 0,1,'L');
        Fpdf::Cell(30,5,"Tgl Produksi", 0,0,'L');
        Fpdf::Cell(3,5,":", 0,0,'L');
        Fpdf::Cell(30,5, $produksi->created_at->format('d/m/Y'), 0,1,'L');
        Fpdf::ln();
        Fpdf::SetFont('Helvetica','B',9);
        Fpdf::SetFillColor(34, 120, 169);
        Fpdf::SetTextColor(255, 255, 255);
        Fpdf::SetDrawColor(0, 0, 0);
        Fpdf::Cell(193,8,"BAHAN", 1,1,'L', 1);
        Fpdf::Cell(10,8,"No.", 1,0,'C', 1);
        Fpdf::Cell(78,8,"Nama Bahan.", 1,0, 'L', 1);
        Fpdf::Cell(50,8,"Qty", 1,0, 'C', 1);
        Fpdf::Cell(55,8,"Satuan", 1,1, 'C', 1);
        Fpdf::SetFillColor(221, 236, 255);
        Fpdf::SetTextColor(0, 0, 0);
        Fpdf::SetDrawColor(0, 0, 0);
        $no = 1;
        foreach ($produksi->det_produksi_bom as $isi) {
            Fpdf::SetFont('Helvetica','',9);
            Fpdf::Cell(10,7, $no++, 1,0,'C', 1);
            Fpdf::Cell(78,7, $isi->item->nama_item, 1,0, 'L', 1);
            Fpdf::Cell(50,7, number_format($isi->qty, 0), 1,0, 'C', 1);
            Fpdf::Cell(55,7, $isi->satuan_item->satuan->satuan, 1,0, 'C', 1);
            Fpdf::ln();
        }
        Fpdf::SetFont('Helvetica','B',9);
        Fpdf::SetFillColor(34, 120, 169);
        Fpdf::SetTextColor(255, 255, 255);
        Fpdf::SetDrawColor(0, 0, 0);
        Fpdf::Cell(193,8,"PRODUKSI", 1,1,'L', 1);
        Fpdf::Cell(10,8,"No.", 1,0,'C', 1);
        Fpdf::Cell(78,8,"Nama Item.", 1,0, 'L', 1);
        Fpdf::Cell(50,8,"Qty", 1,0, 'C', 1);
        Fpdf::Cell(55,8,"Satuan", 1,1, 'C', 1);
        Fpdf::SetFillColor(221, 236, 255);
        Fpdf::SetTextColor(0, 0, 0);
        Fpdf::SetDrawColor(0, 0, 0);
        Fpdf::SetFont('Helvetica','',9);
        Fpdf::Cell(10,7, '1', 1,0,'C', 1);
        Fpdf::Cell(78,7, $produksi->bom->item->nama_item, 1,0, 'L', 1);
        Fpdf::Cell(50,7, number_format($produksi->qty, 0), 1,0, 'C', 1);
        Fpdf::Cell(55,7, $produksi->bom->satuan_item->satuan->satuan, 1,0, 'C', 1);
        Fpdf::ln();
        Fpdf::ln();
        Fpdf::SetFont('Helvetica','B',10);
        Fpdf::Cell(120.3,6, '',0,0,'C');
        Fpdf::Cell(68.3,6, 'Kasir',0,1,'C');
        Fpdf::ln();
        Fpdf::ln();
        Fpdf::SetFont('Helvetica','BU',10);
        Fpdf::Cell(120.3,6, "",0,0,'C');
        Fpdf::Cell(68.3,6, $produksi->user->name,0,1,'C');


        Fpdf::Output('Produksi-bom.pdf', 'I', true);
        

        exit;
    }
}
