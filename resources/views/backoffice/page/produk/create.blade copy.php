@extends('backoffice.layout.backofficelayout')
@section('title'){{ env("SET_TOKO", "NOT SET") }} | Data Master - Tambah Data Barang @endsection
@section('style')
<link rel="stylesheet" href="{{asset("back/modal-x/dist/css/bootstrap-extra-modal.css")}}">
<link rel="stylesheet" href="{{asset("back/jquery-ui-1.12.1.custom/jquery-ui.css")}}">

<style>
	.borderless td, .borderless th, .borderless tr {
        border: none;
        padding:0;
    }
    .remove-padding >tbody>tr>td, .remove-padding >tfoot>tr>td {
        padding: 0px;
        vertical-align: center;
    }
    .remove-padding >tbody>tr>td>input, .remove-padding >tfoot>tr>td>input {
        border: none; 
        border-width: 0; 
        box-shadow: none;
    }
</style>
@endsection
@section('content')
<!-- Page header -->
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
					<div class="alert bg-danger alert-styled-left">
						<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
						<ul>
							<li>
							<span class="text-semibold">Oh snap!</span> Change a few things up and <a href="#" class="alert-link">try submitting again</a>.
							</li>
							<li>
							<span class="text-semibold">Oh snap!</span> Change a few things up and <a href="#" class="alert-link">try submitting again</a>.
							</li>

						</ul>
					</div>

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
                                    <button type="button" class="btn btn-default btn-float btn-float-lg btn-jns-produk" data-item="produk_tunggal"><i class="icon-file-empty2"></i> <span>Produk Tunggal</span></button>
                                    <button type="button" class="btn btn-default btn-float btn-float-lg btn-jns-produk" data-item="produk_komposit"><i class="icon-file-text3"></i> <span>Produk Komposit</span></button>
								</div>
							</div>
						</div>

						<div class="panel panel-success panel-bordered panel-collapsed hidden" id="panel-resep-produk-komposit">
							<div class="panel-heading">
								<h6 class="panel-title">Resep Produk Komposit<a class="heading-elements-toggle"><i class="icon-more"></i></a></h6>
								<div class="heading-elements">
									<ul class="icons-list">
										<li><a data-action="collapse" class=""></a></li>
									</ul>
								</div>
							</div>

							<div class="panel-body">
								<div class="form-group has-feedback">
									<input type="text" class="form-control input-sm" placeholder="Masukan Nama Bahan" id="input-pencarian-bahan">
									<div class="form-control-feedback">
										<i class="icon-search4"></i>
									</div>
								</div>
			
								<table class="table table-bordered table-framed table-xs remove-padding" id="tabel-bahan-resep">
									<thead>
										<tr>
											<th class="text-center">Bahan</th>
											<th width="20">Satuan</th>
											<th width="20">Jumlah</th>
											<th class="text-center" width="20"><i class="icon-gear"></i></th>
										</tr>
									</thead>
									<tbody>
										<tr class="item-not-found">
											<td class="text-center text-bold" colspan="4">Bahan Belum ada</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>

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

@endsection
@section('script')
<script src="{{ asset('back/global_assets/js/plugins/jquery-mask/dist/jquery.mask.js') }}"></script>
<script src="{{ asset('back/global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('back/global_assets/js/plugins/uploaders/fileinput/plugins/purify.min.js') }}"></script>
<script src="{{ asset('back/global_assets/js/plugins/uploaders/fileinput/plugins/sortable.min.js') }}"></script>
<script src="{{ asset('back/global_assets/js/plugins/uploaders/fileinput/fileinput.min.js') }}"></script>
<script src="{{ asset('back/assets/js/tambahan.js') }}"></script>
<script src="{{asset("back/modal-x/dist/js/bootstrap-extra-modal.min.js")}}"></script>
<script src="{{asset("back/jquery-ui-1.12.1.custom/jquery-ui.js")}}"></script>

