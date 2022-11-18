@extends('backoffice.layout.backofficelayout')
@section('title'){{ env("SET_TOKO", "NOT SET") }} | Sistem - Buat Mutasi Kas @endsection
@section('style')

@endsection
@section('content')
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<a href="{{ url('keuangan/mutasi-kas') }}"><h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Detail Mutasi Kas</span></h4></a>
		<a class="heading-elements-toggle"><i class="icon-more"></i></a></div>
	</div>

	<div class="breadcrumb-line"><a class="breadcrumb-elements-toggle"><i class="icon-menu-open"></i></a>
		<ul class="breadcrumb">
			<li><a href="index.html"><i class="icon-grid position-left"></i> Sistem</a></li>
			<li class="active">Buat Mutasi Kas</li>
		</ul>
	</div>
</div>

<div class="content">
	<div class="row">
		<div class="col-sm-12">


			<div class="panel panel-flat border-top-xlg border-top-green-600" id="panel-buat-item">


				<div class="panel-heading">
					<h5 class="panel-title"><span class="text-semibold"><i class="icon-file-eye"></i> Detail Mutasi Kas</span></h5>
				</div>

				<div class="panel-body">
					<form action="{{ url(Request::segment(1).'/'.Request::segment(2)) }}" method="POST" enctype="multipart/form-data" class="form-horizontal" id="form-transaksi">
                    @csrf
					<div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-lg-2 control-label text-right">Kode Transaksi</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" placeholder="Kode Transaksi" readonly name="kode_transaksi" value="{{ $mutasi->kode_transaksi }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label text-right">Tanggal :</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" disabled placeholder="Tanggal" name="tgl_set" value="{{ Carbon\Carbon::createFromFormat('Y-m-d', $mutasi->tgl_transaksi)->format('d/m/Y') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label text-right">Kas Utama :</label>
                            <div class="col-lg-4">
                                <select name="kas_utama" id="kas_utama" class="form-control select-kas" disabled>
                                    <option value="" selected disabled>{{ $mutasi->k_utama->nama_kas }}</option>
                                </select>
                            </div>
                            <label class="col-lg-2 control-label text-right">Jumlah :</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control text-right money" placeholder="Nominal" name="jumlah_1" id="jumlah_1" disabled value="{{ $mutasi->nominal_utama }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label text-right">Kas Tujuan :</label>
                            <div class="col-lg-4">
                                <select name="kas_tujuan" id="kas_tujuan" class="form-control select-kas" disabled>
                                    <option value="" selected disabled>{{ $mutasi->k_tujuan->nama_kas }}</option>
                                </select>
                            </div>
                            <label class="col-lg-2 control-label text-right">Jumlah :</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control text-right money" placeholder="Nominal" name="jumlah_2" id="jumlah_2" disabled value="{{ $mutasi->nominal_tujuan }}">
                            </div>
                        </div>
                    </div>
				</div>
				<div class="panel-footer"><a class="heading-elements-toggle" style="margin-top: 200px;"><i class="icon-more"></i></a>
					<div class="heading-elements">
						<div class="pull-right">
							<a href="{{ url('keuangan/mutasi-kas') }}" class="btn btn-default"><b><i class="icon-circle-left2"></i></b> Kembali</a>
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

window.onload = function () {
	show_loading('#panel-buat-item');
	hide_loading('#panel-buat-item', 500);
}


$(document).ready(function() {


    $('input[name="tgl_set"]').daterangepicker({
        singleDatePicker: true
    });

    $('.money').number(true);

    $('#jumlah_1').on('keyup', function(){
        $('#jumlah_2').val($(this).val());
    });

    $('#form-transaksi').on('submit', function() {
        event.preventDefault();
        let url = $(this).attr('action');
        let formData = {
            kode_transaksi : $('input[name="kode_transaksi"]').val(),
            tgl_transaksi : $('input[name="tgl_set"]').val(),
            kas_utama : $('#kas_utama').val(),
            kas_tujuan : $('#kas_tujuan').val(),
            jumlah_1 : $('input[name="jumlah_1"]').val(),
            jumlah_2 : $('input[name="jumlah_2"]').val(),
        }

        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            success: function(response){
                if(response.status == "sukses"){
                    window.location.href = "{{ url('keuangan/mutasi-kas') }}";
                }
            },
            error: function(response){
                if ($.isEmptyObject(response.responseJSON.errors) == false) {
                    console.log(response.responseJSON.errors[Object.keys(response.responseJSON.errors)[0]])
                    Swal.fire({ type: 'error', title: 'Peringatan', text: response.responseJSON.errors[Object.keys(response.responseJSON.errors)[0]] })
                }
            }
        });



    });




});

</script>
@endsection
