@extends('backoffice.layout.backofficelayout')
@section('title'){{ env("SET_TOKO", "NOT SET") }} | Transaksi Pembelian @endsection
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
					<h5 class="panel-title"><span class="text-semibold"><i class="icon-table2"></i> Daftar Transaksi Pembelian</span></h5>
				</div>

				<div class="panel-body">
					<div class="pull-right">
						<a href="{{ url(Request::url().'/create') }}" class="btn bg-primary-800 btn-labeled text-bold"><b><i class="icon-plus-circle2"></i></b> Buat Transaksi Pembelian</a>
					</div>
				</div>

				<table class="table table-bordered table-framed table-sm datatable-basic">
					<thead>
						<tr class="bg-slate-800 text-semibold">
							<th width="200">No Pembelian</th>
							<th width="200">Tanggal</th>
							<th>Supplier</th>
							<th>Total</th>
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
<script src="{{ asset('back/global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('back/global_assets/js/plugins/ui/moment/moment.min.js') }}"></script>
<script src="{{ asset('back/global_assets/js/plugins/pickers/daterangepicker.js') }}"></script>
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
    var tgl = '';
	var supplier = '';
    var oTable = $('.datatable-basic').DataTable({
        dom: '<"datatable-header"<"#toolbar">><"datatable-scroll"t><"datatable-footer"ip>',
		processing: true,
		lengthChange: true,
		serverSide: true,
        searching: false,
		ajax : {
			url : "{{ url(Request::url()) }}",
            data : function(d){
				d.tgl = tgl;
				d.supplier = supplier;
			}
		},
		language: {
    		searchPlaceholder: 'Cari Berdasarkan No Pembelian',
		},
		columnDefs :[
			{"target" : 0, "className" : 'text-center'},
			{"target" : 1, "className" : 'text-left'},
			{"target" : 2, "className" : 'text-left'},
			{"target" : 3, "className" : 'text-left'},
			{"target" : 4, "className" : 'text-left'},
		],
		columns: [
			{data : 'no_pembelian', name : 'no_pembelian', orderable: false, searchable: true, class: 'text-center'},
			{data : 'tgl_pembelian', name : 'tgl_pembelian', orderable: false, searchable: true, class: 'text-center'},
			{data : 'supplier', name : 'supplier', orderable: false, searchable: true, class: 'text-center'},
			{data : 'total', name : 'total', orderable: false, searchable: false, class: 'text-center'},
			{data : 'action', name : 'action', orderable: false, searchable: false, class: 'text-center'},
		],
        initComplete: function(setting, json){
            $('.datatable-basic').wrap("<div style='overflow:auto; width:100%;position:relative;'></div>");
			$(this).find('tfoot tr:first th:eq(1)').text(this.api().ajax.json().total);
			var input = `<div class="form-group">
							<label class="control-label col-lg-1 text-right text-bold">Filter :</label>
							<div class="col-lg-3">
								<input type="text" class="form-control" id="filter_tanggal" placeholder="Berdasarkan Tanggal">
							</div>
							<div class="col-lg-3">
								<select id="supplier" class="form-control" name="supplier" style="width:100%;"></select>
							</div>
						</div>`;
			var container = $(this).closest('#DataTables_Table_0_wrapper').find('#toolbar').addClass('form-horizontal');
			$(input)
			.find('input').attr('autocomplete', 'off').end()
			.find('input.form-control').daterangepicker({
				locale: { format: 'DD/MM/YYYY', cancelLabel: 'Bersihkan', applyLabel: 'Terapkan' },
                ranges: {
                    'Hari ini': [moment(), moment()],
                    'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '7 Hari Terakhir': [moment().subtract(6, 'days'), moment()],
                    '30 Hari Terakhir': [moment().subtract(29, 'days'), moment()],
                    'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
                    'Bulan Kemarin': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
			},
			function(start, end, label) {
				tgl = start.format('DD/MM/YYYY')+' - '+end.format('DD/MM/YYYY');
				oTable.draw();
			}).on('cancel.daterangepicker', function(ev, picker) {
				$(this).val('');
				tgl="";
				supplier="";
				oTable.draw();
			}).val('').end()
			.find('select[name="supplier"]').select2({
				allowClear: true,
                placeholder: 'Pilih Supplier',
                dropdownAutoWidth : true,
                width: 'resolve',
                ajax: {
                    url: "{{ url('getsupplier') }}",
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results:  $.map(data, function (item) {
                                return {
                                    text: item.nama_supplier,
                                    id: item.id,
                                }
                            })
                        };
                },
                cache: true,
                }
			}).on('select2:select', function (e) {
				supplier = $(this).val();
				oTable.draw();
			}).on('select2:unselect', function (e) {
				supplier = '';
				oTable.draw();
			}).end()
			.appendTo(container);
        }
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

