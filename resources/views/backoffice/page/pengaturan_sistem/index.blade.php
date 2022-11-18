@extends('backoffice.layout.backofficelayout')
@section('title'){{ env("SET_TOKO", "NOT SET") }} | Pengaturan - Pengaturan Sistem @endsection
@section('style')

@endsection
@section('content')
<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h2><i class="icon-cogs position-left"></i> <span class="text-semibold">Pengaturan Sistem</span></h2>
		</div>
	</div>
	<div class="breadcrumb-line"></div>
</div>
<!-- /page header -->


<div class="content">
	<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-flat border-top-xlg border-top-green-600" id="panel-buat-barang">
				<div class="panel-heading">
					<h5 class="panel-title"><span class="text-semibold"><i class="icon-wrench"></i> Pengaturan Sistem</span></h5>
				</div>

				<div class="panel-body">
					<form action="{{ url(Request::segment(1).'/'.Request::segment(2)) }}" method="POST" id="form-pengaturan-akun" class="form-horizontal">

                        <div class="tabbable">
                            <ul class="nav nav-tabs nav-tabs-highlight">
                                <li class="active"><a href="#umum" data-toggle="tab"><i class="icon-grid6"></i> Umum</a></li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="umum">

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label text-right">Costumer Default :</label>
                                        <div class="col-sm-2">
                                            <select name="costumer_default" id="costumer_default" class="form-control"></select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label text-right">Supplier Default :</label>
                                        <div class="col-sm-2">
                                            <select name="supplier_default" id="supplier_default" class="form-control"></select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label text-right">Satuan Default :</label>
                                        <div class="col-sm-2">
                                            <select name="satuan_default" id="satuan_default" class="form-control"></select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label text-right">Kas Cash Penjualan Default :</label>
                                        <div class="col-sm-2">
                                            <select name="kas_penjualan_cash" id="kas_penjualan_cash" class="form-control"></select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label text-right">Kas Credit Penjualan Default :</label>
                                        <div class="col-sm-2">
                                            <select name="kas_penjualan_credit" id="kas_penjualan_credit" class="form-control"></select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label text-right">Kas Cash Pembelian Default :</label>
                                        <div class="col-sm-2">
                                            <select name="kas_pembelian_cash" id="kas_pembelian_cash" class="form-control"></select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label text-right">Kas Credit Pembelian Default :</label>
                                        <div class="col-sm-2">
                                            <select name="kas_pembelian_credit" id="kas_pembelian_credit" class="form-control"></select>
                                        </div>
                                    </div>



                                </div>
                            </div>
                        </div>



					</div>

                    <div class="panel-footer"><a class="heading-elements-toggle"><i class="icon-more"></i></a><a class="heading-elements-toggle"><i class="icon-more"></i></a>
                        <div class="heading-elements">
                            <div class="pull-right">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <button type="submit" class="btn btn-sm bg-success btn-labeled text-bold"><b><i class="icon-floppy-disk"></i></b> Simpan Pengaturan</button>
                            </div>
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
<script src="{{ asset('back/global_assets/js/plugins/jquery-mask/dist/jquery.mask.js') }}"></script>
<script src="{{ asset('back/global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('back/assets/js/tambahan.js') }}"></script>
<script>
let setting = @json($pengaturan);

setting.forEach(function(item){
    let value = JSON.parse(item.value)
    var newOption = new Option(value.label, value.key+'-'+value.label, false, false);
    $(`#${item.setting}`).append(newOption).trigger('change');
});

$(document).ready(function(){

    $('select#costumer_default').select2({
        allowClear: true,
		placeholder: 'Pilih Costumer',
	    dropdownAutoWidth : true,
        width: 'resolve',
        ajax: {
            url: "{{ url('getcostumer') }}",
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results:  $.map(data, function (item) {
                        return {
                            text: item.nama_costumer,
                            id: item.id+'-'+item.nama_costumer,
                        }
                    })
                };
        },
        cache: true,
        }
    });

    $('select#supplier_default').select2({
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
                            id: item.id+'-'+item.nama_supplier,
                        }
                    })
                };
        },
        cache: true,
        }
    });

    $('select#satuan_default').select2({
		allowClear: true,
		placeholder: 'Pilih Satuan',
        ajax: {
            url: "{{ url('getsatuan') }}",
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results:  $.map(data, function (item) {
                        return {
                            text: item.satuan,
                            id: item.id+'-'+item.satuan,
                        }
                    })
                };
        },
        cache: true,
        }
    });

    $("#kas_penjualan_cash").select2({
		allowClear: true,
		placeholder: 'Pilih Kas',
        ajax: {
            url: "{{ url('getkasselect2') }}",
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results:  $.map(data, function (item) {
                        return {
                            text: item.nama_kas,
                            id: item.id+'-'+item.nama_kas,
                        }
                    })
                };
        },
        cache: true,
        }
	});

    $("#kas_penjualan_credit").select2({
		allowClear: true,
		placeholder: 'Pilih Kas',
        ajax: {
            url: "{{ url('getkasselect2') }}",
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results:  $.map(data, function (item) {
                        return {
                            text: item.nama_kas,
                            id: item.id+'-'+item.nama_kas,
                        }
                    })
                };
        },
        cache: true,
        }
	});

    $("#kas_pembelian_cash").select2({
		allowClear: true,
		placeholder: 'Pilih Kas',
        ajax: {
            url: "{{ url('getkasselect2') }}",
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results:  $.map(data, function (item) {
                        return {
                            text: item.nama_kas,
                            id: item.id+'-'+item.nama_kas,
                        }
                    })
                };
        },
        cache: true,
        }
	});

    $("#kas_pembelian_credit").select2({
		allowClear: true,
		placeholder: 'Pilih Kas',
        ajax: {
            url: "{{ url('getkasselect2') }}",
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results:  $.map(data, function (item) {
                        return {
                            text: item.nama_kas,
                            id: item.id+'-'+item.nama_kas,
                        }
                    })
                };
        },
        cache: true,
        }
	});

});


</script>
@endsection

