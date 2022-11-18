@extends('backoffice.layout.backofficelayout')
@section('title'){{ env("SET_TOKO", "NOT SET") }} | Keuangan - Daftar Pembayaran @endsection
@section('style')
@endsection
@section('content')
<div class="content">
	<div class="row">
		<div class="col-sm-12">

			<div class="panel panel-flat border-top-xlg border-top-green-600">
				<div class="panel-heading">
					<h5 class="panel-title"><span class="text-semibold"><i class="icon-table2"></i> Lihat Daftar Pembayaran</span></h5>
				</div>

                <div class="panel-body">
					<div class="pull-right">
						<a href="{{ url(Request::url().'/create') }}" class="btn bg-primary-800 btn-labeled text-bold"><b><i class="icon-plus-circle2"></i></b> Buat Pembayaran</a>	
					</div>
				</div>
				
				<table class="table datatable-basic table-xs table-bordered table-framed">
					<thead class="bg-slate-800 text-semibold">
						<tr>
							<th class="text-center" width="30">No.</th>
							<th class="text-center" width="120">No. Pembayaran</th>
							<th class="text-center" width="120">Tanggal</th>
							<th class="text-center" width="200">Supplier</th>
							<th class="text-center" width="200">Total Bayar</th>
							<th class="text-center" width="120">Action</th>
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
			{data : 'kode_pembayaran', name : 'kode_pembayaran', orderable: false, class: 'text-center text-bold'},
			{data : 'tgl_pembayaran', name : 'tgl_pembayaran', orderable: false, class: 'text-center text-bold'},
			{data : 'supplier', name : 'supplier', orderable: false, class: 'text-center text-bold'},
			{data : 'total_pembayaran', name : 'total_pembayaran', orderable: false, class: 'text-right text-bold'},
			{data : 'action', name : 'action', orderable: false, class: 'text-center text-bold'},
		],
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
				footer: '<a class="text-danger"> Perhatian : Semua Data Transaksi Item Terkait Akan Hilang</a>'
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