<script>
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
  // Buttons inside zoom modal
    var previewZoomButtonClasses = {
        toggleheader: 'btn btn-default btn-icon btn-xs btn-header-toggle',
        fullscreen: 'btn btn-default btn-icon btn-xs',
        borderless: 'btn btn-default btn-icon btn-xs',
        close: 'btn btn-default btn-icon btn-xs'
    };

    // Icons inside zoom modal classes
    var previewZoomButtonIcons = {
        prev: '<i class="icon-arrow-left32"></i>',
        next: '<i class="icon-arrow-right32"></i>',
        toggleheader: '<i class="icon-menu-open"></i>',
        fullscreen: '<i class="icon-screen-full"></i>',
        borderless: '<i class="icon-alignment-unalign"></i>',
        close: '<i class="icon-cross3"></i>'
    };

    // File actions
    var fileActionSettings = {
        zoomClass: 'btn btn-link btn-xs btn-icon',
        zoomIcon: '<i class="icon-zoomin3"></i>',
        dragClass: 'btn btn-link btn-xs btn-icon',
        dragIcon: '<i class="icon-three-bars"></i>',
        removeClass: 'btn btn-link btn-icon btn-xs',
        removeIcon: '<i class="icon-trash"></i>',
        indicatorNew: '<i class="icon-file-plus text-slate"></i>',
        indicatorSuccess: '<i class="icon-checkmark3 file-icon-large text-success"></i>',
        indicatorError: '<i class="icon-cross2 text-danger"></i>',
        indicatorLoading: '<i class="icon-spinner2 spinner text-muted"></i>'
    };



window.onload = function () {
	show_loading('#panel-buat-barang');
	hide_loading('#panel-buat-barang', 500);
}

