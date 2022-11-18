@extends('backoffice.layout.backofficelayout')
@section('title'){{ env("SET_TOKO", "NOT SET") }} | Bill Of Material @endsection
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
					<h5 class="panel-title"><span class="text-semibold"><i class="icon-table2"></i> Daftar Bill Of Material</span></h5>
				</div>

				<div class="panel-body">
					<div class="pull-right">
						<a href="{{ url(Request::url().'/create') }}" class="btn bg-primary-800 btn-labeled text-bold"><b><i class="icon-plus-circle2"></i></b> Buat Bill Of Material</a>	
					</div>
				</div>

				<table class="table table-bordered table-framed table-sm datatable-basic">
					<thead>
						<tr class="bg-slate-800 text-semibold">
							<th width="200">No B.O.M</th>
							<th>Nama Item</th>
							<th class="text-center" width="80"><i class="icon-gear"></i></th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
</div>

@endsection
@section('script')
<script src="{{ asset('back/global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('back/assets/js/datatable.custom.js') }}"></script>
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
    var oTable = $('.datatable-basic').DataTable({
		processing: true,	
		lengthChange: true,
		serverSide: true,
		scrollX: true,
		ajax : {
			url : "{{ url(Request::url()) }}",
		},
		language: {
    		searchPlaceholder: 'Cari Berdasarkan No B.O.M',
		},
		columnDefs :[
			{"target" : 0, "className" : 'text-center'},
			{"target" : 1, "className" : 'text-left'},
			{"target" : 2, "className" : 'text-left'}, 
		], 
		columns: [
			{data : 'no_bom', name : 'no_bom', orderable: false, searchable: true, class: 'text-center'},
			{data : 'item', name : 'item', orderable: false, searchable: true, class: 'text-center'},
			{data : 'action', name : 'action', orderable: false, searchable: false, class: 'text-center'},
		]
	});

	oTable.on('draw', function(){
		$(this).find("a#button_delete").on('click', function(){
			event.preventDefault();
			swal({
				title: 'Peringatan!',
				text: "Apakah Anda Yakin Ingin Menghapus Data?",
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ya, Hapus!',
				cancelButtonText: 'Batal',
				footer: '<a class="text-danger"> Perhatian : Semua Data Transaksi Terkait Akan Hilang</a>'
			}).then((result) => {
				if (result.value) {
					$.ajax({
						type : 'DELETE',
						url : "{{ url(Request::url()) }}"+'/'+ $(this).attr('data-id'),
						data : { _method : 'delete', _token: $('meta[name="csrf-token"]').attr('content')},
						success : function(respon){
							swal(
								'Terhapus!',
								'Data Anda Telah Terhapus.',
								'success'
							);
							oTable.ajax.reload();
							console.log(respon);
						},
						error : function(error){
							if(error.responseJSON.message == 'has-data'){
								swal({
									type: 'error',
									title: 'Gagal Menghapus Data !',
									text: 'Tidak Dapat Menghapus Karena B.O.M Telah Digunakan Untuk Produksi !',
								});
								return false;
							}
							swal({
								type: 'error',
								title: 'Ada Masalah',
								text: 'Harap Hubungi Developer',
							})
						}
					});
				}
			})
		});
	});

	
</script>
@endsection

