@extends('backoffice.layout.backofficelayout')
@section('title'){{ env("SET_TOKO", "NOT SET") }} | Laporan - Stock Item @endsection
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
					<h5 class="panel-title"><span class="text-semibold"><i class="icon-table2"></i> Laporan Stock Item</span></h5>
					<div class="heading-elements">
						<button id="btn-modal-laporan-item" type="button" class="btn btn-success btn-labeled"><b><i class="icon-file-presentation"></i></b> Laporan Item Tingkat Lanjut</button>
					</div>
				</div>
				<table class="table table-bordered table-framed table-sm datatable-basic">
					<thead>
						<tr class="bg-slate-800 text-semibold">
							<th width="200">Kode Item</th>
							<th>Barcode</th>
							<th>Nama Item</th>
							<th>Tipe Item</th>
                            <th>Stock</th>
							<th class="text-center" width="80"><i class="icon-gear"></i></th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div id="laporan_item_menu" class="modal fade" tabindex="-1" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<h5 class="modal-title">Daftar Laporan Item</h5>
			</div>

			<div class="modal-body">
				<ul class="list no-margin-bottom">
					{{-- <li><a href="{{ url('laporan/saldo-stock') }}"><i class="icon-file-presentation"></i> Laporan Saldo Stock</a></li> --}}
					<li><a href="{{ url('laporan/kartu-stock') }}"><i class="icon-file-presentation"></i> Laporan Kartu Stock</a></li>
					{{-- <li><a href="{{ url('laporan/penyesuaian-stock') }}"><i class="icon-file-presentation"></i> Laporan Penyesuaian Stock</a></li>
					<li><a href="{{ url('laporan/opname-stock') }}"><i class="icon-file-presentation"></i> Laporan Opname Stock</a></li> --}}
				</ul>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

@endsection
@section('script')
<script src="{{ asset('back/global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('back/assets/js/datatable.custom.js') }}"></script>
<script>



	$('#btn-modal-laporan-item').on('click', function(){
		$('#laporan_item_menu').modal('show');
	});


    var oTable = $('.datatable-basic').DataTable({
		processing: true,
		lengthChange: true,
		serverSide: true,
		responsive: true,
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
			{data : 'stock', name : 'stock', orderable: false, searchable: false, class: 'text-center'},
			{data : 'action', name : 'action', orderable: false, searchable: false, class: 'text-center'},
		]
	});
</script>
@endsection

