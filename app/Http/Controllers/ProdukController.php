<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\Datatables;
use App\Produk;
use App\ProdukKomposit;
use App\DetProdukKomposit;
use App\Satuan;
use App\Kategori;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Auth;
use DB;
use File;

class ProdukController extends Controller
{
    
    public function index(Request $request)
    {
        if($request->ajax()){
            $produk = Produk::select(['id', 'nama_produk', 'kategori_id', 'sku', 'barcode', 'satuan_id', 'foto_produk', 'jenis_produk', 'opsi_jual', 'user_id', 'stock_warning'])
                        ->with([
                            'kategori',
                            'satuan',
                            'produk_komposit'
                        ])
                        ->orderBy('created_at', 'desc');
            return Datatables::of($produk)
                ->addIndexColumn()
                ->editColumn('sku', function($data){
                    if(!is_null($data->sku)){
                        return '<span class="text-success-600">'.$data->sku.'</span>';
                    }
                    return '<span class="label bg-danger">NOT SET</span>';
                })
                ->editColumn('barcode', function($data){
                    if(!is_null($data->barcode)){
                        return '<span class="text-success-600">'.$data->barcode.'</span>';
                    }
                    return '<span class="label bg-danger">NOT SET</span>';
                })
                ->addColumn('action', function($data){
                    return '
                         <div style="padding-left:5px;">
                                <a href="'.url("data-master/produk/".$data->id)."/edit".'"><i class="icon-pencil5 text-success"></i></a>
                                <button class="btn-link" id="button_delete" data-id="'.$data->id.'"  type="button"><i class="icon-trash text-danger"></i></button>
                        </div>
                    ';
                })
                ->editColumn('jenis_produk', function($data){
                    if($data->jenis_produk == '0'){
                        return '<span class="label bg-primary">TUNGGAL</span>';
                        
                    } 
                    return '<span class="label bg-success">KOMPOSIT</span>';
                })
                ->editColumn('stock_warning', function($data){
                    if(!is_null($data->stock_warning)){
                        return '<span class="text-semibold">'.$data->stock_warning.' '.$data->satuan->satuan.'</span>';
                    }
                    return '<span class="label bg-danger">NOT SET</span>';
                })
                ->rawColumns(['action', 'jenis_produk', 'stock_warning', 'sku', 'barcode']) 
                ->make(true);
        }

        return view('backoffice.page.produk.index');
    }

    
    public function create()
    {
        return view('backoffice.page.produk.create');
    }

    
    public function store(Request $request)
    {
        $request->request->add(['sku' => $request->sku]);
        $request->request->add(['barcode' => $request->barcode]);
        $request->request->add(['nama_produk' => $request->nama_produk]);
        $request->request->add(['kategori_produk' => $request->has('kategori_produk') ? $request->kategori_produk : null ]);
        $request->request->add(['satuan_produk' => $request->has('satuan_produk') ? $request->satuan_produk : null]);
        $request->request->add(['opsi_produk' => $request->opsi_produk]);
        $request->request->add(['jenis_produk' => $request->jenis_produk]);
        $request->request->add(['gambar' => $request->nama_produk]);
        $request->request->add(['list_bahan' => $request->list_bahan]);

        $this->validate($request, [
            'sku' => 'nullable|unique:produk,sku',
            'barcode' => 'nullable|unique:produk,barcode',
            'nama_produk' => 'required|unique:produk,nama_produk',
            'kategori_produk' => 'required',
            'satuan_produk' => 'required',
            'stock_warning' => 'sometimes|integer',
            'jenis_produk' => 'required',
            'list_bahan' => 'required_if:jenis_produk,komposit',
            'list_bahan.*.bahan_id' => 'required_if:jenis_produk,komposit',
            'list_bahan.*.qty' => 'required_if:jenis_produk,komposit',
        ], [
            'sku.unique' => 'SKU Sudah Digunakan',
            'barcode.unique' => 'Barcode Sudah Digunakan',
            'nama_produk.required' => 'Nama Barang Wajib Di Isi',
            'nama_produk.unique' => 'Nama Barang Sudah Digunakan',
            'kategori_produk.required' => 'Kategori Wajib Di Pilih',
            'satuan_produk.required' => 'Satuan Wajib Di Pilih',
            'stock_warning.required' => 'Wajib Di Isi',
            'stock_warning.integer' => 'Harus Menggunakan Angka',
            'list_bahan.required_if' => 'Bahan Minimal 1 Jika Jenis Produk Komposit',
            'list_bahan.*.bahan_id.required_if' => 'Bahan Belum Dipilih',
            'list_bahan.*.qty.required_if' => 'Tidak Boleh Kosong',
            // 'gambar.mimes' => 'File Wajib Bereksistensi JPEG, JPG & PNG',
        ]);

        if ($request->hasFile('gambar')) {
            $file_image = $request->file('gambar');
            $filename_image = Carbon::now()->timestamp.'-'.Str::slug($request->nama_produk, '-').'.'.$file_image->getClientOriginalExtension();
            $request->file('gambar')->move("back/gambar-produk", $filename_image);
        }



        try { 
            DB::beginTransaction();
            
            $produk = Produk::create([
                'nama_produk' => $request->nama_produk,
                'kategori_id' => $request->kategori_produk,
                'sku' => $request->sku,
                'barcode' => $request->barcode,
                'satuan_id' => $request->satuan_produk,
                'foto_produk' => !empty($filename_image) ? $filename_image : null,
                'jenis_produk' => $request->jenis_produk == 'tunggal' ? '0' : '1',
                'stock_warning' => $request->stock_warning,
                'opsi_jual' => !$request->opsi_produk ? '0' : '1',
                'user_id' => Auth::user()->id,
            ]);      
            
            if($request->jenis_produk == 'komposit'){

                $data_produk_komposit = new ProdukKomposit([
                    'produk_id' => $produk->id
                ]);

                $produk_komposit = $produk->produk_komposit()->save($data_produk_komposit);
                
                $det_data_produk_komposit = collect(json_decode($request->list_bahan, true))->transform(function($detail, $index) use ($produk_komposit){
                    $detail['produk_komposit_id']   = $produk_komposit->id;
                    $detail['produk_id']            = $detail['bahan_id'];
                    $detail['qty']                  = $detail['qty'];
                    return new DetProdukKomposit($detail);
                });
                $produk_komposit->det_produk_komposit()->saveMany($det_data_produk_komposit);
            }
            DB::commit();  
            return response()->json([ 'message' => 'success' ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([ 'message' => 'error', 'error' => $e ], 400);
        }

        return response()->json($request->all());
    }

    
    public function show($id)
    {
        //
    }

    
    public function edit($id)
    {
        $produk = Produk::with([
            'kategori',
            'satuan',
            'produk_komposit.det_produk_komposit.produk.satuan'
        ])->findOrFail($id);

        $datax = [
            'id' => $produk->id,
            'barcode' => $produk->barcode,
            'sku' => $produk->sku,
            'gambar' => !is_null($produk->foto_produk) ? asset('back/gambar-produk/'.$produk->foto_produk) : '',
            'jenis_produk' => $produk->jenis_produk == '0' ? 'tunggal' : 'komposit',
            'nama_produk' => $produk->nama_produk,
            'stock_warning' => $produk->stock_warning,
            'opsi_produk' => $produk->opsi_jual == "1" ? true : false,
            'kategori' => [
                'id' => $produk->kategori->id,
                'kategori' => $produk->kategori->kategori,
            ],
            'satuan' => [
                'id' => $produk->satuan->id,
                'satuan' => $produk->satuan->satuan,
            ],
            'list_bahan' => $produk->jenis_produk == '1' ? collect($produk->produk_komposit->det_produk_komposit)->transform(function($detail, $index){
                $xx['bahan_id']   = $detail['produk_id'];
                $xx['nama_bahan'] = $detail->produk->nama_produk;
                $xx['qty']        = $detail['qty'];
                $xx['satuan']     = $detail->produk->satuan->satuan;
                return $xx;
            }) : [],
        ];

        // dd($datax);

        return view('backoffice.page.produk.edit', [
            'produk' => json_encode($datax)
        ]);
    }

    
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'sku' => 'nullable|unique:produk,sku,'.$id,
            'barcode' => 'nullable|unique:produk,barcode,'.$id,
            'nama_produk' => 'required|unique:produk,nama_produk,'.$id,
            'kategori_produk' => 'required',
            'satuan_produk' => 'required',
            'stock_warning' => 'sometimes|integer',
            'jenis_produk' => 'required',
            'list_bahan' => 'required_if:jenis_produk,komposit',
            'list_bahan.*.bahan_id' => 'required_if:jenis_produk,komposit',
            'list_bahan.*.qty' => 'required_if:jenis_produk,komposit',
        ], [
            'sku.unique' => 'SKU Sudah Digunakan',
            'barcode.unique' => 'Barcode Sudah Digunakan',
            'nama_produk.required' => 'Nama Produk Wajib Di Isi',
            'nama_produk.unique' => 'Nama Produk Sudah Digunakan',
            'kategori_produk.required' => 'Kategori Wajib Di Pilih',
            'satuan_produk.required' => 'Satuan Wajib Di Pilih',
            'stock_warning.required' => 'Wajib Di Isi',
            'stock_warning.integer' => 'Harus Menggunakan Angka',
            'list_bahan.required_if' => 'Bahan Minimal 1 Jika Jenis Produk Komposit',
            'list_bahan.*.bahan_id.required_if' => 'Bahan Belum Dipilih',
            'list_bahan.*.qty.required_if' => 'Tidak Boleh Kosong',
        ]);

