@extends('backoffice.layout.backofficelayout')
@section('title'){{ env("SET_TOKO", "NOT SET") }} | Data Master - Tambah Data Kas @endsection
@section('style')

@endsection
@section('content')
<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h2><i class="icon-pencil7 position-left"></i> <span class="text-semibold">Kelola Data Kas</span></h2>
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
					<h5 class="panel-title"><span class="text-semibold"><i class="icon-file-plus"></i> Buat Data Kas</span></h5>
				</div>

				<div class="panel-body">
					<form action="{{ url(Request::segment(1).'/'.Request::segment(2)) }}" method="POST" id="form-akun">
                            
							<div class="row form-group">
                                <div class="col-md-5 {{ $errors->has('kode_kas') ? "has-error" : "" }}">
									<label class="text-bold"> *Kode Kas : </label>
                                    <input type="text" class="form-control" id="kode_kas" name="kode_kas" placeholder="Masukan Kode Kas" value="{{ old('kode_kas', $kode_kas) }}" readonly>
									@if ($errors->has('kode_kas'))
									<div class="label-block">
										<span class="label label-danger">{{ $errors->first('kode_kas') }}</span>
									</div>	
									@endif
								</div>
							</div>
                            <div class="row form-group">
                                <div class="col-md-10 {{ $errors->has('nama_kas') ? "has-error" : "" }}">
									<label class="text-bold"> *Nama Akun :</label>
                                    <input type="text" class="form-control" id="nama_kas" name="nama_kas" placeholder="Masukan Nama Kas" value="{{ old('nama_kas') }}">
									@if ($errors->has('nama_kas'))
									<div class="label-block">
										<span class="label label-danger">{{ $errors->first('nama_kas') }}</span>
									</div>	
									@endif
								</div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-10 {{ $errors->has('type_kas') ? "has-error" : "" }}">
                                    <label class="text-bold"> *Tipe Kas : </label>
                                    <select id="type_kas" class="form-control" name="type_kas">
                                        <option value="">Pilih Tipe Kas</option>
                                        <option value="1">Tunai</option>
                                        <option value="2">Bank</option>
                                    </select>
                                    @if ($errors->has('type_kas'))
                                    <div class="label-block mt-5">
                                        <span class="label label-danger">{{ $errors->first('type_kas') }}</span>
                                    </div>	
                                    @endif
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-10 {{ $errors->has('akun_id') ? "has-error" : "" }}">
                                    <label class="text-bold"> *Akun : </label>
                                    <select id="akun_id" class="form-control" name="akun_id"></select>
                                    @if ($errors->has('akun_id'))
                                    <div class="label-block mt-5">
                                        <span class="label label-danger">{{ $errors->first('akun_id') }}</span>
                                    </div>	
                                    @endif
                                </div>
                            </div>
                      

						<input type="hidden" name="_token" value="{{csrf_token()}}">
						<div class="form-group">
							<div class="row">
								<div class="col-md-12">
									<a href="{{ url(Request::segment(1).'/'.Request::segment(2)) }}" class="btn btn-sm bg-danger btn-labeled text-bold"><b><i class="icon-circle-left2"></i></b> Batal & Kembali</a>
                					<button type="submit" class="btn btn-sm bg-success btn-labeled text-bold"><b><i class="icon-floppy-disk"></i></b> Simpan Data</button>
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

window.onload = function () {
	show_loading('#panel-buat-barang');
	hide_loading('#panel-buat-barang', 500);
}

$('#type_kas').select2();
$('#akun_id').select2({
    allowClear: true,
    placeholder: 'Pilih Akun',
    dropdownAutoWidth : true,
    ajax: {
        url: "{{ url('getakunselect2') }}",
        dataType: 'json',
        data: function (params) {
            return {
                q: params.term,
                status_pembayaran: '1'
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
});




</script>
@endsection

