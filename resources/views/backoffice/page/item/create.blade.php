@extends('backoffice.layout.backofficelayout')
@section('title'){{ env("SET_TOKO", "NOT SET") }} | Data Master - Tambah Data Item @endsection
@section('style')
<link rel="stylesheet" href="{{asset("back/modal-x/dist/css/bootstrap-extra-modal.css")}}">
<link rel="stylesheet" href="{{asset("back/jquery-ui-1.12.1.custom/jquery-ui.css")}}">

<style>

</style>
@endsection
@section('content')
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<a href="{{ url('/data-master/item') }}"><h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Data Item</span></h4></a>
		<a class="heading-elements-toggle"><i class="icon-more"></i></a></div>
	</div>

	<div class="breadcrumb-line"><a class="breadcrumb-elements-toggle"><i class="icon-menu-open"></i></a>
		<ul class="breadcrumb">
			<li><a href="index.html"><i class="icon-grid position-left"></i> Data Master</a></li>
			<li><a href="components_tabs.html">Item</a></li>
			<li class="active">Tambah Item</li>
		</ul>
	</div>
</div>

<div class="content">
	<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-flat border-top-xlg border-top-green-600" id="panel-buat-item">
				<div class="panel-heading">
					<h5 class="panel-title"><span class="text-semibold"><i class="icon-file-plus"></i> Buat Data Item</span></h5>
				</div>

				<div class="panel-body">
					<form action="{{ url(Request::segment(1).'/'.Request::segment(2)) }}" method="POST" enctype="multipart/form-data" class="form-horizontal" id="form-transaksi">
						@csrf

					<div class="tabbable tab-content-bordered">
						<ul class="nav nav-tabs nav-tabs-highlight">
							<li class="active">
								<a href="#bordered-tab1" data-toggle="tab"><i class="icon-grid6"></i> Spesifikasi</a>
							</li>
							<li>
								<a href="#bordered-tab2" data-toggle="tab"><i class="icon-price-tag2"></i> Satuan</a>
							</li>
							<li>
								<a href="#bordered-tab3" data-toggle="tab"><i class="icon-image2"></i> Gambar</a>
							</li>
							<li>
								<a href="#bordered-tab4" data-toggle="tab"><i class="icon-book3"></i> Akutansi</a>
							</li>
						</ul>

						<div class="tab-content">
							<div class="tab-pane has-padding  active" id="bordered-tab1">
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label class="col-lg-3 control-label text-bold">Kode:</label>
											<div class="col-lg-6">
												<input type="text" class="form-control" readonly placeholder="Kode Item" id="kode_item" name="kode_item" value="{{ $kode_item }}">
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-3 control-label text-bold">Barcode :</label>
											<div class="col-lg-6">
												<input type="text" class="form-control" placeholder="Barcode Item" id="barcode" name="barcode">
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-3 control-label text-bold">Nama Item :</label>
											<div class="col-lg-9">
												<input type="text" class="form-control" placeholder="Nama Item" id="nama_item" name="nama_item">
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-3 control-label text-bold">Tipe Item :</label>
											<div class="col-lg-6">
												<select data-placeholder="Pilih Tipe Item" class="form-control select" id="tipe_item" name="tipe_item">
													<option value="0">Barang Jadi</option>
													<option value="1">Barang Hasil Produksi</option>
													<option value="2">Bahan Baku</option>
												</select>
											</div>
										</div>
                                        <div class="form-group">
											<label class="col-lg-3 control-label text-bold">Kategori Item :</label>
											<div class="col-lg-6">
												<select data-placeholder="Pilih Kategori Item" class="form-control select" id="kategori_item" name="kategori_item">
													<option value="0">Non Makanan & Minuman</option>
													<option value="1">Makanan</option>
													<option value="2">Minuman</option>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-3 control-label text-bold">Opsi Jual:</label>
											<div class="col-lg-9">
												<div class="checkbox">
													<label>
														<input type="checkbox" name="opsi_jual" id="opsi_jual">
														Jual
													</label>
												</div>
											</div>
										</div>
									</div>

								</div>

							</div>

							<div class="tab-pane has-padding" id="bordered-tab2">
								<div class="row">
									<div class="col-sm-8">
                                        <table class="table text-nowrap" id="table-list-satuan" style="margin-bottom: 10px;">
                                            <thead class="bg-blue">
                                                <tr>
                                                    <th class="col-md-3 text-center">Satuan</th>
                                                    <th class="col-md-3 text-center">Qty Konversi</th>
                                                    <th class="col-md-3 text-center">Harga Jual</th>
                                                    <th class="col-md-3 text-center">Harga Beli</th>
                                                    <th class="text-center" style="width: 20px;"><i class="icon-cogs"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <select name="satuan" id="satuan" class="form-control">
                                                            <option value="" disabled selected>Pilih Satuan</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" placeholder="Qty Konversi" id="qty_konversi">
                                                            <span class="input-group-addon first"></span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control text-right" placeholder="Harga Jual" name="harga_jual">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control text-right" placeholder="Harga Beli" name="harga_beli">
                                                    </td>
                                                    <td class="text-center">
                                                        <ul class="icons-list">
                                                            <li class="text-danger-600"><a id="button_delete" href="#"><i class="icon-cancel-circle2"></i></a></li>
                                                        </ul>
                                                    </td>
                                                </tr>

                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="5" class="text-center">
                                                        <button type="button" class="btn btn-success btn-xs" id="btn-add-row"><i class="icon-plus-circle2 position-left"></i> Tambahkan Satuan</button>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                        <div class="panel panel-default border-grey">
                                            <div class="panel-heading">
                                                <h6 class="panel-title">Set Stock Warning<a class="heading-elements-toggle"><i class="icon-more"></i></a></h6>
                                            </div>
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <label class="col-lg-3 control-label text-bold">Qty Minimal:</label>
                                                    <div class="col-lg-2">
                                                        <input type="text" class="form-control text-right" placeholder="Qty Minimal" id="qty_minimal" name="qty_minimal">
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control satuan-text" placeholder="Satuan" id="satuan_minimal" name="satuan_minimal" readonly>
                                                            <span class="input-group-btn">
                                                                <button class="btn bg-warning refresh" type="button"><i class="icon-loop3"></i></button>
                                                            </span>
                                                        </div>
											        </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel panel-default border-grey">
                                            <div class="panel-heading">
                                                <h6 class="panel-title">Satuan Default Penjualan/Pembelian/Stock<a class="heading-elements-toggle"><i class="icon-more"></i></a></h6>
                                            </div>

                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <label class="col-lg-3 control-label text-bold">Satuan Jual:</label>
                                                    <div class="col-lg-3">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control satuan-text" readonly placeholder="Satuan Penjualan" id="satuan_penjualan" name="satuan_penjualan">
                                                            <span class="input-group-addon btn bg-warning refresh"><i class="icon-loop3"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-3 control-label text-bold">Satuan Beli:</label>
                                                    <div class="col-lg-3">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control satuan-text" readonly placeholder="Satuan Pembelian" id="satuan_pembelian" name="satuan_pembelian">
                                                            <span class="input-group-addon btn bg-warning refresh"><i class="icon-loop3"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-3 control-label text-bold">Satuan Stock:</label>
                                                    <div class="col-lg-3">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control satuan-text" readonly placeholder="Satuan Stock" id="satuan_stock" name="satuan_stock">
                                                            <span class="input-group-addon btn bg-warning refresh"><i class="icon-loop3"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


									</div>
								</div>
							</div>

							<div class="tab-pane has-padding" id="bordered-tab3">

								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label class="col-lg-3 control-label text-bold">Gambar Item:</label>
											<div class="col-lg-9">
												<input type="file" class="file-input" name="gambar_item" accept="image/x-png,image/jpeg">
											</div>
										</div>
									</div>
								</div>


							</div>

							<div class="tab-pane has-padding" id="bordered-tab4">

								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label class="col-lg-3 control-label">Pembelian :</label>
											<div class="col-lg-9">
												<select name="pembelian" id="pembelian" class="form-control select-2-akun"></select>
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-3 control-label">HPP :</label>
											<div class="col-lg-9">
												<select name="hpp" id="hpp" class="form-control select-2-akun"></select>
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-3 control-label">Penjualan :</label>
											<div class="col-lg-9">
												<select name="penjualan" id="penjualan" class="form-control select-2-akun"></select>
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-3 control-label">Retur Penjualan :</label>
											<div class="col-lg-9">
												<select name="retur_penjualan" id="retur_penjualan" class="form-control select-2-akun"></select>
											</div>
										</div>
									</div>
								</div>
							</div>

						</div>
					</div>
				</div>
				<div class="panel-footer"><a class="heading-elements-toggle"><i class="icon-more"></i></a>
					<div class="heading-elements">
						<div class="pull-right">
							<a href="{{ url('data-master/item') }}" class="btn btn-default"><b><i class="icon-circle-left2"></i></b> Kembali</a>
							<button type="submit" class="btn btn-success"><b><i class="icon-floppy-disk"></i></b> Simpan</button>
						</div>
					</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>