        $produk = Produk::findOrFail($id);

        if ($request->hasFile('gambar')) {
            $image_path = "back/gambar-produk/".$produk->foto_produk;
            if(File::exists($image_path)) {
                File::delete($image_path);
            }
            $file_image = $request->file('gambar');
            $filename_image = Carbon::now()->timestamp.'-'.Str::slug($request->nama_produk, '-').'.'.$file_image->getClientOriginalExtension();
            $request->file('gambar')->move("back/gambar-produk", $filename_image);
        }

        try { 
            DB::beginTransaction();
            
            $produk->update([
                'nama_produk' => $request->nama_produk,
                'kategori_id' => $request->kategori_produk,
                'sku' => $request->sku,
                'barcode' => $request->barcode,
                'satuan_id' => $request->satuan_produk,
                'foto_produk' => !empty($filename_image) ? $filename_image : DB::raw('foto_produk'),
                'jenis_produk' => $request->jenis_produk == 'tunggal' ? '0' : '1',
                'stock_warning' => $request->stock_warning,
                'opsi_jual' => !$request->opsi_produk ? '0' : '1',
                'user_id' => Auth::user()->id,
            ]);      
            
            if($request->jenis_produk == 'komposit'){

                $produk->produk_komposit()->delete();
                
                $data_produk_komposit = new ProdukKomposit([
                    'produk_id' => $produk->id
                ]);

                $produk_komposit = $produk->produk_komposit()->save($data_produk_komposit);
                
                $det_data_produk_komposit = collect(json_decode($request->list_bahan, true))->transform(function($detail, $index) use ($produk_komposit){
                    $detail['produk_komposit_id']   = $produk_komposit->id;
                    $detail['produk_id']            = $detail['bahan_id'];
                    $detail['qty']                  = $detail['qty'];
                    return new DetProdukKomposit($detail);
                });

                $produk_komposit->det_produk_komposit()->saveMany($det_data_produk_komposit);
            }
            DB::commit();  
            return response()->json([ 'message' => 'success' ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([ 'message' => 'error', 'error' => $e ], 400);
        }
    }

    
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        try {
            DB::beginTransaction();
            $image_path = "back/gambar-produk/".$produk->foto_produk;
            if(File::exists($image_path)) {
                File::delete($image_path);
            }
            $produk->produk_komposit()->delete();
            $produk->delete();
            DB::commit();  

            return response()->json([
                'status' => 'sukses',
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e, 400); 
        }
    }


    public function get_produk(Request $request)
    {
        $data = [];

        if($request->has('q')){
            $search = $request->q;
            $data = Produk::with(['satuan', 'kategori'])->select(['nama_produk', 'id', 'kategori_id', 'satuan_id'])
                    ->where('jenis_produk', '0')
                    ->where('nama_produk','LIKE',"%$search%")
                    ->get();
        }
        return response()->json(['items' => $data]);
    }
}
