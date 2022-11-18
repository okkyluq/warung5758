@extends('backoffice.layout.backofficelayout')
@section('title'){{ env("SET_TOKO", "NOT SET") }} | Data Master - Data Item @endsection
@section('style')
<style>
	div.tooltip {
		z-index: 9999;
	}
</style>
@endsection
@section('content')
<div class="content">
	<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-flat border-top-xlg border-top-slate-800">
				<div class="panel-heading">
					<h5 class="panel-title"><span class="text-semibold"><i class="icon-table2"></i> Daftar Item</span></h5>
				</div>

				<div class="panel-body">
					<div class="pull-right">
						<a href="{{ url(Request::url().'/create') }}" class="btn bg-primary-800 btn-labeled text-bold"><b><i class="icon-plus-circle2"></i></b> Buat Item Baru</a>
					</div>
					<div class="pull-left">
						<button type="button" class="btn bg-success-600 btn-labeled text-bold" id="btn-modal-import"><b><i class="icon-plus-circle2"></i></b> Import Item</button>
					</div>
				</div>

				<table class="table table-bordered table-framed table-sm datatable-basic">
					<thead>
						<tr class="bg-slate-800 text-semibold">
							<th width="200">Kode Item</th>
							<th>Barcode</th>
							<th>Nama Item</th>
							<th>Tipe Item</th>
							<th class="text-center" width="80"><i class="icon-gear"></i></th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
</div>


<div id="modal_default" class="modal fade" style="display: none;">
	<div class="modal-dialog" >
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<h5 class="modal-title">Form Upload Import Item</h5>
			</div>

			<div class="modal-body" >
				<form action="{{ url('data-master/item/import') }}" class="form-horizontal" method="POST" enctype="multipart/form-data" id="form-import-item">
					{{ csrf_field() }}
					<div class="form-group">
						<label class="control-label col-lg-3">File Excel :</label>
						<div class="col-lg-9">
							<input type="file" class="file-styled" name="excel" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-lg-3">Jumlah Satuan :</label>
						<label class="radio-inline">
							<input type="radio" name="jumlah_satuan" checked="checked" value="satu">
							1 Satuan
						</label>
						<label class="radio-inline">
							<input type="radio" name="jumlah_satuan" value="dua">
							2 Satuan
						</label>
						<label class="radio-inline">
							<input type="radio" name="jumlah_satuan" value="tiga">
							3 Satuan
						</label>
					</div>
					<hr>
					<a href="{{ asset('back/template-excel/Template-Import-Item-1-satuan.xlsx') }}"><i class="icon-download"></i> Download Template Import Excel 1 Satuan</a><br>
					<a href="{{ asset('back/template-excel/Template-Import-Item-2-satuan.xlsx') }}"><i class="icon-download"></i> Download Template Import Excel 2 Satuan</a><br>
					<a href="{{ asset('back/template-excel/Template-Import-Item-3-satuan.xlsx') }}"><i class="icon-download"></i> Download Template Import Excel 3 Satuan</a>



			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Tutup</button>
				<button type="submit" class="btn bg-primary-600 btn-labeled text-bold" id="btn-modal-import"><b><i class="icon-file-upload2"></i></b> Upload</button>
			</form>
			</div>
		</div>
	</div>
</div>

<div id="modal_log" class="modal fade" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<h5 class="modal-title">Catatan Proses Import</h5>
			</div>

			<div class="modal-body">
				<pre style="padding:10; tab-size:0; height: 200px;">

				</pre>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Tutup</button>
			</form>
			</div>
		</div>
	</div>
</div>

@endsection
@section('script')
<script src="{{ asset('back/global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('back/assets/js/datatable.custom.js') }}"></script>
<script src="{{ asset('back/global_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script src="{{ asset('back/assets/js/tambahan.js') }}"></script>
@if(session()->has('success'))
<script>
	$(document).ready(function(){
		swal( 'Success!', "{{ session()->get('success') }}", 'success' );
	});
</script>
@endif
@if(session()->has('failed'))
<script>
	$(document).ready(function(){
		swal( 'Failed!', "{{ session()->get('success') }}", 'error' );
	});
