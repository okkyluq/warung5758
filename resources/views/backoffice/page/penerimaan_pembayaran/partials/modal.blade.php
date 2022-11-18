<div id="modal_uang_muka" class="modal fade" tabindex="-1" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h5 class="modal-title"><i class="icon-plus-circle2"></i> Tambah Uang Muka</h5>
            </div>

            <div class="modal-body">
                <form action="" class="form-horizontal">
                    <div class="form-group">
                        <label class="control-label col-lg-2">Kas / Bank :</label>
                        <div class="col-lg-5">
                            <select name="kas" id="kas" class="form-control"></select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-2">Jumlah :</label>
                        <div class="col-lg-5">
                            <input type="text" class="form-control text-right" id="jumlah_uang_muka" name="jumlah_uang_muka">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-12 text-right">
                            <button type="button" id="btn-cancel-uang-muka" class="btn btn-danger btn-labeled btn-xs"><b><i class=" icon-circle-left2"></i></b> Batal</button>
                            <button type="button" id="btn-update-uang-muka" class="btn btn-info btn-labeled btn-xs"><b><i class="icon-floppy-disk"></i></b> Simpan</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<div id="modal_piutang" class="modal fade" tabindex="-99" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h5 class="modal-title">Pencarian Penjualan</h5>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="form-pencarian-pembelian">
                    <div class="form-group">
                        <label class="col-lg-2 control-label text-right">No. Penjualan :</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" placeholder="No. Penjualan" id="no_penjualan" name="no_penjualan">
                        </div>
                        <label class="col-lg-2 control-label text-right">Costumer :</label>
                        <div class="col-lg-4">
                            <select id="costumer_penjualan" class="form-control pencarian-costumer" name="costumer_penjualan"></select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 control-label text-right">Tanggal:</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control tanggal-periode" placeholder="Tanggal Penjualan" id="tanggal_penjualan" name="tanggal_penjualan">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-4">
                            <button type="submit" class="btn btn-primary">Filter <i class="icon-filter4 position-right"></i></button>
                        </div>
                    </div>
                </form>
                <table class="table table-sm table-bordered" id="table-pencarian-penjualan">
                    <thead>
                        <tr class="bg-success">
                            <th class="text-center" width="80">No.Penjualan</th>
                            <th class="text-center" width="150">No. Ref</th>
                            <th class="text-center" width="200">Tanggal</th>
                            <th class="text-center" width="200">Costumer</th>
                            <th class="text-center" width="220">Total</th>
                            <th class="text-center" width="220">Terbayar</th>
                            <th class="text-center" width="220">Sisa</th>
                            <th class="text-center" width="10">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="modal_akun" class="modal fade" tabindex="-1" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h5 class="modal-title">Pencarian Akun</h5>
            </div>

            <div class="modal-body">
                <form class="form-horizontal" id="form-pencarian-akun">
                    <div class="form-group">
                        <label class="col-lg-2 control-label text-right">Kode Akun :</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" placeholder="Kode Akun" id="kode_akun" name="kode_akun">
                        </div>
                        <label class="col-lg-2 control-label text-right">Nama Akun :</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" placeholder="Nama Akun" id="nama_akun" name="nama_akun">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 control-label text-right">Kategori:</label>
                        <div class="col-lg-4">
                            <select name="kategori" id="kategori" class="form-control">
                                <option disabled selected value="">Pilih Akun</option>
                                @foreach ($kategori as $isi)
                                <option value="{{ $isi->id }}">{{ $isi->no_kategori.'. '.$isi->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-4">
                            <button type="submit" class="btn btn-primary">Filter <i class="icon-filter4 position-right"></i></button>
                        </div>
                    </div>
                </form>
                <table class="table table-sm table-bordered" id="table-pencarian-akun">
                    <thead>
                        <tr class="bg-success">
                            <th class="text-center" width="200">Kategori</th>
                            <th class="text-center" width="150">Kode Akun</th>
                            <th class="text-center">Nama Akun</th>
                            <th class="text-center" width="10">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="modal_retur" class="modal fade" tabindex="-1" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h5 class="modal-title">Pilih Retur</h5>
            </div>

            <div class="modal-body">
                
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>