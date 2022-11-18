@extends('backoffice.layout.backofficelayout')
@section('title'){{ env("SET_TOKO", "NOT SET") }} | Akutansi - Jurnal Umum @endsection
@section('style')
@endsection
@section('content')
<div class="content">
	<div class="row">
		<div class="col-sm-12">

			<div class="panel panel-flat border-top-xlg border-top-green-600">
				<div class="panel-heading">
					<h5 class="panel-title"><span class="text-semibold"><i class="icon-table2"></i> Lihat Jurnal Umum</span></h5>
				</div>
				
				<table class="table datatable-basic table-xs table-bordered table-framed">
					<thead class="bg-slate-800 text-semibold">
						<tr>
							<th class="text-center" width="30">No.</th>
							<th class="text-center" width="150">Kode Jurnal</th>
							<th class="text-center" width="150">Tanggal</th>
							<th class="text-center">Keterangan</th>
							<th class="text-center" width="100">Action</th>
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
			{"target" : 1, "className" : 'text-center'},
			{"target" : 2, "className" : 'text-center'},
			{"target" : 3, "className" : 'text-left'},
			{"target" : 4, "className" : 'text-center'},
		],
		columns: [
			{data : 'DT_RowIndex', name : 'DT_RowIndex', orderable: false, searchable: false, class: 'text-center'},
			{data : 'kode_journal', name : 'kode_journal', orderable: false, class: 'text-center text-bold'},
			{data : 'tgl_set', name : 'tgl_set', orderable: false, class: 'text-center text-bold'},
			{data : 'keterangan', name : 'keterangan', orderable: false, class: 'text-left text-bold'},
			{data : 'action', name : 'action', orderable: false, class: 'text-center text-bold'},
		],
	});


</script>
@endsection

