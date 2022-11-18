@extends('kasir.layout.layout')
{{-- @section('title'){{ env("SET_TOKO", "NOT SET") }} | Data Master - Data Supplier @endsection --}}
@section('style')
@endsection
@section('content')
<div class="row">
	<div class="col-sm-5">
		<div class="panel panel-flat border-top-xlg border-top-warning" id="panel-item-list">
			<div class="panel-heading">
				<h6 class="panel-title"><span class="text-semibold"><i class="icon-store"></i> Daftar Item</span></h6>
			</div>

			<div class="panel-body">
				<div class="row">
					<div class="form-group col-sm-12">
                        <div class="input-group">
                            <input type="search" class="form-control" placeholder="Masukan Nama Item" id="input-pencarian-item">
                            <span class="input-group-btn">
                                <button id="btn-clear-item" class="hidden btn btn-default btn-danger" type="button"><i class="icon-cancel-circle2"></i></button>
                                <button id="btn-cari-item" class="btn btn-default" type="button"><i class="icon-search4"></i> Cari</button>
                            </span>
                        </div>
					</div>
				</div>

				<div class="row">
					<div class="col-sm-12 text-right">
						<button data-category="" type="button" class="btn bg-orange-800 btn-labeled btn-rounded btn-category"><b><i class="icon-price-tag3"></i></b> All Item</button>
						<button data-category="1" type="button" class="btn bg-info-800 btn-labeled btn-rounded btn-category"><b><i class="icon-price-tag3"></i></b> Makanan</button>
						<button data-category="2" type="button" class="btn bg-primary-800  btn-labeled btn-rounded btn-category"><b><i class="icon-price-tag3"></i></b> Minuman</button>
					</div>
				</div>
			</div>

			<div class="panel-body" style="overflow-y: scroll; height: 350px; background-color: #e8e8e8; border-top-color:#a2a2a2; border-top-style:solid; border-bottom-color:#a2a2a2; border-bottom-style:solid;">
				<div class="row" id="container-list-item">
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-7">
		<div class="panel panel-flat border-top-xlg border-top-warning" id="panel-buat-item">
			<div class="panel-heading">
				<h6 class="panel-title"><span class="text-semibold"><i class="icon-file-plus"></i> Buat Transaksi Penjualan</span></h6>
				<div class="heading-elements">
					<div class="form-group">
						<div class="input-group">
							<div class="input-group-btn">
								<button type="button" class="btn btn-info btn-icon"><i class="icon-calendar3"></i></button>
							</div>
							<input type="text" class="form-control text-right" placeholder="Tanggal" name="tgl_penjualan" id="tgl_penjualan">
						</div>
					</div>
				</div>
			</div>
			<form action="{{ url(Request::segment(1).'/'.Request::segment(2)) }}" method="POST" enctype="multipart/form-data" class="form-horizontal" id="form-transaksi">
			<div class="panel-body">
				<div class="col-sm-12">
					<div class="form-group">
						<label class="col-lg-2 control-label text-right">No. Penjualan :</label>
						<div class="col-lg-2">
							<input type="text" class="form-control" placeholder="No. Penjualan" readonly name="no_penjualan" >
						</div>
						<div class="col-lg-3">
							<select id="costumer" class="form-control" name="costumer"></select>
						</div>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="form-group">
						<label class="col-lg-2 control-label text-right">Termin :</label>
						<div class="col-lg-2">
							<select class="form-control" name="termin" id="termin" readonly>
								<option value="1" selected>Cash</option>
								<option value="2">Credit</option>
							</select>
						</div>
						<div class="col-lg-3" id="opsi_cash">
							<select id="kas" class="form-control" name="kas"></select>
						</div>
						<div class="col-lg-6 hidden" id="opsi_credit">
                            <div class="row">
                                <div class="col-lg-3">
                                    <input type="text" class="form-control" name="hari" placeholder="Hari" value="12" id="hari">
                                </div>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" placeholder="Tanggal" id="tgl_jatuh_tempo" name="tgl_jatuh_tempo">
                                </div>

                            </div>
						</div>
					</div>
				</div>
			</div>
			<div class="table-responsive">
				<table class="table text-nowrap" id="table-transaksi">
					<thead class="bg-primary">
						<tr>
							<th>Nama Item</th>
							<th class="col-md-2 text-center">Qty</th>
							<th class="col-md-2 text-center">Satuan</th>
							<th class="col-md-2 text-right">Harga</th>
							<th class="col-md-2 text-right">Sub Total</th>
							<th class="text-center" style="width: 20px;"><i class="icon-gear"></i></th>
						</tr>
					</thead>
					<tbody>
						<tr class="tr-empty">
                            <td colspan="6" class="text-center text-bold">Item Belum Ada</td>
                        </tr>
					</tbody>
				</table>
			</div>
			<div class="panel-body" style="margin-bottom: -50px;">
				<div class="form-group">
					<div class="row">
						<div class="col-sm-6">
						</div>
						<div class="col-sm-6">
							<div class="content-group">
								<div class="table-responsive no-border">
									<table class="table table-xxs table-framed">
										<tbody>
											<tr>
												<th class="text-right">Total :</th>
												<td class="text-right"><input type="text" name="total_akhir" id="total_akhir" class="form-control input-xs text-right text-bold" readonly value="0"></td>
											</tr>
                                            <tr>
												<th class="text-right">Bayar :</th>
												<td class="text-right">
                                                    {{-- <input type="text" name="bayar" id="bayar" class="form-control input-xs text-right text-bold" value="0"> --}}
                                                    <div class="input-group">
														<input type="text" value="0" id="bayar" name="bayar" class="form-control input-xs text-right text-bold">
														<span id="btn-opsi-bayar" class="input-group-addon"><i class="icon-point-up"></i></span>
													</div>
                                                </td>
											</tr>
                                            <tr>
												<th class="text-right">Kembalian :</th>
												<td class="text-right"><input type="text" name="kembalian" id="kembalian" class="form-control input-xs text-right text-bold" readonly value="0"></td>
											</tr>
											<tr class="hidden" id="kolom_uang_muka">
												<th>Uang Muka:</th>
												<td class="text-right">
													<div class="input-group">
														<input type="text" value="0" id="uang_muka" name="uang_muka" class="form-control input-xs text-right text-bold" readonly>
														<span id="btn-uang-muka" class="input-group-addon"><i class="icon-plus-circle2"></i></span>
													</div>
												</td>
											</tr>
										</tbody>
									</table>
								</div>

							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="panel-footer"><a class="heading-elements-toggle" style="margin-top: 200px;"><i class="icon-more"></i></a>
				<div class="heading-elements">
					<div class="pull-right">
						<button style="width: 100%;" type="submit" class="btn btn-xlg btn-success"><b><i class="icon-typewriter"></i></b> CHECKOUT</button>
					</div>
				</div>
				</form>
			</div>
			</form>
		</div>
	</div>
