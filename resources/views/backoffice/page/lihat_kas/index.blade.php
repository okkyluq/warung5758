@extends('backoffice.layout.backofficelayout')
@section('title'){{ env("SET_TOKO", "NOT SET") }} | Keuangan - Lihat Kas @endsection
@section('style')
@endsection
@section('content')
<div class="content">
	<div class="row">
		<div class="col-sm-12">

			<div class="panel panel-flat border-top-xlg border-top-green-600">
				<div class="panel-heading">
					<h5 class="panel-title"><span class="text-semibold"><i class="icon-table2"></i> Lihat Daftar Kas</span></h5>
				</div>
				
				<table class="table datatable-basic table-xs table-bordered table-framed">
					<thead class="bg-slate-800 text-semibold">
						<tr>
							<th class="text-center" width="30">No.</th>
							<th class="text-center" width="120">Kode</th>
							<th class="text-center">Kas</th>
							<th class="text-center" width="200">Saldo</th>
							<th class="text-center" width="200">Kas Masuk Bulan Ini</th>
							<th class="text-center" width="200">Kas Keluar Bulan Ini</th>
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
        searching: false,
		ajax : {
			url : "{{ url(Request::url()) }}",
		},
		columnDefs :[
			{"target" : 0, "className" : 'text-center'},
			{"target" : 1, "className" : 'text-left'},
			{"target" : 2, "className" : 'text-left'},
			{"target" : 3, "className" : 'text-left'},
			{"target" : 4, "className" : 'text-left'},
			{"target" : 5, "className" : 'text-left'},
		],
		columns: [
			{data : 'DT_RowIndex', name : 'DT_RowIndex', orderable: false, searchable: false, class: 'text-center'},
			{data : 'kode_kas', name : 'kode_kas', orderable: false, class: 'text-center text-bold'},
			{data : 'nama_kas', name : 'nama_kas', orderable: false, class: 'text-left text-bold'},
			{data : 'saldo', name : 'saldo', orderable: false, class: 'text-right text-bold'},
			{data : 'kas_masuk', name : 'kas_masuk', orderable: false, class: 'text-right text-bold'},
			{data : 'kas_keluar', name : 'kas_keluar', orderable: false, class: 'text-right text-bold'},
		],
	});


</script>
@endsection

