@extends('backoffice.layout.backofficelayout')
@section('title'){{ env("SET_TOKO", "NOT SET") }} | Data Master - Tambah Data Supplier @endsection
@section('style')

@endsection
@section('content')
<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h2><i class="icon-pencil7 position-left"></i> <span class="text-semibold">Kelola Data Supplier</span></h2>
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
					<h5 class="panel-title"><span class="text-semibold"><i class="icon-file-plus"></i> Buat Data Supplier</span></h5>
				</div>

				<div class="panel-body">
					<form action="{{ url(Request::segment(1).'/'.Request::segment(2)) }}" method="POST" id="form-satuan">
						<div class="form-group">
							<div class="row">
								<div class="col-md-12 form-group {{ $errors->has('satuan') ? "has-error" : "" }}">
									<label class="text-bold">Nama Supplier :</label>
									<input type="text" class="form-control " placeholder="Nama Supplier" name="nama_supplier">
									@if ($errors->has('nama_supplier'))
									<div class="label-block">
										<span class="label label-danger">{{ $errors->first('nama_supplier') }}</span>
									</div>	
									@endif
								</div>
								<div class="col-md-12 form-group {{ $errors->has('keterangan') ? "has-error" : "" }}">
									<label class="text-bold">Keterangan :</label>
									<textarea name="keterangan" id="keterangan" cols="30" rows="3" class="form-control" placeholder="Masukan Keterangan Jika Ada"></textarea>
									@if ($errors->has('keterangan'))
									<div class="label-block">
										<span class="label label-danger">{{ $errors->first('keterangan') }}</span>
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