</div>

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
                            <select name="kas_kredit" id="kas_kredit" class="form-control"></select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-2">Jumlah :</label>
                        <div class="col-lg-5">
                            <input type="text" class="form-control text-right" id="jumlah_uang_muka" name="jumlah_uang_muka">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-2">Keterangan :</label>
                        <div class="col-lg-10">
                            <textarea name="keterangan" id="keterangan" cols="30" rows="3" class="form-control"></textarea>
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


<div id="modal_opsi_bayar" class="modal fade" tabindex="-1" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-group text-center">
                    <button type="button" class="btn-harga-nominal btn btn-float btn-float-lg" data-nominal="50000">Rp. 50.000</button>
                    <button type="button" class="btn-harga-nominal btn btn-float btn-float-lg" data-nominal="100000">Rp. 100.000</button>
                    <button type="button" class="btn-harga-nominal btn btn-float btn-float-lg" data-nominal="150000">Rp. 150.000</button>
                    <button type="button" class="btn-harga-nominal btn btn-float btn-float-lg" data-nominal="200000">Rp. 200.000</button>
                    <button type="button" class="btn-harga-nominal btn btn-float btn-float-lg" data-nominal="250000">Rp. 250.000</button>
                </div>
                <div class="form-group text-center">
                    <button type="button" class="btn-harga-nominal btn btn-float btn-float-lg" data-nominal="300000">Rp. 300.000</button>
                    <button type="button" class="btn-harga-nominal btn btn-float btn-float-lg" data-nominal="350000">Rp. 350.000</button>
                    <button type="button" class="btn-harga-nominal btn btn-float btn-float-lg" data-nominal="400000">Rp. 400.000</button>
                    <button type="button" class="btn-harga-nominal btn btn-float btn-float-lg" data-nominal="450000">Rp. 450.000</button>
                    <button type="button" class="btn-harga-nominal btn btn-float btn-float-lg" data-nominal="500000">Rp. 500.000</button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')

<script src="{{ asset('back/assets/js/recta.js') }}"></script>
<script src="{{ asset('back/assets/js/recta_kasir.js') }}"></script>
<script src="{{ asset('back/assets/js/tambahan.js') }}"></script>
<script src="{{ asset('back/global_assets/js/plugins/forms/inputs/touchspin.min.js') }}"></script>
<script src="{{ asset('back/global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('back/jquery.number.js') }}"></script>
<script src="{{ asset('back/global_assets/js/plugins/ui/moment/moment.min.js') }}"></script>
<script src="{{ asset('back/global_assets/js/plugins/pickers/daterangepicker.js') }}"></script>
<script src="{{ asset('back/additional/loader.js') }}"></script>

