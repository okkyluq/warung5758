@extends('backoffice.layout.backofficelayout')
@section('title'){{ env("SET_TOKO", "NOT SET") }} | Set Akun Awal - Buat Set Nilai Hutang @endsection
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
			<a href="{{ url('sistem/set-nilai-akun') }}"><h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Data Nilai Awal Hutang</span></h4></a>
		<a class="heading-elements-toggle"><i class="icon-more"></i></a></div>
	</div>

	<div class="breadcrumb-line"><a class="breadcrumb-elements-toggle"><i class="icon-menu-open"></i></a>
		<ul class="breadcrumb">
			<li><a href="index.html"><i class="icon-equalizer2 position-left"></i> Sistem</a></li>
			<li class="active">Buat Set Nilai Awal Hutang</li>
		</ul>
	</div>
</div>

<div class="content">
	<div class="row">
		<div class="col-sm-12">
            

			<div class="panel panel-flat border-top-xlg border-top-green-600" id="panel-buat-item">
                

				<div class="panel-heading">
					<h5 class="panel-title"><span class="text-semibold"><i class="icon-file-plus"></i> Buat Set Nilai Awal Hutang</span></h5>
				</div>
				
                <form action="{{ url(Request::segment(1).'/'.Request::segment(2)) }}" method="POST" enctype="multipart/form-data" class="form-horizontal" id="form-transaksi">
				<div class="panel-body">
                    @csrf
					<div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Kode Transaksi :</label>
                            <div class="col-lg-6 {{ $errors->has('kode_transaksi') ? "has-error" : "" }}">
                                <input type="text" class="form-control" placeholder="Kode Transaksi" readonly name="kode_transaksi" value="{{ $kode }}">
                                @if ($errors->has('kode_transaksi'))
									<div class="label-block">
										<span class="label label-danger">{{ $errors->first('kode_transaksi') }}</span>
									</div>	
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Tanggal :</label>
                            <div class="col-lg-6 {{ $errors->has('Tanggal') ? "has-error" : "" }}">
                                <input type="text" class="form-control" placeholder="Tanggal" name="tgl_set">
                                @if ($errors->has('tgl_set'))
									<div class="label-block">
										<span class="label label-danger">{{ $errors->first('tgl_set') }}</span>
									</div>	
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Supplier :</label>
                            <div class="col-lg-6 {{ $errors->has('supplier') ? "has-error" : "" }}">
                                <select name="supplier" id="supplier" class="form-control"></select>
                                @if ($errors->has('supplier'))
									<div class="label-block mt-5">
										<span class="label label-danger">{{ $errors->first('supplier') }}</span>
									</div>	
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Jatuh Tempo :</label>
                            <div class="col-lg-4 {{ $errors->has('jatuh_tempo') ? "has-error" : "" }}">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Jumlah Hari" id="jatuh_tempo" name="jatuh_tempo" max="30">
                                    <span class="input-group-addon"><i class="icon-calendar52"></i></span>
                                </div>
                                @if ($errors->has('jatuh_tempo'))
									<div class="label-block mt-5">
										<span class="label label-danger">{{ $errors->first('jatuh_tempo') }}</span>
									</div>	
                                @endif
                            </div>
                        </div>
                    </div>
				</div>
                <div class="panel-body">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Total :</label>
                            <div class="col-lg-6 {{ $errors->has('total') ? "has-error" : "" }}">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="text-bold">Rp.</i></span>
                                    <input type="text" class="form-control" placeholder="Total Nominal" id="total" name="total">
                                </div>
                                @if ($errors->has('total'))
                                    <div class="label-block mt-5">
                                        <span class="label label-danger">{{ $errors->first('total') }}</span>
                                    </div>	
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Akun :</label>
                            <div class="col-lg-6 {{ $errors->has('akun') ? "has-error" : "" }}">
                                <select name="akun" id="akun" class="form-control"></select>
                                @if ($errors->has('akun'))
									<div class="label-block mt-5">
										<span class="label label-danger">{{ $errors->first('akun') }}</span>
									</div>	
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6"> 
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Keterangan :</label>
                            <div class="col-lg-6">
                                <textarea name="keterangan" id="keterangan" class="form-control" rows="5" placeholder="Masukan Keterangan Jika Ada"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
				<div class="panel-footer"><a class="heading-elements-toggle" style="margin-top: 200px;"><i class="icon-more"></i></a>
					<div class="heading-elements">
						<div class="pull-right">
							<a href="{{ url('sistem/set-nilai-hutang') }}" class="btn btn-default"><b><i class="icon-circle-left2"></i></b> Kembali</a>
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
<script>
var akun_hutang = {!! $akun_hutang !!};
var option_hutang = new Option(akun_hutang.kode+' - '+akun_hutang.value , akun_hutang.id, false, false);

window.onload = function () {
	show_loading('#panel-buat-item');
	hide_loading('#panel-buat-item', 500);
}

$('input[name="tgl_set"]').daterangepicker({ 
    singleDatePicker: true
});

$('#total').mask("#,##0", {reverse: true});
$('#jatuh_tempo').mask("0#", {reverse: true});

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
});

$("#akun").select2({
    allowClear: true,
    placeholder: 'Silahkan Pilih Akun',
    dropdownAutoWidth : true,
    ajax: {
        url: "{{ url('getakunselect2') }}",
        dataType: 'json',
        data: function (params) {
            return {
                q: params.term,
            };
        },
        delay: 250,
        processResults: function (data) {
            return {
                results:  $.map(data, function (item) {
                    return {
                        text: item.kode_akun + ' - ' +item.nama_akun,
                        id: item.id,
                        item : item
                    }
                })
            };
    }, 
    cache: true, 
    }
}).append(option_hutang).trigger('change');





    

</script>
@endsection