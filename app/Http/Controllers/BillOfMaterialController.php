<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\Datatables;
use App\BillOfMaterial;
use App\DetBillOfMaterial;
use App\Satuan;
use App\SatuanItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Codedge\Fpdf\Facades\Fpdf;


class BillOfMaterialController extends Controller
{

    public function index(Request $request)
    {
        if($request->ajax()){
            $bom = BillOfMaterial::select(['no_bom', 'item_id', 'id'])
                        ->with(['item'])
                        ->orderBy('created_at', 'desc');

             return Datatables::of($bom)
                ->addColumn('item', function($data){
                    return $data->item->nama_item;
                })
                ->addColumn('action', function($data){
                    return '<ul class="icons-list">
                                <li class="text-info-600"><a target="_blank" href="'.url("produksi/bill-of-material/".$data->id)."/cetak".'"><i class="icon-printer2"></i></a></li>
                                <li class="text-primary-600"><a href="'.url("produksi/bill-of-material/".$data->id)."/edit".'"><i class="icon-pencil5"></i></a></li>
                                <li class="text-danger-600"><a id="button_delete" data-id="'.$data->id.'" href="#"><i class="icon-trash"></i></a></li>
                            </ul>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('backoffice.page.bom.index');
    }


    public function create()
    {
        $no_bom = $this->no_bom();
        return view('backoffice.page.bom.create', compact('no_bom'));
    }


    public function store(Request $request)
    {
        $request->request->add(['list_item' => json_decode($request->list_item, true)]);
        $rules = [
            'no_bom' => 'required|unique:bom,no_bom',
            'item_id' => 'required',
            'satuan' => 'required',
            'qty' => 'required',
            'list_item' => 'required',
            'list_item.*.item_id' => 'required',
            'list_item.*.satuan' => 'required',
            'list_item.*.qty' => 'required|gt:0',
            'list_item.*.cost' => 'required|numeric'
        ];
        $message = [
            'no_bom.required' => 'No B.O.M Wajib Di isi',
            'no_bom.unique' => 'No B.O.M Sudah Digunakan',
            'item_id.required' => 'Item Belum Dipilih',
            'satuan.required' => 'Satuan Belum Dipilih',
            'qty.required' => 'Qty Hasil Produksi Belum Disi',
            'list_item.required' => 'Item Minimal 1 ',
        ];
        foreach ($request->list_item as $key => $value) {
            $baris = $key+1;
            $message['list_item.'.$key.'.item_id.required'] = 'Baris Ke-'.$baris.' Item Belum Dipilih ';
            $message['list_item.'.$key.'.satuan.required'] = 'Baris Ke-'.$baris.' Satuan Belum Diset ';
            $message['list_item.'.$key.'.qty.required'] = 'Baris Ke-'.$baris.' Qty Tidak Boleh Kosong ';
            $message['list_item.'.$key.'.cost.required'] = 'Baris Ke-'.$baris.' Cost Tidak Boleh Kosong ';
            $message['list_item.'.$key.'.cost.numeric'] = 'Baris Ke-'.$baris.' Cost Harus Angka';
        }
        $this->validate($request, $rules, $message);

        // return response()->json($request->all());

        try {
            DB::beginTransaction();
            $satuan = Satuan::where('satuan', $request->satuan)->first();
            $satuan_item = SatuanItem::where('item_id', $request->item_id)->where('satuan_id', $satuan->id)->first();

            $bom = BillOfMaterial::create([
                'no_bom' => $request->no_bom,
                'item_id' => $request->item_id,
                'satuan_item_id' => $satuan_item->id,
                'qty' => $request->qty,
                'total_cost' => str_replace(',', '', $request->total_cost),
                'user_id' => Auth::user()->id
            ]);

            $detail = collect($request->list_item)->transform(function($value, $index){
                $satuan = Satuan::where('satuan', $value['satuan'])->first();
                $satuan_item = SatuanItem::where('item_id', $value['item_id'])->where('satuan_id', $satuan->id)->first();
                $new['item_id']           = $value['item_id'];
                $new['qty']               = $value['qty'];
                $new['cost']              = str_replace(',', '', $value['cost']);
                $new['satuan_item_id']    = $satuan_item->id;
                return new DetBillOfMaterial($new);
            });

            $bom->det_bom()->saveMany($detail);

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
        $bom = BillOfMaterial::with(['item.satuan_item.satuan', 'det_bom.item.satuan_item.satuan', 'det_bom.satuan_item.satuan', 'satuan_item.satuan'])->findOrFail($id);
        return view('backoffice.page.bom.edit', ['bom' => $bom]);
    }


    public function update(Request $request, $id)
    {
        $request->request->add(['list_item' => json_decode($request->list_item, true)]);
        $rules = [
            'no_bom' => 'required|unique:bom,no_bom,'.$id,
            'item_id' => 'required',
            'satuan' => 'required',
            'qty' => 'required',
            'list_item' => 'required',
            'list_item.*.item_id' => 'required',
            'list_item.*.satuan' => 'required',
            'list_item.*.qty' => 'required|gt:0',
        ];
        $message = [
            'no_bom.required' => 'No B.O.M Wajib Di isi',
            'no_bom.unique' => 'No B.O.M Sudah Digunakan',
            'item_id.required' => 'Item Belum Dipilih',
            'satuan.required' => 'Satuan Belum Dipilih',
            'qty.required' => 'Qty Hasil Produksi Belum Disi',
            'list_item.required' => 'Item Minimal 1 ',
        ];
        foreach ($request->list_item as $key => $value) {
            $baris = $key+1;
            $message['list_item.'.$key.'.item_id.required'] = 'Baris Ke-'.$baris.' Item Belum Dipilih ';
            $message['list_item.'.$key.'.satuan.required'] = 'Baris Ke-'.$baris.' Satuan Belum Diset ';
            $message['list_item.'.$key.'.qty.required'] = 'Baris Ke-'.$baris.' Qty Tidak Boleh Kosong ';
            $message['list_item.'.$key.'.qty.gt'] = 'Baris Ke-'.$baris.' Qty Tidak Boleh Kosong ';
        }
        $this->validate($request, $rules, $message);

        $bom = BillOfMaterial::findOrFail($id);

        try {
            DB::beginTransaction();
            $satuan = Satuan::where('satuan', $request->satuan)->first();
            $satuan_item = SatuanItem::where('item_id', $request->item_id)->where('satuan_id', $satuan->id)->first();

            $bom->update([
                'no_bom' => $request->no_bom,
                'item_id' => $request->item_id,
                'satuan_item_id' => $satuan_item->id,
                'qty' => $request->qty,
                'total_cost' => str_replace(',', '', $request->total_cost),
                'user_id' => Auth::user()->id
            ]);

            $itemIds = [];

            foreach ($request->list_item as $isi) {
                $satuan = Satuan::where('satuan', $isi['satuan'])->first();
                $satuan_item = SatuanItem::where('item_id', $isi['item_id'])->where('satuan_id', $satuan->id)->first();

                if(isset($isi["id"]) && $isi["id"] != "") {
                    $bom->det_bom()->whereId($isi["id"])->update([
                        'item_id'        => $isi["item_id"],
                        'qty'            => $isi["qty"],
                        'cost'           => str_replace(',', '', $isi['cost']),
                        'satuan_item_id' => $satuan_item->id,
                    ]);
                    $itemIds[] = $isi['id'];

                } else {

                    $det_bom = new DetBillOfMaterial;
                    $det_bom->item_id = $isi["item_id"];
                    $det_bom->qty     = $isi["qty"];
                    $det_bom->cost    = str_replace(',', '', $isi['cost']);
                    $det_bom->satuan_item_id = $satuan_item->id;
                    $bom->det_bom()->save($det_bom);
                    $itemIds[] = $det_bom->id;

                }
            }

            $bom->det_bom()->where('bom_id', $bom->id)->whereNotIn('id', $itemIds)->delete();

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
        $bom = BillOfMaterial::findOrFail($id);
        try {
            DB::beginTransaction();
            if($bom->produksi->history_item()->exists()){
                return response()->json(['code' => 404, 'message' => 'has-data' ], 400);
            }
            $bom->produksi->history_item()->delete();
            $bom->produksi()->delete();
            $bom->det_bom()->delete();

            $bom->delete();

            DB::commit();

            return response()->json(['deleted' => true ]);
        } catch (\Exception $e) {
            DB::commit();
            return response()->json(['code' => 404, 'message' => $e ], 400);
        }
    }

    public function getbom(Request $request)
    {
        $data = [];
        if($request->has('q')){
            $search = $request->q;
            $data = BillOfMaterial::with(['item', 'det_bom.item', 'det_bom.satuan_item.satuan', 'satuan_item.satuan'])
                    ->with(['det_bom.item'=> function($q){
                        $q->withCount(['history_item' => function($query){
                            $query->select(DB::raw("SUM(history_item.qty * satuan_item.qty_konversi) as stock"))
                            ->join('satuan_item', 'satuan_item.id', '=', 'history_item.satuan_item_id')
                            ->groupBy('history_item.item_id');
                        }]);
                    }])
                    ->whereHas('item', function($q) use ($search){
                        $q->where('item.nama_item', 'LIKE', '%'.$search.'%');
                    })
                    ->get();
        }
        return response()->json($data);
    }

    public function no_bom()
    {
        $check = BillOfMaterial::select('no_bom', 'created_at')->orderBy('created_at', 'desc')->first();
        if (!empty($check)) {
            $bulan_last = Carbon::parse($check->created_at)->format('m');
            $no_bom = $check->no_bom;
        } else {
            $bulan_last = date('m');
            $no_bom = 'BM'.date('ym').'000';
        }

        if ($bulan_last >= date('m')) {
            $lastNoUrut = (int)substr($no_bom, 6, 3);
            $tmp = ((int)$lastNoUrut)+1;
            $kd = 'BM'.date('ym').sprintf("%03s", $tmp);
        } else {
            $lastNoUrut = (int)substr($no_bom, 6, 3);
            $tmp = 000+1;
            $kd = 'BM'.date('ym').sprintf("%03s", $tmp);
        }
        return $kd;
    }


    public function cetak(Request $request, $id)
    {
        $bom = BillOfMaterial::findOrFail($id);
        Fpdf::SetTitle('Bill Of Material '.$bom->item->nama_item." (".$bom->no_bom.")", false);
        Fpdf::AddPage('P', "A4");
        Fpdf::SetMargins(10, 50, 20);
        Fpdf::SetAutoPageBreak(false);
        Fpdf::SetFont('Helvetica','B',12);
        Fpdf::Cell(94,6,"BILL OF MATERIAL", 0,0,'L');
        Fpdf::SetFont('Helvetica','BI',12);
        Fpdf::Cell(94,6,"WARUNG MAKAN 5758", 0,1,'R');
        Fpdf::Line(10, 16, 198, 16);
        Fpdf::Line(10, 16.5, 198, 16.5);
        Fpdf::setY(18);
        Fpdf::SetFont('Helvetica','B',9);
        Fpdf::Cell(30,5,"No. B.O.M", 0,0,'L');
        Fpdf::Cell(3,5,":", 0,0,'L');
        Fpdf::Cell(30,5, $bom->no_bom, 0,1,'L');
        Fpdf::Cell(30,5,"B.o.M", 0,0,'L');
        Fpdf::Cell(3,5,":", 0,0,'L');
        Fpdf::Cell(30,5, $bom->item->nama_item, 0,1,'L');
        Fpdf::Cell(30,5,"Qty", 0,0,'L');
        Fpdf::Cell(3,5,":", 0,0,'L');
        Fpdf::Cell(30,5, number_format($bom->qty, 0) .' / '.$bom->satuan_item->satuan->satuan, 0,1,'L');
        Fpdf::ln();
        Fpdf::SetFont('Helvetica','B',9);
        Fpdf::SetFillColor(34, 120, 169);
        Fpdf::SetTextColor(255, 255, 255);
        Fpdf::SetDrawColor(34, 120, 169);
        Fpdf::Cell(10,8,"No.", 1,0,'C', 1);
        Fpdf::Cell(78,8,"Nama Item.", 1,0, 'L', 1);
        Fpdf::Cell(50,8,"Qty", 1,0, 'C', 1);
        Fpdf::Cell(55,8,"Satuan", 1,0, 'C', 1);
        Fpdf::setY(47);
        Fpdf::SetFillColor(221, 236, 255);
        Fpdf::SetTextColor(0, 0, 0);
        Fpdf::SetDrawColor(221, 236, 255);
        for ($i=0; $i <28 ; $i++) {
            Fpdf::Cell(10,7,"", 1,0,'C', 1);
            Fpdf::Cell(78,7,"", 1,0, 'L', 1);
            Fpdf::Cell(50,7,"", 1,0, 'C', 1);
            Fpdf::Cell(55,7,"", 1,0, 'C', 1);
            Fpdf::ln();
        }
        Fpdf::setY(47);
        $no = 1;
        foreach ($bom->det_bom as $isi) {
            Fpdf::SetFont('Helvetica','',9);
            Fpdf::Cell(10,7, $no++, 1,0,'C', 1);
            Fpdf::Cell(78,7, $isi->item->nama_item, 1,0, 'L', 1);
            Fpdf::Cell(50,7, $isi->qty, 1,0, 'C', 1);
            Fpdf::Cell(55,7, $isi->satuan_item->satuan->satuan, 1,0, 'C', 1);
            Fpdf::ln();
        }
        Fpdf::setY(250);
        Fpdf::SetFont('Helvetica','B',10);
        Fpdf::Cell(120.3,6, '',0,0,'C');
        Fpdf::Cell(68.3,6, 'Kasir',0,1,'C');
        Fpdf::ln();
        Fpdf::ln();
        Fpdf::SetFont('Helvetica','BU',10);
        Fpdf::Cell(120.3,6, "",0,0,'C');
        Fpdf::Cell(68.3,6, $bom->user->name,0,1,'C');


        Fpdf::Output('Bom.pdf', 'I', true);


        exit;
    }
}
