@extends('backoffice.layout.backofficelayout')
@section('title'){{ env("SET_TOKO", "NOT SET") }} | Keuangan - Daftar Mutasi Kas @endsection
@section('style')
@endsection
@section('content')
<div class="content">
	<div class="row">
		<div class="col-sm-12">

			<div class="panel panel-flat border-top-xlg border-top-green-600">
				<div class="panel-heading">
					<h5 class="panel-title"><span class="text-semibold"><i class="icon-table2"></i> Lihat Daftar Daftar Mutasi Kas</span></h5>
				</div>

                <div class="panel-body">
					<div class="pull-right">
						<a href="{{ url(Request::url().'/create') }}" class="btn bg-primary-800 btn-labeled text-bold"><b><i class="icon-plus-circle2"></i></b> Buat Mutasi Kas</a>
					</div>
				</div>

				<table class="table datatable-basic table-xs table-bordered table-framed">
					<thead class="bg-slate-800 text-semibold">
						<tr>
							<th class="text-center" width="120">No. Transaksi</th>
							<th class="text-center" width="120">Tanggal</th>
							<th class="text-center" width="200">Kas Utama</th>
							<th class="text-center" width="200">Kas Tujuan</th>
							<th class="text-center" width="200">Jumlah</th>
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
			{data : 'kode_transaksi', name : 'kode_transaksi', orderable: false, class: 'text-center text-bold'},
			{data : 'tgl_transaksi', name : 'tgl_transaksi', orderable: false, class: 'text-center text-bold'},
			{data : 'utama', name : 'utama', orderable: false, class: 'text-center text-bold'},
			{data : 'tujuan', name : 'tujuan', orderable: false, class: 'text-center text-bold'},
			{data : 'jumlah', name : 'jumlah', orderable: false, class: 'text-right text-bold'},
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

