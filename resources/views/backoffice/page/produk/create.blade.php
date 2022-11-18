@extends('backoffice.layout.backofficelayout')
@section('title'){{ env("SET_TOKO", "NOT SET") }} | Data Master - Tambah Data Barang @endsection
@section('style')
<link rel="stylesheet" href="{{asset("back/modal-x/dist/css/bootstrap-extra-modal.css")}}">
<link rel="stylesheet" href="{{asset("back/jquery-ui-1.12.1.custom/jquery-ui.css")}}">

<style>
	.borderless td, .borderless th, .borderless tr {
        border: none;
        padding:0;
    }
    .remove-padding >tbody>tr>td, .remove-padding >tfoot>tr>td {
        padding: 0px;
        vertical-align: center;
    }
    .remove-padding >tbody>tr>td>input, .remove-padding >tfoot>tr>td>input {
        border: none; 
        border-width: 0; 
        box-shadow: none;
    }
</style>
@endsection
@section('content')
<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h2><i class="icon-pencil7 position-left"></i> <span class="text-semibold">Kelola Data Produk</span></h2>
		</div>
	</div>
	<div class="breadcrumb-line"></div>
</div>


<div class="content">
	<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-flat border-top-xlg border-top-green-600" id="panel-buat-barang">
				<div class="panel-heading">
					<h5 class="panel-title"><span class="text-semibold"><i class="icon-file-plus"></i> Buat Data Produk</span></h5>
				</div>
				
				<div class="panel-body" id="app">
					<create-barang></create-barang>
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
<script src="{{ asset('back/global_assets/js/plugins/uploaders/fileinput/plugins/purify.min.js') }}"></script>
<script src="{{ asset('back/global_assets/js/plugins/uploaders/fileinput/plugins/sortable.min.js') }}"></script>
<script src="{{ asset('back/global_assets/js/plugins/uploaders/fileinput/fileinput.min.js') }}"></script>
<script src="{{ asset('back/assets/js/tambahan.js') }}"></script>
<script src="{{asset("back/modal-x/dist/js/bootstrap-extra-modal.min.js")}}"></script>
<script src="{{asset("back/jquery-ui-1.12.1.custom/jquery-ui.js")}}"></script>

<script>
 
window.onload = function () {
	show_loading('#panel-buat-barang');
	hide_loading('#panel-buat-barang', 500);
}

</script>
@endsection