$(document).ready(function(){
	$('.select-search').select2();
	$('.qty').mask('0#');
	$('.money').mask('#,##0', { reverse: true });
	$('.file-input').fileinput({
		// initialPreview: [
		// 	'<img src="https://images-na.ssl-images-amazon.com/images/I/61tZUbTlwhL._AC_SY679_.jpg" alt="" class="file-preview-image">'
		// ],
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
        previewZoomButtonClasses: previewZoomButtonClasses,
        previewZoomButtonIcons: previewZoomButtonIcons,
        fileActionSettings: fileActionSettings
    });

	$('.btn-jns-produk').on('click', function(){
		var container = $(this).closest('div');
		container.find('button.btn-jns-produk').removeClass("btn-success").addClass("btn-default");
		$(this).addClass("btn-success")
		
		if($(this).attr("data-item") == "produk_komposit") {
			$("#panel-resep-produk-komposit").removeClass("hidden");
			$("#panel-resep-produk-komposit").find("a[data-action='collapse']").click();
			$('#jenis_produk').val(1);
		} else {
			$("#panel-resep-produk-komposit").addClass("hidden");
			$("#panel-resep-produk-komposit").find("a[data-action='collapse']").click();
			$('#jenis_produk').val(0);
		}

	});

	$('#kategori_produk').select2({
		minimumInputLength: 2,
        placeholder: 'Cari Kategori Produk',
        ajax: {
            url: "{{ url('getkategori') }}",
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results:  $.map(data, function (item) {
                        return {
                        text: item.kategori,
                        id: item.id,
                        }

                    })
                };
            },
            cache: true,
        },
        language: {
            noResults: function(term){
                var element = $('#kategori_produk');
				var isi = element.data('select2');

				if(term != ""){
					var result = '<span>Tidak Ada Hasil !</span><br><button class="btn btn-sm btn-success" type="button" id="modal-create-kategori"><i class="icon-diff-added"></i>Klik untuk membuat</button>';
					return $(result).on('click', function(){
						$.ajax({
							url: "{{ url('data-master/kategori')}}",
							method: "POST",
							data: {
								_token: $('meta[name="csrf-token"]').attr('content'),
								kategori : $.trim(isi.results.lastParams.term)
							},
							success: function(data){
								var newOption = new Option(data.kategori, data.id, false, false);
								element.append(newOption).trigger('change');
								element.select2("close");
							}, 
							fail: function(xhr, textStatus, errorThrown){
								alert("Ada Masalah Saat Menambahkan Data");
								console.log(xhr)
							}
						});
					});
				}

            }
        }
		
    });

	$('#satuan_produk').select2({
        minimumInputLength: 2,
        placeholder: 'Cari Satuan Produk',
        ajax: {
            url: "{{ url('getsatuan') }}",
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results:  $.map(data, function (item) {
                        return {
                        text: item.satuan,
                        id: item.id,
                        }

                    })
                };
            },
            cache: true,
        },
        language: {
            noResults: function(term){
                var element = $('#satuan_produk');
				var isi = element.data('select2');

				if(term != ""){
					var result = '<span>Tidak Ada Hasil !</span><br><button class="btn btn-sm btn-success" type="button" id="modal-create-kategori"><i class="icon-diff-added"></i>Klik untuk membuat</button>';
					return $(result).on('click', function(){
						$.ajax({
							url: "{{ url('data-master/satuan')}}",
							method: "POST",
							data: {
								_token: $('meta[name="csrf-token"]').attr('content'),
								satuan : $.trim(isi.results.lastParams.term)
							},
							success: function(data){
								var newOption = new Option(data.satuan, data.id, false, false);
								element.append(newOption).trigger('change');
								element.select2("close");
							}, 
							fail: function(xhr, textStatus, errorThrown){
								alert("Ada Masalah Saat Menambahkan Data");
								console.log(xhr)
							}
						});
					});
				}

            }
        }
    });

	$("#input-pencarian-bahan").autocomplete({
        source: function( request, response ) {
          // Fetch data
          $.ajax({
            url: "{{ url('getproduk') }}",
            type: 'GET',
            dataType: "json",
            data: {
               _token: $('meta[name="csrf-token"]').attr('content'),
               term: request.term
            },
            success: function( data ) {
                response($.map(data, function(item) {
                    return {
                        label: item.nama_produk,
                        value: item.nama_produk,
						satuan : item.satuan.satuan,
						nama_produk: item.nama_produk,
						obj: item
                    };
                }));
            }
          });
        },
        select: function (event, ui) {
			var table = $("#tabel-bahan-resep");
			table.find('tbody tr.item-not-found').remove();

			var html = `<tr><td><input type="hidden" id="bahan_id" name="bahan_id"><input type="text" class="form-control" name="nama_bahan" id="nama_bahan" readonly></td>
			<td><input type="text" class="form-control" readonly name="satuan_bahan" id="satuan_bahan"></td>
			<td><input type="text" class="form-control text-center" name="qty_bahan" id="qty_bahan"></td>
			<td class="text-center"><button type="button" class="btn btn-link"><i class="text-danger icon-trash"></i></button></td></tr>`;
			
			$(html).find('input#bahan_id').val(ui.item.id).end()
					.find("input#nama_bahan").val(ui.item.nama_produk).end()
					.find("input#satuan_bahan").val(ui.item.satuan).end()
					.find("input#qty_bahan").val(1).end()
					.find("i").on("click", function(){
						event.preventDefault();
						$(this).closest("tr").detach()
						if(table.find('tbody tr').length == 0) {
							var not_found = `<tr class="item-not-found"><td class="text-center text-bold" colspan="4">Bahan Belum ada</td></tr>`;
							table.find('tbody').append(not_found);
						}
					}).end()
					.appendTo(table.find('tbody'))

			$(this).val("")
           	return false;
        }
    });


	$('#form-tambah-produk').on('submit', function() {
		event.preventDefault();
		var formData = new FormData(this);
		var url = $(this).attr("action")

		console.log(url)


		$.ajax({
			url : url,
			data: formData,
			type: "POST",
			contentType: false,
        	processData: false,
			success: function(respon){
				console.log(respon);
			},
			fail: function(jqXHR, textStatus, errorThrown){
				alert(textStatus);
				return false;
			},
			error: function(xhr){
				var res = xhr.responseJSON;
				if ($.isEmptyObject(res) == false) {
					$('#loading').removeClass("overlay").addClass("hidden");
					var html = `<div class="alert alert-danger alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<h4><i class="icon fa fa-times-circle"></i> Peringatan !</h4>
						<ul></ul>
						</div>`;
					$('div.box-body').prepend(html).find('div.alert');
					$.each(res, function (key, value) {
						$(`<li>${value}</li>`).appendTo('div.alert > ul');
					});
				}
			}
		});



    	// var tr = $('#tabel-detail-transaksi tbody tr:not(#adax)');
		// if ($('#supplier').val() == null) {
		// 	Swal.fire({ icon: 'error', title: 'Peringatan', text: 'Supplier Belum DIpilih !' })
		// 	return false;
		// }
		// if ($('#tgl_transaksi').val() == null) {
		// 	Swal.fire({ icon: 'error', title: 'Peringatan', text: 'Supplier Belum DIpilih !' })
		// 	return false;
		// }
    	// if (tr.length == 0) {
		// 	Swal.fire({ icon: 'error', title: 'Peringatan', text: 'Tanggal Belum Dipilih !' })
		// 	return false;
		// }
    	// let jsonObj = [];
    	// tr.each(function(){
	    //     item = {}
	    //     item ["produk_id"]  = $(this).find('input[name="kode_barang[]"]').val();
	    //     item ["qty"]        = $(this).find('input[name="kode_barang[]"]').attr('data-harga');
	    //     jsonObj.push(item);
    	// });
		// $("<input />").attr("type", "hidden").attr("name", "detail").attr("value", JSON.stringify(jsonObj)).appendTo("#form-transaksi");
	});





});
</script>
@endsection