@include('kasir.page.penjualan.partials.script_create')
<script>
	$(document).ready(function(){
        $.checkConnectionRecta({
            RECTA_API_KEY: '123456789',
            RECTA_PORT: '1811'
        });

		$.setupFormTambahPenjualan();

        $('#form-transaksi').submit(function(){
            let url = $(this).attr('action');
            event.preventDefault();
            var termin = $('#termin').val();
            var kas,uang_muka,keterangan,hari_jatuh_tempo,tgl_jatuh_tempo;

            if(termin == '1'){
                kas = $('#kas').val()
                hari_jatuh_tempo = '',
                tgl_jatuh_tempo = '';
                uang_muka = '';
            } else {
                kas = $('#kas_kredit').val()
                hari_jatuh_tempo = $('#hari').val();
                tgl_jatuh_tempo = $('#tgl_jatuh_tempo').val();
                uang_muka = $('#uang_muka').val();
            }


            let list_item = [];
            $('#table-transaksi tbody tr:not(.tr-empty)').each(function(){
                item = {}
                item ["item_id"]   = $(this).find('input#item_id').val();
                item ["name_item"] = $(this).find('td:eq(0) span').text();
                item ["qty"]       = $(this).find('input#qty').val();
                item ["satuan"]    = $(this).find('select#satuan_item').val();
                item ["harga"]     = $(this).find('input#harga').val();
                item ["sub_total"] = $(this).find('input#sub_total').val();
                list_item.push(item);
            });


            let formData = {
                no_penjualan : $('input[name="no_penjualan"]').val(),
                costumer : $('select[name="costumer"]').val(),
                tgl_penjualan : $('input[name="tgl_penjualan"]').val(),
                termin: $('#termin').val(),
                kas: kas,
                uang_muka: uang_muka,
                keterangan: $('#keterangan').val(),
                hari_jatuh_tempo : hari_jatuh_tempo,
                tgl_jatuh_tempo : tgl_jatuh_tempo,
                total: $('#total_akhir').val(),
                list_item: JSON.stringify(list_item),
                bayar: $('#bayar').val(),
                kembalian: $('#kembalian').val(),
            }


            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                beforeSend: function(xhr ){

                    if(Number(formData.total) > Number(formData.bayar)){
                        console.log(formData.bayar)
                        console.log(formData.total)
                        Swal.fire({ type: 'error', title: 'Peringatan', text: 'Total Bayar Kurang Dari Pembayaran' });
                        return false;
                    }
                    $.loaderStart('#panel-buat-item');
                },
                success: function(response){
                    if(response.status == "sukses"){
                        $.loaderStop('#panel-buat-item', 500);
                        Swal.fire({
                            title: 'Print Struk?',
                            text: "Apakah Anda Ingin Cetak Struk!",
                            type: 'info',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Iya, Print!',
                            cancelButtonText: 'Batal',
                        }).then((result) => {
                            if (result.value) {
                                $.rectaPrintStruk({
                                    RECTA_API_KEY: '123456789',
                                    RECTA_PORT: '1811',
                                    NAMA_WARUNG: 'WARUNG 5758',
                                    ALAMAT_WARUNG: "Jl. Poros Sumbarrang No.5 Borongpa'la'la, Patalassang, Gowa",
                                    TELP_WARUNG: '085242730448',
                                    AKUN_IG: '@waja5758',
                                    NO_TRANSAKSI: formData.no_penjualan,
                                    TGL: moment().format('DD/MM/YYYY h:mm:ss a'),
                                    LIST_ITEM: list_item,
                                    TOTAL_HARGA: formData.total,
                                    BAYAR: formData.bayar,
                                    KEMBALIAN: formData.kembalian
                                });

                                $.setupFormTambahPenjualan();
                            } else {
                                $.setupFormTambahPenjualan();
                            }
                        })

                    }
                },
                error: function(response){
                    $.loaderStop('#panel-buat-item', 500);
                    if ($.isEmptyObject(response.responseJSON.errors) == false) {
                        console.log(response.responseJSON.errors[Object.keys(response.responseJSON.errors)[0]])
                        Swal.fire({ type: 'error', title: 'Peringatan', text: response.responseJSON.errors[Object.keys(response.responseJSON.errors)[0]] })
                    }

                }

            });

        });
	})
</script>
@endsection

