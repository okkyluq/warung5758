<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\ItemRequest;
use App\Services\API\ItemService;
use Illuminate\Support\Facades\File;
use App\Item;
use App\Satuan;
use App\SatuanItem;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\ItemAkutansi;
use App\ItemStockMinimal;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{

    public function index(Request $request)
    {
        $items = Item::when($request->kode_item, function($query) use ($request){
                                $query->where('kode_item', 'like', '%'.$request->kode_item.'%');
                            })
                            ->when($request->nama_item, function($query) use ($request){
                                $query->where('nama_item', 'like', '%'.$request->nama_item.'%');
                            })
                            ->when($request->barcode, function($query) use ($request){
                                $query->where('barcode', 'like', '%'.$request->barcode.'%');
                            })
                            ->when($request->tipe_item, function($query) use ($request){
                                $query->where('tipe_item', $request->tipe_item);
                            })
                            ->orderBy('created_at', 'desc');
        $items = $items->paginate(10);
        return $items->appends($request->all());
    }


    public function store(ItemRequest $request)
    {

        if($request->has('image') && $request->image != ""){
            $image_parts    = explode(";base64,", $request->image);
            $extension      = explode('/', mime_content_type($request->image))[1];
            $filename_image = Carbon::now()->timestamp.'-'.Str::slug($request->kode_item, '-').'-'.Str::slug($request->nama_item, '-').'.'.$extension;
            Storage::put('public/gambar-item/'.$filename_image, base64_decode($image_parts[1]));
        }


        try {
            DB::beginTransaction();

            $item = Item::create([
                'kode_item' => $request->kode_item,
                'barcode' => $request->barcode,
                'nama_item' => $request->nama_item,
                'tipe_item' => $request->tipe_item,
                'kategori_item' => $request->kategori_item,
                'opsi_jual' => $request->opsi_jual === true ? '1' : '0',
                'satuan_penjualan' => $request->satuan_jual,
                'satuan_pembelian' => $request->satuan_beli,
                'satuan_stock' => $request->satuan_stock,
                'gambar_item' => !empty($filename_image) ? $filename_image : null,
                'user_id' => 1
            ]);

            $list_satuan = collect($request->satuan)->transform(function($value, $index) use ($item){
                $new = [
                    'item_id'      => $item->id,
                    'satuan_id'    => $value['satuan'],
                    'lvl'          => $index+1,
                    'qty_konversi' => $value['qty_konversi'],
                    'harga_jual'   => $value['harga_jual'] != "" ? $value['harga_jual'] : 0,
                    'harga_beli'   => $value['harga_beli'] != "" ? $value['harga_beli'] : 0,
                ];
                return new SatuanItem($new);
            });

            $item->satuan_item()->saveMany($list_satuan);

            if($request->has('qty_minimal') && $request->qty_minimal > 0){
                $item->item_stock_minimal()->save(
                    new ItemStockMinimal([
                        'qty_minimal' => $request->qty_minimal,
                        'satuan_id' => $request->satuan_qty_minmal
                    ])
                );
            }

            $akuntasi_item = new ItemAkutansi([
                'akun_pembelian' => $request->akun_pembelian,
                'akun_hpp' => $request->akun_hpp,
                'akun_penjualan' => $request->akun_penjualan,
                'akun_retur_penjualan' => $request->akun_retur_penjualan
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


    }


    public function show($id)
    {
        //
    }

     public function edit(ItemService $convert, $id)
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

        $item->setAttribute('image', $item->gambar_item != null ? $convert->convertImgToBase64($item->gambar_item) : null);
        return response()->json($item);
    }


    public function update(ItemRequest $request, $id)
    {
        $item = Item::findOrFail($id);

        if($request->has('image') && $request->image != ''){
            if(Storage::exists("public/gambar-item/".$item->gambar_item)) {
                Storage::delete("public/gambar-item/".$item->gambar_item);
            }
            $image_parts    = explode(";base64,", $request->image);
            $extension      = explode('/', mime_content_type($request->image))[1];
            $filename_image = Carbon::now()->timestamp.'-'.Str::slug($request->kode_item, '-').'-'.Str::slug($request->nama_item, '-').'.'.$extension;
            Storage::put('public/gambar-item/'.$filename_image, base64_decode($image_parts[1]));
        }

        try {
            DB::beginTransaction();
            $item->update([
                'kode_item' => $request->kode_item,
                'barcode' => $request->barcode,
                'nama_item' => $request->nama_item,
                'tipe_item' => $request->tipe_item,
                'kategori_item' => $request->kategori_item,
                'opsi_jual' => $request->opsi_jual === true ? '1' : '0',
                'satuan_penjualan' => $request->satuan_jual,
                'satuan_pembelian' => $request->satuan_beli,
                'satuan_stock' => $request->satuan_stock,
                'gambar_item' => !empty($filename_image) ? $filename_image : null,
                'user_id' => 1
            ]);

            $list_satuan = collect($request->satuan)->transform(function($value, $index) use ($item){
                $new = [
                    'item_id'      => $item->id,
                    'id'           => $value['id'],
                    'satuan_id'    => $value['satuan'],
                    'lvl'          => $index+1,
                    'qty_konversi' => $value['qty_konversi'],
                    'harga_jual'   => $value['harga_jual'] != "" ? $value['harga_jual'] : 0,
                    'harga_beli'   => $value['harga_beli'] != "" ? $value['harga_beli'] : 0,
                ];
                return $new;
            });

            // return response()->json($list_satuan);

            foreach ($list_satuan as $isi){
                if(isset($isi["id"]) && $isi["id"] != "") {
                    $item->satuan_item()->whereId($isi['id'])->update([
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
                if($item->item_stock_minimal){
                    $item->item_stock_minimal()->update([
                        'qty_minimal' => $request->qty_minimal,
                        'satuan_id' => $request->satuan_qty_minmal
                    ]);
                } else {
                     $item->item_stock_minimal()->save(
                        new ItemStockMinimal([
                            'qty_minimal' => $request->qty_minimal,
                            'satuan_id' => $request->satuan_qty_minmal
                        ])
                    );
                }


            }



            $item->item_akutansi()->update([
                'akun_pembelian' => $request->akun_pembelian,
                'akun_hpp' => $request->akun_hpp,
                'akun_penjualan' => $request->akun_penjualan,
                'akun_retur_penjualan' => $request->akun_retur_penjualan
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

            if(Storage::exists("public/gambar-item/".$item->gambar_item)) {
                Storage::delete("public/gambar-item/".$item->gambar_item);
            }

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

    public function get_item(Request $request)
    {
        $data = [];

        if($request->has('q')){
            $search = $request->q;
            $data = Item::select(['*'])->with(['satuan_item.satuan', 'get_satuan_pembelian', 'get_satuan_penjualan'])
                    ->where('nama_item','LIKE',"%$search%")
                    ->limit(10)
                    ->get();
        }
        return response()->json($data);
    }

    public function get_list_item(Request $request)
    {
        $items = Item::with(['get_satuan_penjualan', 'satuan_item' => function($query){
                            $query->orderBy('lvl', 'desc');
                        },
                        'satuan_item.satuan'])
                        ->withCount(['history_item' => function($query){
                                $query->select(DB::raw("SUM(history_item.qty * satuan_item.qty_konversi) as stock"))
                                        ->join('satuan_item', 'satuan_item.id', '=', 'history_item.satuan_item_id')
                                        ->groupBy('history_item.item_id');
                        }])
                        ->when($request->nama_item, function($query) use ($request){
                            $query->where('nama_item', 'like', '%'.$request->nama_item.'%');
                        })
                        ->when($request->kategori, function($query) use ($request){
                            $query->where('kategori_item', $request->kategori);
                        })
                        ->where('opsi_jual', '1')
                        ->orderBy('created_at', 'desc')
                        ->limit(24)->get();
        return response()->json($items);
    }

}
