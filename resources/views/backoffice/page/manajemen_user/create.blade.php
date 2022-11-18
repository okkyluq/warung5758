@extends('backoffice.layout.backofficelayout')
@section('title'){{ env("SET_TOKO", "NOT SET") }} | Pengaturan User - Tambah User @endsection
@section('style')

@endsection
@section('content')
<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h2><i class="icon-pencil7 position-left"></i> <span class="text-semibold">Kelola Data User</span></h2>
		</div>
	</div>
	<div class="breadcrumb-line"></div>
</div>
<!-- /page header -->


<div class="content">
	<div class="row">
		<div class="col-sm-4">
			<div class="panel panel-flat border-top-xlg border-top-green-600" id="panel-buat-barang">
				<div class="panel-heading">
					<h5 class="panel-title"><span class="text-semibold"><i class="icon-file-plus"></i> Buat Data User</span></h5>
				</div>

				<div class="panel-body">
					<form action="{{ url(Request::segment(1).'/'.Request::segment(2)) }}" method="POST" id="form-satuan">
						<div class="form-group">
							<div class="row">
								<div class="col-md-12 form-group {{ $errors->has('name') ? "has-error" : "" }}">
									<label class="text-bold">Nama Lengkap :</label>
									<input type="text" class="form-control " placeholder="Masukan Nama Lengkap" name="name" value="{{ old('name') }}">
									@if ($errors->has('name'))
									<div class="label-block">
										<span class="label label-danger">{{ $errors->first('name') }}</span>
									</div>
									@endif
								</div>
                                <div class="col-md-12 form-group {{ $errors->has('username') ? "has-error" : "" }}">
									<label class="text-bold">Username :</label>
									<input type="text" class="form-control " placeholder="Masukan Username" name="username" value="{{ old('username') }}">
									@if ($errors->has('username'))
									<div class="label-block">
										<span class="label label-danger">{{ $errors->first('username') }}</span>
									</div>
									@endif
								</div>
                                <div class="col-md-12 form-group {{ $errors->has('password') ? "has-error" : "" }}">
									<label class="text-bold">Password :</label>
									<input type="password" class="form-control " placeholder="Masukan Password" name="password">
									@if ($errors->has('password'))
									<div class="label-block">
										<span class="label label-danger">{{ $errors->first('password') }}</span>
									</div>
									@endif
								</div>
                                <div class="col-md-12 form-group {{ $errors->has('password_confirmation') ? "has-error" : "" }}">
									<label class="text-bold">Konfirmasi Password :</label>
									<input type="password" class="form-control " placeholder="Masukan Konfirmasi Password" name="password_confirmation">
									@if ($errors->has('password_confirmation'))
									<div class="label-block">
										<span class="label label-danger">{{ $errors->first('password_confirmation') }}</span>
									</div>
									@endif
								</div>
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
				</div>


			</div>
		</div>
	</div>
</div>

@endsection
@section('script')
<script src="{{ asset('back/global_assets/js/plugins/jquery-mask/dist/jquery.mask.js') }}"></script>
<script src="{{ asset('back/assets/js/tambahan.js') }}"></script>
<script>

window.onload = function () {
	show_loading('#panel-buat-barang');
	hide_loading('#panel-buat-barang', 500);
}


</script>
@endsection

