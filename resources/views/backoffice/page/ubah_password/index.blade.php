@extends('backoffice.layout.backofficelayout')
@section('title'){{ env("SET_TOKO", "NOT SET") }} | Ubah Password @endsection
@section('style')

@endsection
@section('content')
<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h2><i class="icon-pencil7 position-left"></i> <span class="text-semibold">Ubah password</span></h2>
		</div>
	</div>
	<div class="breadcrumb-line"></div>
</div>
<!-- /page header -->


<div class="content">
	<div class="row">
		<div class="col-sm-4">
            @if (Session::has('failed'))
            <div class="alert alert-styled-left alert-arrow-left alert-component alert-danger">
				<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
				<h6 class="alert-heading text-semibold">
					{{ Session::get('failed') }}
				</h6>
			</div>
            @endif

            @if (Session::has('success'))
            <div class="alert alert-styled-left alert-arrow-left alert-component alert-success">
				<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
				<h6 class="alert-heading text-semibold">
					{{ Session::get('success') }}
				</h6>
			</div>
            @endif

			<div class="panel panel-flat border-top-xlg border-top-green-600" id="panel-buat-barang">
				<div class="panel-heading">
					<h5 class="panel-title"><span class="text-semibold"><i class="icon-key"></i> Ubah Password</span></h5>
				</div>

				<div class="panel-body">
					<form action="{{ url(Request::segment(1)) }}" method="POST" id="form-satuan">
						<div class="form-group">
							<div class="row">
								<div class="col-md-12 form-group {{ $errors->has('password_lama') ? "has-error" : "" }}">
									<label class="text-bold">Password Lama :</label>
									<input type="password" class="form-control " placeholder="Masukan Password Lama" name="password_lama">
									@if ($errors->has('password_lama'))
									<div class="label-block">
										<span class="label label-danger">{{ $errors->first('password_lama') }}</span>
									</div>
									@endif
								</div>
                                <div class="col-md-12 form-group {{ $errors->has('password_baru') ? "has-error" : "" }}">
									<label class="text-bold">Password Baru :</label>
									<input type="password" class="form-control " placeholder="Password Baru" name="password_baru">
									@if ($errors->has('password_baru'))
									<div class="label-block">
										<span class="label label-danger">{{ $errors->first('password_baru') }}</span>
									</div>
									@endif
								</div>

							</div>
						</div>
						<input type="hidden" name="_token" value="{{csrf_token()}}">
						<div class="form-group">
							<div class="row">
								<div class="col-md-12">
                					<button type="submit" class="btn btn-sm bg-success btn-labeled text-bold pull-right"><b><i class="icon-floppy-disk"></i></b> Ubah Password</button>
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

