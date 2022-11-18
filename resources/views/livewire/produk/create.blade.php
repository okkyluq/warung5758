<div>
    <div class="page-header page-header-default">
        <div class="page-header-content">
            <div class="page-title">
                <h2><i class="icon-pencil7 position-left"></i> <span class="text-semibold">Kelola Data Produk</span></h2>
            </div>
        </div>
        <div class="breadcrumb-line"></div>
    </div>
    <!-- /page header -->
    
    
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-flat border-top-xlg border-top-green-600" id="panel-buat-barang">
                    <div class="panel-heading">
                        <h5 class="panel-title"><span class="text-semibold"><i class="icon-file-plus"></i> Buat Data Barang</span></h5>
                    </div>
                    
                    <div class="panel-body">
                        <example-component></example-component>
    
                        <form action="{{ url(Request::segment(1).'/'.Request::segment(2)) }}" method="POST" enctype="multipart/form-data" id="form-tambah-produk">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-3 {{ $errors->has('sku') ? 'has-error' : '' }}">
                                        <label class="text-bold">SKU :</label>
                                        <input type="text" class="form-control text-bold" placeholder="SKU Produk" id="sku" name="sku" style="text-transform:uppercase">
                                        @if ($errors->has('sku'))
                                        <div class="label-block">
                                            <span class="help-block">{{ $errors->first('sku') }}</span>
                                        </div>	
                                        @endif
                                    </div>
                                    <div class="col-md-3 {{ $errors->has('barcode') ? 'has-error' : '' }}">
                                        <label class="text-bold">Barcode Barang :</label>
                                        <input type="text" class="form-control text-bold" placeholder="Barcode Produk" id="barcode" name="barcode">
                                        @if ($errors->has('barcode'))
                                        <div class="label-block">
                                            <span class="help-block">{{ $errors->first('barcode') }}</span>
                                        </div>	
                                        @endif
                                    </div>
                                </div>
                            </div>
    
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6 {{ $errors->has('nama_produk') ? 'has-error' : '' }}">
                                        <label class="text-bold">Nama Produk :</label>
                                        <input type="text" class="form-control text-bold" placeholder="Nama Produk" name="nama_produk" id="nama_produk">
                                        @if ($errors->has('nama_produk'))
                                        <div class="label-block">
                                            <span class="help-block">{{ $errors->first('nama_produk') }}</span>
                                        </div>	
                                        @endif
                                    </div>
                                </div>
                            </div>
    
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-3 {{ $errors->has('kategori_produk') ? 'has-error' : '' }}">
                                        <label class="text-bold">Kategori Produk :</label>
                                        <select class="form-control select-search text-bold" id="kategori_produk" name="kategori_produk">
                                            @if(Request::old('kategori_produk') != NULL)
                                            <option value="{{Request::old('kategori_produk')}}"></option>
                                            @endif
                                        </select>
                                        @if ($errors->has('kategori_produk'))
                                        <div class="label-block">
                                            <span class="help-block">{{ $errors->first('kategori_produk') }}</span>
                                        </div>	
                                        @endif
                                    </div>
                                    <div class="col-md-3 {{ $errors->has('satuan_produk') ? 'has-error' : '' }}">
                                        <label class="text-bold">Satuan Produk :</label>
                                        <select class="form-control select-search text-bold" id="satuan_produk" name="satuan_produk"></select>
                                        @if ($errors->has('satuan_produk'))
                                        <div class="label-block">
                                            <span class="help-block">{{ $errors->first('satuan_produk') }}</span>
                                        </div>	
                                        @endif
                                    </div>
                                    <div class="col-md-2 {{ $errors->has('stock_warning') ? 'has-error' : '' }}">
                                        <label class="text-bold">Stok Warning :</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control qty text-bold" placeholder="Stock Warning" name="stock_warning" id="stock_warning">
                                            <span class="input-group-addon">Qty</span>
                                        </div>
                                        @if ($errors->has('stock_warning'))
                                        <div class="label-block">
                                        <span class="help-block">{{ $errors->first('stock_warning') }}</span>
                                        </div>	
                                        @endif
                                    </div>
                                    <div class="col-md-2 {{ $errors->has('stock') ? 'has-error' : '' }}">
                                        <label class="text-bold">Opsi Produk :</label>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" class="control-primary" name="opsi_produk" value="jual" id="opsi_produk">Produk Dijual
                                            </label>
                                        </div>
                                        @if ($errors->has('stock'))
                                        <div class="label-block">
                                        <span class="help-block">{{ $errors->first('stock') }}</span>
                                        </div>	
                                        @endif
                                    </div>
                                    
                                </div>
                            </div>
    
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-8">
                                        <label class="text-bold">Gambar Barang :</label>
                                        <input type="file" class="file-input" name="gambar_barang">
                                        @if ($errors->has('gambar_barang'))
                                        <div class="label-block">
                                            <span class="help-block">{{ $errors->first('gambar_barang') }}</span>
                                        </div>	
                                        @endif
                                    </div>
                                </div>
                            </div>
    
                            <legend class="text-bold">Jenis Produk</legend>
    
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-8">
                                        <input type="hidden" id="jenis_produk" name="jenis_produk">
                                        <button type="button" class="btn btn-float btn-float-lg {{ $produk_type == 'tunggal' ? 'btn-success' : 'btn-default'}}" wire:click.prevent="toggleTypeProduk('tunggal')"><i class="icon-file-empty2"></i> <span>Produk Tunggal</span></button>
                                        <button type="button" class="btn btn-float btn-float-lg {{ $produk_type == 'komposit' ? 'btn-success' : 'btn-default'}}" wire:click.prevent="toggleTypeProduk('komposit')"><i class="icon-file-text3"></i> <span>Produk Komposit</span></button>
                                    </div>
                                </div>
                            </div>
     
                            
                                    
                            @if ($produk_type === "komposit")
                            <select class="form-control select-search text-bold" id="bahan" name="bahan">
                                @foreach ($bahan_baku as $isi)
                                    <option value="{{$isi->id}}">{{$isi->satuan}}</option>
                                @endforeach
                            </select>
                            <table class="table table-framed table-xs" id="tabel-bahan-resep">
                                <thead>
                                    <tr>
                                        <th class="text-center text-bold">Bahan</th>
                                        <th width="150" class="text-center">Jumlah</th>
                                        <th width="90">Satuan</th>
                                        <th class="text-center" width="20"><i class="icon-gear"></i></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($inputs) >= 1)
                                    @foreach($inputs as $key => $value)
                                    <tr>
                                        <td>
                                            {{$value['nama_produk']}}
                                        </td>
                                        <td>
                                            <input type="text" class="form-control">
                                        </td>
                                        <td class="text-center">
                                            {{ $value['satuan'] }}
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-link" wire:click.prevent="remove({{$key}})"><i class="text-danger icon-trash"></i></button>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr class="item-not-found"><td class="text-center text-bold" colspan="4">Bahan Belum ada</td></tr>
                                    @endif
                                </tbody>
                            </table>
                            <div class="form-group has-feedback" style="margin-top: 10px;" wire:ignore>
                                <button type="button" class="btn btn-link btn-xs text-info" wire:click.prevent="add({{$i}})"><i class="icon-plus-circle2 position-left text-info"></i> Tambah Bahan Baku</button>
                            </div>
                            @endif
                            

                            
                           
    
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="form-group">
                                <div class="row pull-right">
                                    <div class="col-md-12">
                                        <a href="{{ url(Request::segment(1).'/'.Request::segment(2)) }}" class="btn btn-sm bg-danger btn-labeled text-bold"><b><i class="icon-circle-left2"></i></b> Batal & Kembali</a>
                                        <button type="submit" class="btn btn-sm bg-success btn-labeled text-bold"><b><i class="icon-floppy-disk"></i></b> Simpan Data</button>
                                    </div>
                                </div>
                            </div>
                            
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
    
        {{-- modal tambah kategori --}}
        <div id="modal-add-kategori" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">×</button>
                        <h5 class="modal-title">Basic modal</h5>
                    </div>
    
                    <div class="modal-body">
                        <h6 class="text-semibold">Text in a modal</h6>
                        <p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>
    
                        <hr>
    
                        <h6 class="text-semibold">Another paragraph</h6>
                        <p>Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.</p>
                        <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>
                    </div>
    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    
        
        
    
    
    
    </div>
</div>

