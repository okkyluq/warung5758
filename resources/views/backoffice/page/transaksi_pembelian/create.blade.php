@extends('backoffice.layout.backofficelayout')
@section('title'){{ env("SET_TOKO", "NOT SET") }} | Transaksi Pembelian - Buat Transaksi Pembelian @endsection
@section('style')
<link href="{{ asset('back/bootstrap-editable.css') }}" rel="stylesheet"/>
<style>
    .autocomplete-suggestions {
       border: 1px solid #999;
       background: #FFF;
       overflow: auto;
   }
   .autocomplete-suggestion {
       padding: 2px 5px;
       white-space: nowrap;
       overflow: hidden;
   }
   .autocomplete-selected {
       background: #F0F0F0;
   }
   .autocomplete-suggestions strong {
       font-weight: normal;
       color: #3399FF;
   }
   .autocomplete-group {
       padding: 2px 5px;
   }
   .autocomplete-group strong {
       display: block;
       border-bottom: 1px solid #000;
   }
</style>
@endsection
@section('content')
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<a href="{{ url('/pembelian') }}"><h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Data Transaksi Pembelian</span></h4></a>
		<a class="heading-elements-toggle"><i class="icon-more"></i></a></div>
	</div>

	<div class="breadcrumb-line"><a class="breadcrumb-elements-toggle"><i class="icon-menu-open"></i></a>
		<ul class="breadcrumb">
			<li><a href="index.html"><i class="icon-grid position-left"></i> Data Transaksi</a></li>
			<li class="active">Buat Transaksi Pembelian</li>
		</ul>
	</div>
</div>

