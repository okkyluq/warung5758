@extends('backoffice.layout.backofficelayout')
@section('title'){{ env("SET_TOKO", "NOT SET") }} | Sistem - Daftar Penyesuaian Stock @endsection
@section('style')
@endsection
@section('content')
<div class="content">
	<div class="row">
		<div class="col-sm-12">

			@if(Session::has('success') OR Session::has('failed'))
			<div class="alert alert-styled-left alert-arrow-left alert-component {{ Session::has('success') ? 'alert-success' : 'alert-danger' }}">
				<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
			</div>
			@endif


			<div class="panel panel-flat border-top-xlg border-top-green-600">
				<div class="panel-heading">
					<h5 class="panel-title"><span class="text-semibold"><i class="icon-table2"></i> Daftar Stock Opname</span></h5>
				</div>
				<div class="panel-body">
					<div class="pull-right">
						<a href="{{ url(Request::url().'/create') }}" class="btn bg-green-600 btn-labeled text-bold"><b><i class="icon-plus-circle2"></i></b> Buat Stock Opname</a>	
					</div>
				</div>
				
				<table class="table datatable-basic table-xs table-bordered table-framed">
					<thead class="bg-slate-800 text-semibold">
						<tr>
							<th class="text-center" width="30">No.</th>
							<th class="text-center">Kode Transaksi</th>
							<th class="text-center">Tgl</th>
							<th class="text-center" width="120">Actions</th>
						</tr>
					</thead>
					<tbody>
						 
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

@endsection 
@section('script')
<script src="{{ asset('back/global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>

<script src="{{ asset('back/assets/js/datatable.custom.js') }}"></script>
<script src="{{ asset('back/assets/js/tambahan.js') }}"></script>
<script>
    
	var oTable = $('.datatable-basic').DataTable({
		bLengthChange: false,
		processing: true,	
		lengthChange: true,
		serverSide: true,
		ajax : {
			url : "{{ url(Request::url()) }}",
		},
		language: {
    		searchPlaceholder: 'Cari Berdasarkan Akun',
		},
		columnDefs :[
			{"target" : 0, "className" : 'text-center'},
			{"target" : 1, "className" : 'text-center'}, 
			{"target" : 2, "className" : 'text-center'},
			{"target" : 4, "className" : 'text-center'},
		],
		columns: [
			{data : 'DT_RowIndex', name : 'DT_RowIndex', orderable: false, searchable: false, class: 'text-center text-bold'},
			{data : 'kode_transaksi', name : 'kode_transaksi', orderable: false, class: 'text-center text-bold'},
			{data : 'tgl_transaksi', name : 'tgl_transaksi', orderable: false, class: 'text-center text-bold'},
			{data : 'action', name : 'action', orderable: false, searchable: false, class: 'text-center'},
		],
	});

	oTable.on('draw', function(){
		$(this).find("a#button_delete").on('click', function(){
			swal({
				title: 'Peringatan!',
				text: "Apakah Anda Yakin Ingin Menghapus Data ?",
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ya, Hapus!',
				cancelButtonText: 'Batal'
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
							console.log(error);
						}
					});
				}
			})
		});
	});




</script>
@endsection