</script>
@endif
<script>
	// show_loading('#panel-buat-item');
	// hide_loading('#panel-buat-item', 500);
	// Default file input style
	$(".file-styled").uniform({
		fileButtonClass: 'action btn btn-default'
	});

    var oTable = $('.datatable-basic').DataTable({
		processing: true,
		lengthChange: true,
		serverSide: true,
		scrollX: true,
		ajax : {
			url : "{{ url(Request::url()) }}",
		},
		language: {
    		searchPlaceholder: 'Cari Berdasarkan Nama Item',
		},
		columnDefs :[
			{"target" : 0, "className" : 'text-center'},
			{"target" : 1, "className" : 'text-left'},
			{"target" : 2, "className" : 'text-left'},
			{"target" : 3, "className" : 'text-left'},
			{"target" : 4, "className" : 'text-left'},
		],
		columns: [
			{data : 'kode_item', name : 'kode_item', orderable: false, searchable: true, class: 'text-center'},
			{data : 'barcode', name : 'barcode', orderable: false, searchable: true, class: 'text-center'},
			{data : 'nama_item', name : 'nama_item', orderable: false, searchable: true, class: 'text-center'},
			{data : 'tipe_item', name : 'tipe_item', orderable: false, searchable: false, class: 'text-center'},
			{data : 'action', name : 'action', orderable: false, searchable: false, class: 'text-center'},
		]
	});

	oTable.on('draw', function(){
		$(this).find("a#button_delete").on('click', function(){
			swal({
				title: 'Peringatan!',
				text: "Apakah Anda Yakin Ingin Menghapus Data?",
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ya, Hapus!',
				cancelButtonText: 'Batal',
				footer: '<a class="text-danger"> Perhatian : Semua Data Transaksi Item Terkait Akan Hilang</a>'
			}).then((result) => {
				if (result.value) {
					$.ajax({
						type : 'DELETE',
						url : "{{ url(Request::url()) }}"+'/'+ $(this).attr('data-id'),
						data : { _method : 'delete', _token: $('meta[name="csrf-token"]').attr('content')},
						success : function(data, status, xhr){
                            if(xhr.status == 200){
                                Swal.fire({ type: 'success', title: 'Success', text: data.message });
                                oTable.ajax.reload();
                            }
						},
						error : function(xhr, status, error){
                            if (xhr.status == 405){
                                Swal.fire({ type: 'error', title: 'Peringatan', text: xhr.responseJSON.message})
                            }
						}
					});
				}
			})
		});
	});

	$('#btn-modal-import').on('click', function(){
		// show_loading('.modal-dialog');
		$('#modal_default').modal('show')

	});



	$('#form-import-item').submit(function(){
		event.preventDefault();

		if($('input[name="excel"]').val() == ''){
			Swal.fire({
				type: 'error',
				title: 'Peringatan',
				customClass: 'swal-wide',
				text: 'File Masih Kosong'
			});
			return false;
		}

		var link = $(this).attr('action');
    	var form_data = new FormData(this);
		var file_data = $(this).find('input[name="excel"]').prop('files')[0];
		var jumlah_satuan = $('input[name="jumlah_satuan"]:checked').val();
		form_data.append('excel', file_data);
		form_data.append('jumlah_satuan', jumlah_satuan);

		// console.log(form_data);
		Swal.showLoading()
		$.ajax({
			url : link,
			type : "POST",
			cache: false,
			contentType: false,
			processData: false,
			data : form_data,
			success : function(data,status,xhr){
				oTable.ajax.reload();
				Swal.close();
				$('input[name="excel"]').val("");
				$.uniform.update();
				var modal_log = $('#modal_log');
				modal_log.find('pre').html('');

				$.each(data.failure, function(index, element){
					$(`<code>Baris ${element.row} ${element.errors[0]} </code><br>`).appendTo(modal_log.find('pre'));
				});

				var row_total = `<br><code class="text-info">Total Data Import : ${data.total_row}</code><br>`;
				var row_success = `<code class="text-success">Total Berhasil DiImport  : ${data.row_success}</code><br>`;
				var row_fail = `<code class="text-danger">Total Gagal DiImport  : ${data.row_fail}</code>`;

				modal_log.find('pre').append(row_total);
				modal_log.find('pre').append(row_success);
				modal_log.find('pre').append(row_fail);
				modal_log.modal('show');

				console.log(data)
			},
			error: function(xhr, status, err){
				Swal.close();
				if(xhr.status == 401){
					Swal.fire({
						type: 'error',
						title: 'Peringatan',
						customClass: 'swal-wide',
						text: xhr.responseJSON.message
					});
					return false;

				}
				console.log(xhr)
			}

		});


	});

	$('.modal-title').on('click', function(){
		$('#modal_log').modal('show')
	});
</script>
@endsection