<div class="content">
	<div class="row">
		<div class="col-sm-12">


			<div class="panel panel-flat border-top-xlg border-top-green-600" id="panel-buat-item">


				<div class="panel-heading">
					<h5 class="panel-title"><span class="text-semibold"><i class="icon-file-plus"></i> Buat Transaksi Pembelian</span></h5>
				</div>

				<div class="panel-body">
					<form action="{{ url(Request::segment(1)) }}" method="POST" enctype="multipart/form-data" class="form-horizontal" id="form-transaksi">
                    @csrf
					<div class="col-sm-8">
                        <div class="form-group">
                            <label class="col-lg-3 control-label">No. Pembelian :</label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" placeholder="No. Pembelian" readonly name="no_pembelian" value="{{ $kode }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Tanggal :</label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" placeholder="Tanggal" name="tgl_pembelian">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Termin :</label>
                            <div class="col-lg-3">
                                <select class="form-control" name="termin" id="termin">
                                    <option value="1">Cash</option>
                                    <option value="2">Credit</option>
                                </select>
                            </div>
                            <div class="col-lg-3" id="opsi_cash">
                                <select id="kas" class="form-control" name="kas"></select>
                            </div>
                            <div class="col-lg-6 hidden" id="opsi_credit">
                                <div class="col-lg-3">
                                    <input type="text" class="form-control" name="hari" placeholder="Hari" value="12" id="hari">
                                </div>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" placeholder="Tanggal" id="tgl_jatuh_tempo" name="tgl_jatuh_tempo">
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Supplier</label>
                            <div class="col-lg-8">
                                <select id="supplier" class="form-control" name="supplier"></select>
                            </div>
                        </div>
                    </div>
				</div>
                <div class="table-responsive">
                    <table class="table text-nowrap" id="table-transaksi">
                        <thead>
                            <tr class="bg-success">
                                <th>Nama Item</th>
                                <th class="text-right col-md-1">Qty</th>
                                <th class="text-center col-md-2" width="90">Satuan</th>
                                <th class="text-right col-md-2" width="200">Harga</th>
                                <th class="text-right col-md-2" width="220">Subtotal</th>
                                <th width="20"><i class="icon-cog"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="item" id="item" class="form-control select-item"></select>
                                </td>
                                <td>
                                    <input type="text" class="form-control text-right" id="qty" name="qty" readonly>
                                </td>
                                <td>
                                    <select name="satuan_item" id="satuan_item" class="form-control" disabled>
                                        <option value="" disabled selected>Pilih Satuan</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control text-right" id="harga" name="harga" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control text-right" id="sub_total" name="sub_total" readonly>
                                </td>
                                <td class="text-center">
                                    <ul class="icons-list">
                                        <li class="text-danger-600"><a id="button_delete" data-id="11" href="#"><i class="icon-cancel-circle2"></i></a></li>
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
                <div class="panel-body" style="margin-bottom: -50px;">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-8">
                            </div>
                            <div class="col-sm-4">
                                <div class="content-group">
                                    <div class="table-responsive no-border">
                                        <table class="table table-xxs table-framed">
                                            <tbody>
                                                <tr>
                                                    <th>Total :</th>
                                                    <td class="text-right"><input type="text" name="total_akhir" id="total_akhir" class="form-control input-xs text-right text-bold" readonly value="0"></td>
                                                </tr>
                                                <tr class="hidden" id="kolom_uang_muka">
                                                    <th>Uang Muka:</th>
                                                    <td class="text-right">
                                                        <div class="input-group">
                                                            <input type="text" value="0" id="uang_muka" name="uang_muka" class="form-control input-xs text-right text-bold" readonly>
                                                            <span id="btn-uang-muka" class="input-group-addon"><i class="icon-plus-circle2"></i></span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
				<div class="panel-footer"><a class="heading-elements-toggle" style="margin-top: 200px;"><i class="icon-more"></i></a>
					<div class="heading-elements">
						<div class="pull-right">
							<a href="{{ url('pembelian') }}" class="btn btn-default"><b><i class="icon-circle-left2"></i></b> Kembali</a>
							<button type="submit" class="btn btn-success"><b><i class="icon-floppy-disk"></i></b> Simpan Transaksi</button>
						</div>
					</div>
                </form>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="modal_uang_muka" class="modal fade" tabindex="-1" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h5 class="modal-title"><i class="icon-plus-circle2"></i> Tambah Uang Muka</h5>
            </div>

            <div class="modal-body">
                <form action="" class="form-horizontal">
                    <div class="form-group">
                        <label class="control-label col-lg-2">Kas / Bank :</label>
                        <div class="col-lg-5">
                            <select name="kas_kredit" id="kas_kredit" class="form-control"></select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-2">Jumlah :</label>
                        <div class="col-lg-5">
                            <input type="text" class="form-control text-right" id="jumlah_uang_muka" name="jumlah_uang_muka">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-2">Keterangan :</label>
                        <div class="col-lg-10">
                            <textarea name="keterangan" id="keterangan" cols="30" rows="3" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-12 text-right">
                            <button type="button" id="btn-cancel-uang-muka" class="btn btn-danger btn-labeled btn-xs"><b><i class=" icon-circle-left2"></i></b> Batal</button>
                            <button type="button" id="btn-update-uang-muka" class="btn btn-info btn-labeled btn-xs"><b><i class="icon-floppy-disk"></i></b> Simpan</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>




@endsection
@section('script')
<script src="{{ asset('back/global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('back/global_assets/js/plugins/ui/moment/moment.min.js') }}"></script>
<script src="{{ asset('back/global_assets/js/plugins/pickers/daterangepicker.js') }}"></script>
<script src="{{ asset('back/bootstrap-editable.min.js') }}"></script>
<script src="{{ asset('back/jquery.number.js') }}"></script>
<script src="{{ asset('back/global_assets/js/plugins/jquery-mask/dist/jquery.mask.js') }}"></script>
<script src="{{ asset('back/jquery.autocomplete.js') }}"></script>
<script src="{{ asset('back/assets/js/tambahan.js') }}"></script>
@include('backoffice.page.transaksi_pembelian.script.script_create')
<script>
let supplier_default = JSON.parse(@json($supplier_default));

window.onload = function () {
	show_loading('#panel-buat-item');
	hide_loading('#panel-buat-item', 500);
}


$(document).ready(function() {
    $("#supplier").select2({
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
	}).append(new Option(supplier_default.label, supplier_default.key)).trigger('change');

    $("#kas").select2({
		allowClear: true,
		placeholder: 'Pilih Kas',
	    dropdownAutoWidth : true,
        ajax: {
            url: "{{ url('getkasselect2') }}",
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results:  $.map(data, function (item) {
                        console.log(item)
                        return {
                            text: item.nama_kas,
                            id: item.id,
                        }
                    })
                };
        },
        cache: true,
        }
	});

    $("#kas_kredit").select2({
		allowClear: true,
		placeholder: 'Pilih Kas',
	    dropdownAutoWidth : true,
        // width: 'resolve',
        dropdownParent: $('#modal_uang_muka'),
        ajax: {
            url: "{{ url('getkasselect2') }}",
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results:  $.map(data, function (item) {
                        return {
                            text: item.nama_kas,
                            id: item.id,
                        }
                    })
                };
        },
        cache: true,
        }
	});

    $('input[name="tgl_pembelian"]').daterangepicker({
        locale: { format: 'DD/MM/YYYY' },
        singleDatePicker: true
    }).on('apply.daterangepicker', function(ev, picker){
        let tgl_sekarang = picker.startDate._d;
        let hari = $('#hari').val();
        $('#tgl_jatuh_tempo').data('daterangepicker').setStartDate(moment(tgl_sekarang).add(hari, 'days').format('DD/MM/YY'));
    });

    $('#tgl_jatuh_tempo').daterangepicker({
        locale: { format: 'DD/MM/YYYY' },
        singleDatePicker: true
    }).on('apply.daterangepicker', function(ev, picker){
        let tgl_sekarang = moment($('input[name="tgl_pembelian"]').data('daterangepicker').startDate._d);
        let tgl_jatuh_tempo = moment(picker.startDate._d);
        $('#hari').val(tgl_jatuh_tempo.diff(tgl_sekarang, 'days'));
    });

    $('#termin').select2().on('change', function(e){
        $('#kas').val(null).trigger('change');
        $('#jumlah_uang_muka').val('');
        $('#kas_kredit').val(null).trigger('change');
        $('#uang_muka').val('');
        $('#keterangan').val('');
        if(e.target.value == '1'){
            $('div#opsi_cash').removeClass('hidden');
            $('div#opsi_credit').addClass('hidden');
            $('#kolom_uang_muka').addClass('hidden');
        } else {
            $('div#opsi_cash').addClass('hidden');
            $('div#opsi_credit').removeClass('hidden');
            let tgl_sekarang = $('input[name="tgl_pembelian"]').data('daterangepicker').startDate._d;
            let hari = $('#hari').val();
            $('#tgl_jatuh_tempo').data('daterangepicker').setStartDate(moment(tgl_sekarang).add(hari, 'days').format('DD/MM/YY'));
            $('#uang_muka').val(0);
            $('#kolom_uang_muka').removeClass('hidden');
        }
    });

    $('#hari').on('change', function(e){
        let tgl_sekarang = $('input[name="tgl_pembelian"]').data('daterangepicker').startDate._d;
        let hari = e.target.value;
        $('#tgl_jatuh_tempo').data('daterangepicker').setStartDate(moment(tgl_sekarang).add(hari, 'days').format('DD/MM/YY'));
    });

    var table = $('#table-transaksi');


    $('#btn-uang-muka').on('click', function(){
        $('#modal_uang_muka').modal({backdrop: 'static', keyboard: false}).modal('show').on('shown.bs.modal', function(){
            $('#jumlah_uang_muka').mask("#,##0", {reverse: true});
        });
    });

    $('#btn-update-uang-muka').on('click', function(){

        let kas_kredit = $('#kas_kredit').val();
        let jumlah = $('#jumlah_uang_muka').val() != '' ? $('#jumlah_uang_muka').val() : 0;
        let keterangan = $('#keterangan').val();

        if(kas_kredit == null || kas_kredit == ''){
            Swal.fire({ type: 'error', title: 'Peringatan', text: 'Kas/Bank Belum DIpilih' });
            return false;
        }

        $('#uang_muka').val(jumlah).mask("#,##0", {reverse: true});
        $('#modal_uang_muka').modal('hide')
    });

    $('#btn-cancel-uang-muka').on('click', function(){
        $('#modal_uang_muka').modal('hide')
    });

    $('#form-transaksi').on('submit', function() {
        event.preventDefault();
        var form = $(this);
        var url = form.attr('action');
        var tr = $('#table-transaksi tbody tr:not(#empty-rows)');
        var termin = $('#termin').val();
        var kas;
        var uang_muka;
        var keterangan;
        var hari_jatuh_tempo;
        var tgl_jatuh_tempo;

        if(termin == '1'){
            kas = $('#kas').val()
            hari_jatuh_tempo = '',
            tgl_jatuh_tempo = '';
            uang_muka = '';
        } else {
            kas = $('#kas_kredit').val()
            hari_jatuh_tempo = $('#hari').val();
            tgl_jatuh_tempo = $('#tgl_jatuh_tempo').val();
            uang_muka = $('#uang_muka').val();
        }

        var jsonObj = [];
    	tr.each(function(){
	        item = {}
	        item ["item_id"]   = $(this).find('select#item').val();
            item ["satuan"]    = $(this).find('select#satuan_item').val();
	        item ["qty"]       = $(this).find('input#qty').val();
	        item ["harga"]     = $(this).find('input#harga').val();
	        item ["sub_total"] = $(this).find('input#sub_total').val();
	        jsonObj.push(item);
    	});

        var formData = {
            no_pembelian : $('input[name="no_pembelian"]').val(),
            supplier : $('select[name="supplier"]').val(),
            tgl_pembelian : $('input[name="tgl_pembelian"]').val(),
            termin: $('#termin').val(),
            kas: kas,
            uang_muka: uang_muka,
            keterangan: $('#keterangan').val(),
            hari_jatuh_tempo : hari_jatuh_tempo,
            tgl_jatuh_tempo : tgl_jatuh_tempo,
            total: $('#total_akhir').val(),
            list_item: JSON.stringify(jsonObj)
        }

        // console.log(formData)

        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            beforeSend: function(){
                $.loaderStart('#panel-buat-item')
            },
            success: function(response){
                if(response.status == "sukses"){
                    window.location.href = "{{ url('/pembelian') }}";
                }
            },
            error: function(response){
                if ($.isEmptyObject(response.responseJSON.errors) == false) {
                    console.log(response.responseJSON.errors[Object.keys(response.responseJSON.errors)[0]])
                    Swal.fire({ type: 'error', title: 'Peringatan', text: response.responseJSON.errors[Object.keys(response.responseJSON.errors)[0]] })
                }

            },
            complete: function(){
                $.loaderStop('#panel-buat-item')
            }

        });



    });






});

</script>
@endsection
