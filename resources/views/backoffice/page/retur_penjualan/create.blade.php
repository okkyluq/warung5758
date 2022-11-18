@extends('backoffice.layout.backofficelayout')
@section('title'){{ env("SET_TOKO", "NOT SET") }} | Sistem - Buat Retur Penjualan @endsection
@section('style')

@endsection
@section('content')
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<a href="{{ url('sistem/set-nilai-item') }}"><h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Data Retur Penjualan</span></h4></a>
		<a class="heading-elements-toggle"><i class="icon-more"></i></a></div>
	</div>

	<div class="breadcrumb-line"><a class="breadcrumb-elements-toggle"><i class="icon-menu-open"></i></a>
		<ul class="breadcrumb">
			<li><a href="index.html"><i class="icon-grid position-left"></i> Sistem</a></li>
			<li class="active">Buat Retur Penjualan</li>
		</ul>
	</div>
</div>

<div class="content">
	<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-flat border-top-xlg border-top-green-600" id="panel-buat-item">
				<div class="panel-heading">
					<h5 class="panel-title"><span class="text-semibold"><i class="icon-file-plus"></i> Buat Retur Penjualan</span></h5>
                    <div class="heading-elements">
                        <button id="btn-modal-import-penjualan" type="button" class="btn border-slate text-slate-800 btn-flat"><i class=" icon-import position-left"></i> Import Pembelian</button>
                    </div>
				</div>
				<div class="panel-body">
					<form action="{{ url(Request::segment(1).'/'.Request::segment(2).'/'.Request::segment(3)) }}" method="POST" enctype="multipart/form-data" class="form-horizontal" id="form-transaksi">
                    @csrf
					<div class="col-sm-8">
                        <div class="form-group">
                            <label class="col-lg-2 control-label text-right">Kode Transaksi :</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" placeholder="Kode Transaksi" readonly name="kode_transaksi" value="{{ $kode }}">
                            </div>
                            <label class="col-lg-2 control-label text-right">Costumer</label>
                            <div class="col-lg-4">
                                <select id="costumer" class="form-control" name="costumer"></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label text-right">Tanggal :</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" placeholder="Tanggal" name="tgl_set">
                            </div>
                        </div>
                    </div>
				</div>
                <div class="table-responsive">
                    <table class="table table-sm" id="table-transaksi">
                        <thead class="bg-danger">
                            <tr>
                                <th>Kode Item</th>
                                <th>Nama Item</th>
                                <th class="text-right">Qty</th>
                                <th>Unit</th>
                                <th class="text-right">Harga</th>
                                <th class="text-right">Sub Total</th>
                                <th class="text-center"><i class="icon-gear"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="no_item">
                                <td colspan="7" class="text-center">Data Belum Ada</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="col-lg-6">
                            <textarea name="keterangan" id="keterangan" cols="10" rows="3" class="form-control" placeholder="Masukan Keterangan Jika Ada"></textarea>
                        </div>
                    </div>
                </div>
				<div class="panel-footer"><a class="heading-elements-toggle" style="margin-top: 200px;"><i class="icon-more"></i></a>
					<div class="heading-elements">
						<div class="pull-right">
							<a href="{{ url('sistem/retur/retur-penjualan') }}" class="btn btn-default"><b><i class="icon-circle-left2"></i></b> Kembali</a>
							<button type="submit" class="btn btn-success"><b><i class="icon-floppy-disk"></i></b> Simpan Transaksi</button>
						</div>
					</div>
				</div>
            </form>
			</div>
		</div>
	</div>
</div>


<div id="modal_import_penjualan" class="modal fade" tabindex="-99" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h5 class="modal-title">Cari Penjualan</h5>
            </div>

            <div class="modal-body">

                <form class="form-horizontal" id="form-pencarian-penjualan">
                    <div class="form-group">
                        <label class="col-lg-2 control-label text-right">No. Penjualan :</label>
                        <div class="col-lg-3">
                            <input type="text" class="form-control" placeholder="No. Penjualan" id="no_penjualan" name="no_penjualan">
                        </div>
                        <label class="col-lg-2 control-label text-right">Costumer :</label>
                        <div class="col-lg-3">
                            <select id="costumer_penjualan" class="form-control pencarian-costumer" name="costumer_penjualan"></select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 control-label text-right">Tanggal:</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control tanggal-periode" placeholder="No. Penjualan" id="tanggal_penjualan" name="tanggal_penjualan">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-4">
                            <button type="submit" class="btn btn-primary">Filter <i class="icon-filter4 position-right"></i></button>
                        </div>
                    </div>
                </form>

                <table class="table table-sm table-bordered" id="table-pencarian-penjualan">
                    <thead>
                        <tr class="bg-success">
                            <th class="text-center" width="120">No.Penjualan</th>
                            <th class="text-center" width="120">Tanggal</th>
                            <th class="text-center">Costumer</th>
                            <th class="text-center" width="120">Total</th>
                            <th class="text-center" width="120">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
<script src="{{ asset('back/global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('back/global_assets/js/plugins/ui/moment/moment.min.js') }}"></script>
<script src="{{ asset('back/global_assets/js/plugins/pickers/daterangepicker.js') }}"></script>
<script src="{{ asset('back/jquery.number.js') }}"></script>
<script src="{{ asset('back/global_assets/js/plugins/jquery-mask/dist/jquery.mask.js') }}"></script>
<script src="{{ asset('back/jquery.autocomplete.js') }}"></script>
<script src="{{ asset('back/assets/js/tambahan.js') }}"></script>
<script src="{{ asset('back/global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
@include('backoffice.page.retur_penjualan.partials.script_create')
<script>

window.onload = function () {
	show_loading('#panel-buat-item');
	hide_loading('#panel-buat-item', 500);
}


$(document).ready(function() {

    $('#form-transaksi').on('submit', function() {
        event.preventDefault();
        var form = $(this);
        var url = form.attr('action');
        var tr = $('#table-transaksi tbody tr:not(.no_item)');

        var jsonObj = [];

        $('#table-transaksi tbody tr:not(.no_item)').each(function(index, value){
            let item = {}
            item['kas_id'] = $(value).find('input#kas_id').val();
            item['item_id'] = $(value).find('input#item_id').val();
            item['qty'] = $(value).find('input#qty').val();
            item['satuan_item_id'] = $(value).find('input#satuan_item_id').val();
            item['harga'] = $(value).find('input#harga').val();
            item['sub_total'] = $(value).find('input#sub_total').val();
            jsonObj.push(item);
        });

        var formData = {
            kode_transaksi : $('input[name="kode_transaksi"]').val(),
            tgl_set : $('input[name="tgl_set"]').val(),
            costumer : $('select[name="costumer"]').val(),
            keterangan : $('textarea#keterangan').val(),
            list_item: JSON.stringify(jsonObj)
        }

        // console.log(formData)

        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            beforeSend: function(){
                $.loaderStart('#panel-buat-item');
            },
            success: function(response){
                if(response.status == "sukses"){
                    window.location.href = "{{ url('sistem/retur/retur-penjualan') }}";
                }
            },
            error: function(response){
                if ($.isEmptyObject(response.responseJSON.errors) == false) {
                    console.log(response.responseJSON.errors[Object.keys(response.responseJSON.errors)[0]])
                    Swal.fire({ type: 'error', title: 'Peringatan', text: response.responseJSON.errors[Object.keys(response.responseJSON.errors)[0]] })
                }
            },
            complete: function(){
                $.loaderStop('#panel-buat-item');
            }

        });
    });








});

</script>
@endsection
