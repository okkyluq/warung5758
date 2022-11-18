<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\ItemCreate;
use App\Http\Requests\ItemUpdate;
use App\Imports\ItemImport;
use App\Imports\ItemImport2;
use App\Imports\ItemImport3;
use App\PengaturanAkun;
use App\PengaturanSistem;
use App\Item;
use App\Satuan;
use App\SatuanItem;
use App\ItemAkutansi;
use App\ItemStockMinimal;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class ItemController extends Controller
{

    public function index(Request $request)
    {
        if($request->ajax()){
            $item = Item::select(['id', 'kode_item', 'barcode', 'nama_item', 'tipe_item'])
                        ->orderBy('created_at', 'desc');

            return Datatables::of($item)
                ->addIndexColumn()
                ->editColumn('tipe_item', function($data){
                    $tipe = ['Barang Jadi', 'Barang Hasil Produksi', 'Bahan Baku'];
                    return "<span class='label bg-success-400'>".$tipe[$data->tipe_item]."</span>";
                })
                ->addColumn('action', function($data){
                    return '
                        <ul class="icons-list">
                            <li><a href="'.url("data-master/item/".$data->id)."/edit".'"><i class="icon-pencil5 text-success"></i></a></li>
                            <li><a class="btn-link" id="button_delete" data-id="'.$data->id.'"><i class="icon-trash text-danger"></i></a></li>
                        </ul>

                    ';
                })
                ->addColumn('stock', function($data){
                    return 1;
                })
                ->rawColumns(['action', 'stock', 'tipe_item'])
                ->make(true);
        }
        return view('backoffice.page.item.index');
    }


    public function create()
    {
        $akun = [
            'akun_pembelian' => PengaturanAkun::select(['id', 'akun_id', 'value', 'kode', 'setting'])->where('setting', 'inventory')->first(),
            'akun_hpp' => PengaturanAkun::select(['id', 'akun_id', 'value', 'kode', 'setting'])->where('setting', 'hpp')->first(),
            'akun_penjualan' => PengaturanAkun::select(['id', 'akun_id', 'value', 'kode', 'setting'])->where('setting', 'penjualan')->first(),
            'akun_retur_penjualan' => PengaturanAkun::select(['id', 'akun_id', 'value', 'kode', 'setting'])->where('setting', 'retur_jual')->first(),
        ];
        return view('backoffice.page.item.create', [
            'kode_item' => $this->kode_item(),
            'akun' => $akun,
            'satuan_default' => PengaturanSistem::where('setting', 'satuan_default')->pluck('value')->first(),
        ]);
    }


    public function store(ItemCreate $request)
    {
        if ($request->hasFile('gambar_item')) {
            $file_image = $request->file('gambar_item');
            $filename_image = Carbon::now()->timestamp.'-'.Str::slug($request->nama_item, '-').'.'.$file_image->getClientOriginalExtension();
            $request->file('gambar_item')->move("back/gambar-item", $filename_image);
        }

        try {
                DB::beginTransaction();
                // return response()->json($request->all());
                $satuan_penjualan = Satuan::firstOrCreate(['satuan' => $request->satuan_penjualan]);
                $satuan_pembelian = Satuan::firstOrCreate(['satuan' => $request->satuan_pembelian]);
                $satuan_stock = Satuan::firstOrCreate(['satuan' => $request->satuan_stock]);

                $item = Item::create([
                        'kode_item' => $request->kode,
                        'barcode' => $request->barcode,
                        'nama_item' => $request->nama_item,
                        'tipe_item' => $request->tipe_item,
                        'kategori_item' => $request->kategori_item,
                        'opsi_jual' => $request->opsi_jual,
                        'satuan_penjualan' => $satuan_penjualan->id,
                        'satuan_pembelian' => $satuan_pembelian->id,
                        'satuan_stock' => $satuan_stock->id,
                        'gambar_item' => !empty($filename_image) ? $filename_image : null,
                        'user_id' => Auth::user()->id
                ]);

                $list_satuan = collect($request->list_satuan)->transform(function($value, $index) use ($item){
                    $new = [
                        'item_id'      => $item->id,
                        'satuan_id'    => $value['satuan_id'],
                        'lvl'          => $index+1,
                        'qty_konversi' => $value['qty_konversi'],
                        'harga_jual'   => $value['harga_jual'] != "" ? $value['harga_jual'] : 0,
                        'harga_beli'   => $value['harga_beli'] != "" ? $value['harga_beli'] : 0,
                    ];
                    return new SatuanItem($new);
                });

                $item->satuan_item()->saveMany($list_satuan);

                if($request->has('qty_minimal') && $request->qty_minimal != null){
                    $satuan_minimal = Satuan::firstOrCreate(['satuan' => $request->satuan_minimal]);
                    $item->item_stock_minimal()->save(
                        new ItemStockMinimal([
                            'qty_minimal' => $request->qty_minimal,
                            'satuan_id' => $satuan_minimal->id
                        ])
                    );
                }

                $akuntasi_item = new ItemAkutansi([
                    'akun_pembelian' => $request->pembelian,
                    'akun_hpp' => $request->hpp,
                    'akun_penjualan' => $request->penjualan,
                    'akun_retur_penjualan' => $request->retur_penjualan
                ]);

                $item->item_akutansi()->save($akuntasi_item);

                DB::commit();
                return response()->json([
                    'status' => 'sukses',
                ], 200);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json($e, 400);
            }

            return response()->json($request->all());
    }


    public function show($id)
    {
        return $this->gethpp($id);
    }

    public function gethpp($id)
    {
        $item = Item::findOrFail($id);
        // cek apakah punya bom atau tidak
        if($item->bom()->exists()){
            $isi_bom = $item->load(['det_bom.item.history_item' => function($query){
                $query->select(['history_item.*', 'satuan_item.*', DB::raw('round((harga / qty_konversi), 2) as hpp')])
                ->join('satuan_item', 'satuan_item.id', 'history_item.satuan_item_id');
            }]);

            $cost_hpp =  $isi_bom->det_bom->sum('cost');

            $item_hpp = $isi_bom->det_bom->map(function($value, $index){
                $new['hpp'] = $value->item->history_item->avg('hpp');
                return $new;
            });

            return number_format($item_hpp->sum('hpp') + $cost_hpp, 2);

        } else {
            $datax = $item->load(['history_item' => function($query){
                $query->select(['history_item.*', 'satuan_item.*', DB::raw('round((harga / qty_konversi), 2) as hpp')])
                ->join('satuan_item', 'satuan_item.id', 'history_item.satuan_item_id');
            }]);
            $hpp = $datax->history_item->avg('hpp');
            return number_format($hpp, 2);
        }
    }


    public function edit($id)
    {
        $item = Item::with([
                            'satuan_item.satuan',
                            'get_satuan_penjualan', 'get_satuan_pembelian', 'get_satuan_stock',
                            'item_stock_minimal.satuan',
                            'item_akutansi.akun_pembelian',
                            'item_akutansi.akun_hpp',
                            'item_akutansi.akun_penjualan',
                            'item_akutansi.akun_retur_penjualan',
                ])->findOrFail($id);
        return view('backoffice.page.item.edit', [
            'item' => $item
        ]);
    }


    public function update(ItemUpdate $request, $id)
    {
        $item = Item::findOrFail($id);

        if ($request->hasFile('gambar_item')) {
            $image_path = "back/gambar-item/".$item->gambar_item;
            if(File::exists($image_path)) {
                File::delete($image_path);
            }
            $file_image = $request->file('gambar_item');
            $filename_image = Carbon::now()->timestamp.'-'.Str::slug($request->nama_item, '-').'.'.$file_image->getClientOriginalExtension();
            $request->file('gambar_item')->move("back/gambar-item", $filename_image);
        }


        try {

            DB::beginTransaction();

            $satuan_penjualan = Satuan::firstOrCreate(['satuan' => $request->satuan_penjualan]);
            $satuan_pembelian = Satuan::firstOrCreate(['satuan' => $request->satuan_pembelian]);
            $satuan_stock = Satuan::firstOrCreate(['satuan' => $request->satuan_stock]);

            $item->update([
                'kode_item' => $request->kode,
                'barcode' => $request->barcode,
                'nama_item' => $request->nama_item,
                'tipe_item' => $request->tipe_item,
                'kategori_item' => $request->kategori_item,
                'opsi_jual' => $request->opsi_jual,
                'satuan_penjualan' => $satuan_penjualan->id,
                'satuan_pembelian' => $satuan_pembelian->id,
                'satuan_stock' => $satuan_stock->id,
                'gambar_item' => !empty($filename_image) ? $filename_image : DB::raw('gambar_item'),
                'user_id' => Auth::user()->id
            ]);

            $list_satuan = collect($request->list_satuan)->transform(function($value, $index) use ($item){
                $new = [
                    'item_id'        => $item->id,
                    'satuan_item_id' => $value['satuan_item_id'],
                    'satuan_id'      => $value['satuan_id'],
                    'lvl'            => $index+1,
                    'qty_konversi'   => $value['qty_konversi'],
                    'harga_jual'     => $value['harga_jual'] != "" ? $value['harga_jual'] : 0,
                    'harga_beli'     => $value['harga_beli'] != "" ? $value['harga_beli'] : 0,
                ];
                return $new;
            });

            foreach ($list_satuan as $isi){
                if(isset($isi["satuan_item_id"]) && $isi["satuan_item_id"] != "") {
                    $item->satuan_item()->whereId($isi['satuan_item_id'])->update([
                        'harga_jual' => $isi['harga_jual'],
                        'harga_beli' => $isi['harga_beli'],
                    ]);
                } else {
                    $satuan_item = new SatuanItem;
                    $satuan_item->item_id      = $item->id;
                    $satuan_item->satuan_id    = $isi['satuan_id'];
                    $satuan_item->lvl          = $isi['lvl'];
                    $satuan_item->qty_konversi = $isi['qty_konversi'];
                    $satuan_item->harga_jual   = $isi['harga_jual'];
                    $satuan_item->harga_beli   = $isi['harga_beli'];
                    $item->satuan_item()->save($satuan_item);
                }
            }

            if($request->has('qty_minimal') && $request->qty_minimal != null){
                $satuan_minimal = Satuan::firstOrCreate(['satuan' => $request->satuan_minimal]);

                if($item->item_stock_minimal){
                    $item->item_stock_minimal()->update([
                        'qty_minimal' => $request->qty_minimal,
                        'satuan_id' => $satuan_minimal->id
                    ]);
                } else {
                    $item->item_stock_minimal()->save(
                        new ItemStockMinimal([
                            'qty_minimal' => $request->qty_minimal,
                            'satuan_id' => $satuan_minimal->id
                        ])
                    );
                }


            }

            $item->item_akutansi()->update([
                'akun_pembelian' => $request->pembelian,
                'akun_hpp' => $request->hpp,
                'akun_penjualan' => $request->penjualan,
                'akun_retur_penjualan' => $request->retur_penjualan
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


    public function destroy($id)
    {
        $item = Item::with(['history_item'])->findOrFail($id);
        try {
            DB::beginTransaction();
            if(count($item->history_item) > 0){
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak Dapat Menghapus Karena Item Sudah Memiliki Data Transaksi !',
                ], 405);
            }
            $image_path = "back/gambar-item/".$item->gambar_item;
            if(File::exists($image_path)) { File::delete($image_path); }
            $item->satuan_item()->delete();
            $item->delete();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil Menghapus Data Item !',
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e
            ], 400);
        }
    }

    public function getStockMinimal(Request $request)
    {
        $query = DB::select(DB::raw("
                        SELECT item.id, item.nama_item, item.tipe_item,
                        item_stock_minimal.qty_minimal,
                        ROUND(satuan_item.qty_konversi, 0) as qty_konversi,
                        ROUND(item_stock_minimal.qty_minimal * ROUND(satuan_item.qty_konversi) / satuan_item.qty_konversi, 0) as stock_butuh,
                        IFNULL(ROUND(SUM(history_item.qty * anu.qty_konversi) / satuan_item.qty_konversi), 0) as stock_tersedia,
                        satuan.satuan
                        FROM item
                        INNER JOIN item_stock_minimal ON item_stock_minimal.item_id = item.id
                        INNER JOIN satuan_item ON satuan_item.item_id = item.id AND satuan_item.satuan_id = item_stock_minimal.satuan_id
                        LEFT JOIN history_item ON history_item.item_id = item.id
                        LEFT JOIN satuan_item as anu ON anu.id = history_item.satuan_item_id
                        LEFT JOIN satuan ON satuan.id = satuan_item.satuan_id
                        GROUP BY item.id
                        HAVING stock_tersedia < stock_butuh
                        ORDER BY stock_tersedia ASC
                    "));
        $paginator = $this->arrayPaginator($query, $request);
        return response()->json($paginator);
    }

    public function arrayPaginator($array, $request)
    {
        $page = $request->input("page", 1);
        $perPage = 5;
        $offset = ($page * $perPage) - $perPage;

        return new LengthAwarePaginator(array_slice($array, $offset, $perPage, true), count($array), $perPage, $page,
            ['path' => $request->url(), 'query' => $request->query()]);
    }

    public function getitem(Request $request)
    {
        $search = $request->get('query');
        $result = Item::with(['satuan_item.satuan', 'get_satuan_pembelian'])
                    ->when($request->has('not_category') && $request->not_category == '1', function($q){
                        $q->where('tipe_item', '!=', '1');
                    })
                    ->when($request->has('category') && $request->category == '1', function($q){
                        $q->where('tipe_item', '1');
                    })
                    ->when($request->has('opsi_jual') && $request->opsi_jual == '1', function($q){
                        $q->where('opsi_jual', '1');
                    })
                    ->where('nama_item','LIKE',"%$search%")
                    ->take('5')
                    ->get();
        foreach($result as $data) {
            $output['suggestions'][] = [
                'value'       => $data['nama_item'],
                'id'          => $data['id'],
                'item' => $data,
            ];
        }

        if (! empty($output)) {
            // Encode ke format JSON.
            echo json_encode($output);
        }
    }

    public function getitem_select2(Request $request)
    {
        $data = [];

        if($request->has('q')){
            $search = $request->q;
            $data = Item::select(['*'])->with(['satuan_item.satuan', 'get_satuan_pembelian', 'get_satuan_penjualan'])
                    ->withCount(['history_item' => function($query){
                        $query->select(DB::raw("ROUND(SUM(history_item.qty * satuan_item.qty_konversi)) as stock"))
                                ->join('satuan_item', 'satuan_item.id', '=', 'history_item.satuan_item_id')
                                ->groupBy('history_item.item_id');
                    }])
                    ->when($request->has('category') && $request->category == '1', function($q){
                        $q->where('tipe_item', '1');
                    })
                    ->when($request->has('opsi_jual') && $request->opsi_jual == '1', function($q){
                        $q->where('opsi_jual', '1');
                    })
                    ->limit(3)
                    ->where('nama_item', 'LIKE', '%'.$search.'%')
                    ->get();
        }

        return response()->json($data);
    }

    public function kode_item()
    {
        $check = Item::select('kode_item', 'created_at')->orderBy('created_at', 'desc')->first();
        if (!empty($check)) {
            $bulan_last = Carbon::parse($check->created_at)->format('m');
            $kode_item = $check->kode_item;
        } else {
            $bulan_last = date('m');
            $kode_item = 'ITEM'.date('ym').'000';
        }

        if ($bulan_last >= date('m')) {
            $lastNoUrut = (int)substr($kode_item, 8, 3);
            $tmp = ((int)$lastNoUrut)+1;
            $kd = 'ITEM'.date('ym').sprintf("%03s", $tmp);
        } else {
            $lastNoUrut = (int)substr($kode_item, 8, 3);
            $tmp = 000+1;
            $kd = 'ITEM'.date('ym').sprintf("%03s", $tmp);
        }
        return $kd;


    }


    public function import(Request $request)
    {
        $nama_file = $request->file('excel')->getClientOriginalName();

        if ($request->jumlah_satuan == 'satu' && strpos($nama_file, 'Template-Import-Item-1-satuan') !== false) {
            $import = new ItemImport;
            $import->import($request->file('excel'));
            $rows = Excel::toCollection(new ItemImport, $request->file('excel'))[0]->count();
            return response()->json([
                'failure' => collect($import->failures()->isNotEmpty() ? $import->failures() : null),
                'total_row' => $rows,
                'row_success' => $import->getRowCount(),
                'row_fail' => $rows - $import->getRowCount()
            ]);
        } else if ($request->jumlah_satuan == 'dua' && strpos($nama_file, 'Template-Import-Item-2-satuan') !== false) {
            $import = new ItemImport2;
            $import->import($request->file('excel'));
            $rows = Excel::toCollection(new ItemImport2, $request->file('excel'))[0]->count();
            return response()->json([
                'failure' => collect($import->failures()->isNotEmpty() ? $import->failures() : null),
                'total_row' => $rows,
                'row_success' => $import->getRowCount(),
                'row_fail' => $rows - $import->getRowCount()
            ]);
        } else if ($request->jumlah_satuan == 'tiga' && strpos($nama_file, 'Template-Import-Item-3-satuan') !== false){
            $import = new ItemImport3;
            $import->import($request->file('excel'));
            $rows = Excel::toCollection(new ItemImport3, $request->file('excel'))[0]->count();
            return response()->json([
                'failure' => collect($import->failures()->isNotEmpty() ? $import->failures() : null),
                'total_row' => $rows,
                'row_success' => $import->getRowCount(),
                'row_fail' => $rows - $import->getRowCount()
            ]);
        } else {
            return response()->json(['message' =>  'File Tidak Sesuai Aturan !'], 401);
        }



    }

    public function get_item_penjualan(Request $request)
    { 
        $item = Item::with(['get_satuan_penjualan.satuan_item', 'satuan_item.satuan'])
                        ->withCount(['history_item' => function($query){
                                $query->select(DB::raw("SUM(history_item.qty * satuan_item.qty_konversi) as stock"))
                                        ->join('satuan_item', 'satuan_item.id', '=', 'history_item.satuan_item_id')
                                        ->groupBy('history_item.item_id');
                        }])
                        ->when($request->has('key'), function($query) use ($request){
                            $query->where('nama_item', 'like', '%'.$request->key.'%');
                        })
                        ->when($request->has('kategori') && $request->kategori != "", function($query) use ($request){
                            $query->where('kategori_item', $request->kategori);
                        })
                        ->where('opsi_jual', '1')
                        ->limit(20)
                        ->orderBy('created_at', 'desc')->get();
        return response()->json($item);



    }

}
