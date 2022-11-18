@extends('backoffice.layout.backofficelayout')
@section('title'){{ env("SET_TOKO", "NOT SET") }} | Sistem - Buat Stock Opname @endsection
@section('style')

@endsection
@section('content')
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<a href="{{ url('sistem/set-nilai-item') }}"><h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Data Penyesuaian Stock</span></h4></a>
		<a class="heading-elements-toggle"><i class="icon-more"></i></a></div>
	</div>

	<div class="breadcrumb-line"><a class="breadcrumb-elements-toggle"><i class="icon-menu-open"></i></a>
		<ul class="breadcrumb">
			<li><a href="index.html"><i class="icon-grid position-left"></i> Sistem</a></li>
			<li class="active">Buat Stock Opname</li>
		</ul>
	</div>
</div>

<div class="content">
	<div class="row">
		<div class="col-sm-12">


			<div class="panel panel-flat border-top-xlg border-top-green-600" id="panel-buat-item">


				<div class="panel-heading">
					<h5 class="panel-title"><span class="text-semibold"><i class="icon-file-plus"></i> Buat Stock Opname</span></h5>
				</div>

				<div class="panel-body">
					<form action="{{ url(Request::segment(1).'/'.Request::segment(2)) }}" method="POST" enctype="multipart/form-data" class="form-horizontal" id="form-transaksi">
                    @csrf
					<div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Kode Transaksi :</label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" placeholder="Kode Transaksi" readonly name="kode_transaksi" value="{{ $kode }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Tanggal :</label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" placeholder="Tanggal" name="tgl_transaksi">
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
                                <th class="text-right">Qty Opname</th>
                                <th class="text-right">Qty Program</th>
                                <th class="text-right">Qty Selisih</th>
                                <th class="text-center">Unit</th>
                                <th class="text-center"><i class="icon-gear"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td width="200">
                                    <input type="hidden" id="item_id" name="item_id">
                                    <input class="form-control" placeholder="Kode Item" readonly id="kode_item" name="kode_item">
                                </td>
                                <td>
                                    <select name="item" id="item" class="form-control select-item"></select>
                                </td>
                                <td width="150">
                                    <input name="qty_opname" id="qty_opname" class="form-control text-right" placeholder="Qty" disabled>
                                </td>
                                <td width="150">
                                    <input name="qty_program" id="qty_program" class="form-control text-right" placeholder="Qty" disabled>
                                </td>
                                <td width="150">
                                    <input name="qty_selisih" id="qty_selisih" class="form-control text-right" placeholder="Qty" disabled>
                                </td>
                                <td width="200">
                                    <select name="satuan_item" id="satuan_item" class="form-control" disabled>
                                        <option value="" disabled selected>Pilih Satuan</option>
                                    </select>
                                </td>
                                <td width="50">
                                    <ul class="icons-list">
                                        <li class="text-danger-600"><a id="button_delete" data-id="11" href="#"><i class="icon-trash"></i></a></li>
                                    </ul>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="panel-body text-center" >
                    <div style="border: 1.5px dashed #b8c1cc; padding:10px;">
                        <div class="btn-group">
                            <button type="button" class="btn-xs btn border-slate text-slate-800 btn-flat btn-add-row" data-row="1">Tambah Baris</button>
                            <button type="button" class="btn-xs btn border-slate text-slate-800 btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="caret"></span></button>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li><a href="#" class="btn-add-row" data-row="3">Tambah 3</a></li>
                                <li><a href="#" class="btn-add-row" data-row="5">Tambah 5</a></li>
                                <li><a href="#" class="btn-add-row" data-row="10">Tambah 10</a></li>
                            </ul>
                        </div>
                    </div>
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
							<a href="{{ url('sistem/stock-opname') }}" class="btn btn-default"><b><i class="icon-circle-left2"></i></b> Kembali</a>
							<button type="submit" class="btn btn-success"><b><i class="icon-floppy-disk"></i></b> Simpan Transaksi</button>
						</div>
					</div>
					</form>
				</div>
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
@include('backoffice.page.stock_opname.partials.script_create')
<script>

window.onload = function () {
	show_loading('#panel-buat-item');
	hide_loading('#panel-buat-item', 500);
}


$(document).ready(function() {

    $('input[name="tgl_transaksi"]').daterangepicker({
        singleDatePicker: true
    });

    $('#form-transaksi').on('submit', function() {
        event.preventDefault();
        var form = $(this);
        var url = form.attr('action');
        var tr = $('#table-transaksi tbody tr');

        var jsonObj = [];

        $('#table-transaksi tbody tr').each(function(index, value){
            item = {}
	        item ["item_id"]                = $(this).find('#item_id').val();
            item ["satuan"]                 = $(this).find('#satuan_item').val();
	        item ["qty_opname"]             = parseInt($(this).find('#qty_opname').val());
	        item ["qty_program"]            = parseInt($(this).find('#qty_program').val());
	        item ["qty_selisih"]            = parseInt($(this).find('#qty_selisih').val());
	        jsonObj.push(item);
        });

        var formData = {
            kode_transaksi : $('input[name="kode_transaksi"]').val(),
            tgl_transaksi : $('input[name="tgl_transaksi"]').val(),
            keterangan : $('textarea[name="keterangan"]').val(),
            list_item: JSON.stringify(jsonObj)
        }

        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            beforeSend: function(){
                $.loaderStart('#panel-buat-item');
            },
            success: function(response){
                if(response.status == "sukses"){
                    window.location.href = "{{ url('sistem/stock-opname') }}";
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