@endsection
@section('script')
<script src="{{ asset('back/global_assets/js/plugins/jquery-mask/dist/jquery.mask.js') }}"></script>
<script src="{{ asset('back/global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('back/global_assets/js/plugins/uploaders/fileinput/plugins/purify.min.js') }}"></script>
<script src="{{ asset('back/global_assets/js/plugins/uploaders/fileinput/plugins/sortable.min.js') }}"></script>
<script src="{{ asset('back/global_assets/js/plugins/uploaders/fileinput/fileinput.min.js') }}"></script>
<script src="{{ asset('back/assets/js/tambahan.js') }}"></script>
<script src="{{ asset('back/jquery.number.js') }}"></script>
<script src="{{asset("back/modal-x/dist/js/bootstrap-extra-modal.min.js")}}"></script>
<script src="{{asset("back/jquery-ui-1.12.1.custom/jquery-ui.js")}}"></script>
@include('backoffice.page.item.partials.script_create')
<script>
var akun = {!! json_encode($akun) !!};
var modalTemplate = '<div class="modal-dialog modal-lg" role="document">\n' +
        '  <div class="modal-content">\n' +
        '    <div class="modal-header">\n' +
        '      <div class="kv-zoom-actions btn-group">{toggleheader}{fullscreen}{borderless}{close}</div>\n' +
        '      <h6 class="modal-title">{heading} <small><span class="kv-zoom-title"></span></small></h6>\n' +
        '    </div>\n' +
        '    <div class="modal-body">\n' +
        '      <div class="floating-buttons btn-group"></div>\n' +
        '      <div class="kv-zoom-body file-zoom-content"></div>\n' + '{prev} {next}\n' +
        '    </div>\n' +
        '  </div>\n' +
        '</div>\n';


