@extends('backoffice.layout.backofficelayout')
@section('title'){{ env("SET_TOKO", "NOT SET") }} | Data Master - Daftar Kas @endsection
@section('style')
@endsection
@section('content')
<div class="content">
	<div class="row">
		<div class="col-sm-12">

			@if(Session::has('success') OR Session::has('failed'))
			<div class="alert alert-styled-left alert-arrow-left alert-component {{ Session::has('success') ? 'alert-success' : 'alert-danger' }}">
				<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
				<h6 class="alert-heading text-semibold">
					@if (Session::has('success'))
						{{ Session::get('success') }}
					@else
						{{ Session::get('failed') }}
					@endif
				</h6>
			</div>
			@endif


			<div class="panel panel-flat border-top-xlg border-top-green-600">
				<div class="panel-heading">
					<h5 class="panel-title"><span class="text-semibold"><i class="icon-table2"></i> Daftar Kas</span></h5>
				</div>
				<div class="panel-body">
					<div class="pull-right">
						<a href="{{ url(Request::url().'/create') }}" class="btn bg-green-600 btn-labeled text-bold"><b><i class="icon-plus-circle2"></i></b> Buat Data Kas</a>
					</div>
				</div>

				<table class="table datatable-basic table-xxs table-bordered table-framed">
					<thead class="bg-slate-800 text-semibold">
						<tr>
							<th class="text-center" width="30">No.</th>
							<th class="text-center" width="120">Kode</th>
							<th class="text-center" width="120">Tipe Kas</th>
							<th class="text-center">Kas</th>
							<th class="text-center" width="160">Saldo</th>
							<th class="text-center" width="160">Tot Giro Keluar</th>
							<th class="text-center" width="100">Actions</th>
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
    		searchPlaceholder: 'Cari Berdasarkan Nama Kas',
		},
		columnDefs :[
			{"target" : 0, "className" : 'text-center'},
			{"target" : 1, "className" : 'text-left'},
			{"target" : 2, "className" : 'text-left'},
			{"target" : 3, "className" : 'text-left'},
			{"target" : 4, "className" : 'text-left'},
			{"target" : 5, "className" : 'text-left'},
			{"target" : 6, "className" : 'text-left'},
		],
		columns: [
			{data : 'DT_RowIndex', name : 'DT_RowIndex', orderable: false, searchable: false, class: 'text-center'},
			{data : 'kode_kas', name : 'kode_kas', orderable: false, class: 'text-center text-bold'},
			{data : 'type_kas', name : 'type_kas', orderable: true, class: 'text-center text-bold'},
			{data : 'nama_kas', name : 'nama_kas', orderable: false, class: 'text-left text-bold'},
			{data : 'saldo', name : 'saldo', orderable: false, class: 'text-right text-bold'},
			{data : 'total_giro_keluar', name : 'total_giro_keluar', orderable: false, class: 'text-right text-bold'},
			{data : 'action', name : 'action', orderable: false, searchable: false, class: 'text-center'},
		],
	});

	oTable.on('draw', function(){
		$(this).find("button#button_delete").on('click', function(){
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




</script>
@endsection

