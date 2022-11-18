@extends('backoffice.layout.backofficelayout')
@section('title'){{ env("SET_TOKO", "NOT SET") }} | Data Master - Tambah Data Akun @endsection
@section('style')

@endsection
@section('content')
<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h2><i class="icon-pencil7 position-left"></i> <span class="text-semibold">Kelola Data Akun</span></h2>
		</div>
	</div>
	<div class="breadcrumb-line"></div>
</div>
<!-- /page header -->


<div class="content">
	<div class="row">
		<div class="col-sm-6">
			<div class="panel panel-flat border-top-xlg border-top-green-600" id="panel-buat-barang">
				<div class="panel-heading">
					<h5 class="panel-title"><span class="text-semibold"><i class="icon-file-plus"></i> Buat Data Akun</span></h5>
				</div>

				<div class="panel-body">
					<form action="{{ url(Request::segment(1).'/'.Request::segment(2)) }}" method="POST" id="form-akun">
                            
							<div class="row form-group">
								<div class="col-md-5 {{ $errors->has('kategori_akun_id') ? "has-error" : "" }}">
									<label class="text-bold">*Kategori Akun :</label>
                                    <select name="kategori_akun_id" id="kategori_akun_id" class="form-control has-error">
                                        <option disabled selected value="">Pilih Akun</option>
                                        @foreach ($kategori as $isi)
                                            <option {{ old('kategori_akun_id') == $isi->id ? 'selected' : '' }} value="{{ $isi->id }}" data-kode="{{ $isi->no_kategori }}">{{ $isi->no_kategori.'. '.$isi->nama_kategori }}</option>
                                        @endforeach
                                    </select>
									@if ($errors->has('kategori_akun_id'))
									<div class="label-block mt-5">
										<span class="label label-danger">{{ $errors->first('kategori_akun_id') }}</span>
									</div>	
									@endif
								</div>
                                <div class="col-md-5 {{ $errors->has('kode_akun') ? "has-error" : "" }}">
									<label class="text-bold"> *Nomor Akun : <i class="icon-info22" data-popup="popover" title="Nomor Akun" data-trigger="hover" data-content="Angka pertama menyesuaikan berdasarkan urutan kategori akun"></i></label>
                                    <input type="text" class="form-control" id="kode_akun" name="kode_akun" placeholder="Masukan Kode Akun" {{ old('kode_akun') ? '' : 'readonly' }} value="{{ old('kode_akun') }}">
									@if ($errors->has('kode_akun'))
									<div class="label-block">
										<span class="label label-danger">{{ $errors->first('kode_akun') }}</span>
									</div>	
									@endif
								</div>
							</div>
                            <div class="row form-group">
                                <div class="col-md-10 {{ $errors->has('nama_akun') ? "has-error" : "" }}">
									<label class="text-bold"> *Nama Akun :</label>
                                    <input type="text" class="form-control" id="nama_akun" name="nama_akun" placeholder="Masukan Nama Akun" value="{{ old('nama_akun') }}">
									@if ($errors->has('nama_akun'))
									<div class="label-block">
										<span class="label label-danger">{{ $errors->first('nama_akun') }}</span>
									</div>	
									@endif
								</div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-10 {{ $errors->has('status_header') ? "has-error" : "" }}">
                                    <label class="text-bold"> Status Induk : <i class="icon-info22" data-popup="popover" title="Status Induk" data-trigger="hover" data-content="Pilih Ya jika akun ingin dibuat sebagai induk akun"></i></label><br>
                                    <input type="checkbox" name="status_header" data-on-color="success" data-size="small" class="switch" data-on-text="Ya" data-off-text="Tidak" {{ old('status_header') ? 'checked="checked"' : '' }}>
								</div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-10 {{ $errors->has('parent_id') ? "has-error" : "" }}">
                                    <label class="text-bold"> Induk Akun : </label>
                                    <select id="parent_id" class="form-control" name="parent_id"></select>
                                    @if ($errors->has('parent_id'))
                                    <div class="label-block mt-5">
                                        <span class="label label-danger">{{ $errors->first('parent_id') }}</span>
                                    </div>	
                                    @endif
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-4 {{ $errors->has('status_pembayaran') ? "has-error" : "" }}">
                                    <label class="text-bold"> Status Pembayaran : <i class="icon-info22" data-popup="popover" title="Status Pembayaran" data-trigger="hover" data-content="Pilih Ya jika Akun Ingin Digunakan Sebagai Transaksi Kas"></i></label><br>
                                    <input type="checkbox" name="status_pembayaran" data-on-color="success" data-size="small" class="switch" data-on-text="Ya" data-off-text="Tidak" {{ old('status_pembayaran') ? 'checked="checked"' : '' }}>
								</div>
                                <div class="col-md-4 {{ $errors->has('default_saldo') ? "has-error" : "" }}">
                                    <label class="text-bold"> Default Saldo : <i class="icon-info22" data-popup="popover" title="Default Saldo" data-trigger="hover" data-content="Aturan Otomatis Kredit/Debit"></i></label><br>
                                    <input type="checkbox" name="default_saldo" data-on-color="info" data-size="small" class="switch" data-on-text="Debit" data-off-text="Kredit" {{ old('default_saldo') ? 'checked="checked"' : '' }}>
								</div>
                            </div>
                      

						<input type="hidden" name="_token" value="{{csrf_token()}}">
						<div class="form-group">
							<div class="row">
								<div class="col-md-12">
									<a href="{{ url(Request::segment(1).'/'.Request::segment(2)) }}" class="btn btn-sm bg-danger btn-labeled text-bold"><b><i class="icon-circle-left2"></i></b> Batal & Kembali</a>
                					<button type="submit" class="btn btn-sm bg-success btn-labeled text-bold pull-right"><b><i class="icon-floppy-disk"></i></b> Simpan Data</button>
								</div>
							</div>
						</div>
					</form>
					</div>
                    <div class="panel-footer panel-footer-condensed">
                        <div class="heading-elements">
                            <span class="heading-text text-semibold">Keterangan : <p class="text-danger">(*) Wajib Diisi !</p></span>
                        </div>
                    </div>
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
<script src="{{ asset('back/global_assets/js/plugins/forms/styling/switchery.min.js') }}"></script>
<script src="{{ asset('back/global_assets/js/plugins/forms/styling/switch.min.js') }}"></script>
<script>

