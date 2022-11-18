@extends('backoffice.layout.backofficelayout')
@section('title'){{ env("SET_TOKO", "NOT SET") }} | Data Master - Data Produk @endsection
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
					<h5 class="panel-title"><span class="text-semibold"><i class="icon-table2"></i> Daftar Produk</span></h5>
				</div>

				<div class="panel-body">
					<div class="pull-right">
						<a href="{{ url(Request::url().'/create') }}" class="btn bg-primary-800 btn-labeled text-bold"><b><i class="icon-plus-circle2"></i></b> Buat Produk Baru</a>	
					</div>
					<div class="pull-left">
						<a href="{{ url('manajemen-toko/import-barang') }}" class="btn bg-green btn-labeled text-bold"><b><i class="icon-import"></i></b> Impor Data Excel</a>	
					</div>
				</div>

				<table class="table table-bordered table-framed table-sm datatable-basic">
					<thead>
						<tr class="bg-slate-800 text-semibold">
							<th width="100">SKU</th>
							<th>Barcode</th>
							<th>Nama Produk</th>
							<th>Kategori</th>
                            <th>Satuan</th>
							<th>Jenis Produk</th>
                            <th>Stock Warning</th>
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
    		searchPlaceholder: 'Cari Berdasarkan Nama Produk',
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
			{data : 'sku', name : 'sku', orderable: false, searchable: true, class: 'text-center'},
			{data : 'barcode', name : 'barcode', orderable: false, searchable: true, class: 'text-center'},
			{data : 'nama_produk', name : 'nama_produk', orderable: false, searchable: true, class: 'text-center'},
			{data : 'kategori.kategori', name : 'kategori.kategori', orderable: false, searchable: false, class: 'text-center'},
			{data : 'satuan.satuan', name : 'satuan.satuan', orderable: false, searchable: false, class: 'text-center'},
			{data : 'jenis_produk', name : 'jenis_produk', orderable: false, searchable: false, class: 'text-center'},
			{data : 'stock_warning', name : 'stock_warning', orderable: false, searchable: false, class: 'text-center'},
			{data : 'action', name : 'action', orderable: false, searchable: false, class: 'text-center'},
		]
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