window.onload = function () {
	show_loading('#panel-buat-item');
	hide_loading('#panel-buat-item', 500);
}


document.addEventListener('DOMContentLoaded', function() {

    $('#opsi_jual').change(function(){
        this.checked ? $(this).attr( 'checked', true ) : $(this).attr( 'checked', false )
    });

	$("#pembelian").select2({
		allowClear: true,
		placeholder: 'Silahkan Pilih Akun',
		dropdownAutoWidth : true,
		ajax: {
			url: "{{ url('getakunselect2') }}",
			dataType: 'json',
			data: function (params) {
				return {
					q: params.term,
				};
			},
			delay: 250,
			processResults: function (data) {
				return {
					results:  $.map(data, function (item) {
						return {
							text: item.kode_akun + ' - ' +item.nama_akun,
							id: item.id,
							item : item
						}
					})
				};
		},
		cache: true,
		}
	}).append(new Option(akun.akun_pembelian.kode+' - '+akun.akun_pembelian.value , akun.akun_pembelian.akun_id, false, false)).trigger('change');

	$("#hpp").select2({
		allowClear: true,
		placeholder: 'Silahkan Pilih Akun',
		dropdownAutoWidth : true,
		ajax: {
			url: "{{ url('getakunselect2') }}",
			dataType: 'json',
			data: function (params) {
				return {
					q: params.term,
				};
			},
			delay: 250,
			processResults: function (data) {
				return {
					results:  $.map(data, function (item) {
						return {
							text: item.kode_akun + ' - ' +item.nama_akun,
							id: item.id,
							item : item
						}
					})
				};
		},
		cache: true,
		}
	}).append(new Option(akun.akun_hpp.kode+' - '+akun.akun_hpp.value , akun.akun_hpp.akun_id, false, false)).trigger('change');

	$("#penjualan").select2({
		allowClear: true,
		placeholder: 'Silahkan Pilih Akun',
		dropdownAutoWidth : true,
		ajax: {
			url: "{{ url('getakunselect2') }}",
			dataType: 'json',
			data: function (params) {
				return {
					q: params.term,
				};
			},
			delay: 250,
			processResults: function (data) {
				return {
					results:  $.map(data, function (item) {
						return {
							text: item.kode_akun + ' - ' +item.nama_akun,
							id: item.id,
							item : item
						}
					})
				};
		},
		cache: true,
		}
	}).append(new Option(akun.akun_penjualan.kode+' - '+akun.akun_penjualan.value , akun.akun_penjualan.akun_id, false, false)).trigger('change');

	$("#retur_penjualan").select2({
		allowClear: true,
		placeholder: 'Silahkan Pilih Akun',
		dropdownAutoWidth : true,
		ajax: {
			url: "{{ url('getakunselect2') }}",
			dataType: 'json',
			data: function (params) {
				return {
					q: params.term,
				};
			},
			delay: 250,
			processResults: function (data) {
				return {
					results:  $.map(data, function (item) {
						return {
							text: item.kode_akun + ' - ' +item.nama_akun,
							id: item.id,
							item : item
						}
					})
				};
		},
		cache: true,
		}
	}).append(new Option(akun.akun_retur_penjualan.kode+' - '+akun.akun_retur_penjualan.value , akun.akun_retur_penjualan.akun_id, false, false)).trigger('change');


    $('#tipe_item').select2();
    $('#kategori_item').select2();

	$('.file-input').fileinput({
        browseLabel: 'Pilih Gambar',
        browseIcon: '<i class="icon-file-plus"></i>',
        uploadIcon: '<i class="icon-file-upload2"></i>',
        removeIcon: '<i class="icon-cross3"></i>',
        layoutTemplates: {
            icon: '<i class="icon-file-check"></i>',
            modal: modalTemplate
		},
		showUpload: false,
        initialCaption: "Tidak Ada File Terpilih",
    });

    $('#form-transaksi').submit(function(){
        event.preventDefault();
        let url = $(this).attr('action');
        let myFormData = new FormData();
        let list_item = [];
    	$('#table-list-satuan tbody tr').each(function(index, value){
	        item = {}
	        item ["satuan_id"] = $(value).find('select#satuan').val();
	        item ["qty_konversi"] = $(value).find('input#qty_konversi').val();
	        item ["harga_jual"] = $(value).find('input[name="harga_jual"]').val();
	        item ["harga_beli"] = $(value).find('input[name="harga_beli"]').val();
	        list_item.push(item);
    	});

        myFormData.append('kode', $('#kode_item').val());
        myFormData.append('barcode', $('#barcode').val());
        myFormData.append('nama_item', $('#nama_item').val());
        myFormData.append('tipe_item', $('#tipe_item').val());
        myFormData.append('kategori_item', $('#kategori_item').val());
        myFormData.append('opsi_jual', $('#opsi_jual').is(":checked") ? 1 : 0);
        myFormData.append('qty_minimal', $('#qty_minimal').val());
        myFormData.append('satuan_minimal', $('#satuan_minimal').val());
        myFormData.append('satuan_penjualan', $('#satuan_penjualan').val());
        myFormData.append('satuan_pembelian', $('#satuan_pembelian').val());
        myFormData.append('satuan_stock', $('#satuan_stock').val());
        myFormData.append('pembelian', $('#pembelian').val());
        myFormData.append('hpp', $('#hpp').val());
        myFormData.append('penjualan', $('#penjualan').val());
        myFormData.append('retur_penjualan', $('#retur_penjualan').val());
        if($('input[name="gambar_item"]').get(0).files.length !== 0){
            myFormData.append('gambar_item', $('input[name="gambar_item"]')[0].files[0]);
        }
        myFormData.append('list_satuan', JSON.stringify(list_item));

        $.ajax({
            url: url,
            type: 'POST',
            data: myFormData,
            processData : false,
            contentType : false,
            beforeSend: function(){
                $.loaderStart('#panel-buat-item');
            },
            success: function(response){
                if(response.status == "sukses"){
                    window.location.href = "{{ url('data-master/item') }}";
                }
            },
            error: function(response){
                if ($.isEmptyObject(response.responseJSON.errors) == false) {
                    let key = Object.keys(response.responseJSON.errors);
                    Swal.fire({ type: 'error', title: 'Peringatan', text: response.responseJSON.errors[Object.keys(response.responseJSON.errors)[0]] })
                }
            },
            complete: function(){
                $.loaderStop('#panel-buat-item');
            }
        });


    });

});

</script>
@endsection