// Hide method
$('#hide-tooltip-method-target').on('mouseenter', function() {
    $(this).tooltip('show')
});

window.onload = function () {
	show_loading('#panel-buat-barang');
	hide_loading('#panel-buat-barang', 500);
}


$('#kategori_akun_id').on('change', function(){
    var kode = $(this).find(':selected').attr('data-kode');
    $('#kode_akun').val("").removeAttr('readonly').mask(kode+"000");
});

$("input[name='status_pembayaran']").bootstrapSwitch();
$("input[name='default_saldo']").bootstrapSwitch();
$("input[name='status_header']").bootstrapSwitch().on('switchChange.bootstrapSwitch', function(event, state) {
    if(state == true){
        $("#parent_id").prop("disabled", true).val(null).trigger('change');
        $('#parent_id').next().next().remove();
    } else {
        $("#parent_id").prop("disabled", false).val(null).trigger('change');
    }
    
}); 
$('#kategori_akun_id').select2();
$('#parent_id').select2({
    allowClear: true,
    placeholder: 'Pilih Induk Akun',
    dropdownAutoWidth : true,
    ajax: {
        url: "{{ url('getakunselect2') }}",
        dataType: 'json',
        data: function (params) {
            return {
                q: params.term,
                status_header: '1',
                category: $('#kategori_akun_id').val()
            };
        },
        delay: 250,
        processResults: function (data) {
            return {
                results:  $.map(data, function (item) {
                    return {
                        text: item.nama_akun,
                        id: item.id,
                        item : item
                    }
                })
            };
    }, 
    cache: true, 
    }
}).prop("disabled", false);




</script>
@endsection

