@extends('backoffice.layout.backofficelayout')
@section('title'){{ env("SET_TOKO", "NOT SET") }} | Sistem - Buat Mutasi Kas @endsection
@section('style')

@endsection
@section('content')
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<a href="{{ url('sistem/set-nilai-item') }}"><h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Data Mutasi Kas</span></h4></a>
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
					<h5 class="panel-title"><span class="text-semibold"><i class="icon-file-plus"></i> Buat Mutasi Kas</span></h5>
				</div>

				<div class="panel-body">
					<form action="{{ url(Request::segment(1).'/'.Request::segment(2)) }}" method="POST" enctype="multipart/form-data" class="form-horizontal" id="form-transaksi">
                    @csrf
					<div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-lg-2 control-label text-right">Kode Transaksi :</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" placeholder="Kode Transaksi" readonly name="kode_transaksi" value="{{ $kode }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label text-right">Tanggal :</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" placeholder="Tanggal" name="tgl_set">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label text-right">Kas Utama :</label>
                            <div class="col-lg-4">
                                <select name="kas_utama" id="kas_utama" class="form-control select-kas"></select>
                            </div>
                            <label class="col-lg-2 control-label text-right">Jumlah :</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control text-right money" placeholder="Nominal" name="jumlah_1" id="jumlah_1">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label text-right">Kas Tujuan :</label>
                            <div class="col-lg-4">
                                <select name="kas_tujuan" id="kas_tujuan" class="form-control select-kas"></select>
                            </div>
                            <label class="col-lg-2 control-label text-right">Jumlah :</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control text-right money" placeholder="Nominal" name="jumlah_2" id="jumlah_2" readonly>
                            </div>
                        </div>
                    </div>
				</div>
				<div class="panel-footer"><a class="heading-elements-toggle" style="margin-top: 200px;"><i class="icon-more"></i></a>
					<div class="heading-elements">
						<div class="pull-right">
							<a href="{{ url('keuangan/mutasi-kas') }}" class="btn btn-default"><b><i class="icon-circle-left2"></i></b> Kembali</a>
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
@include('backoffice.page.penyesuaian_stock.partials.script_create')
<script>

window.onload = function () {
	show_loading('#panel-buat-item');
	hide_loading('#panel-buat-item', 500);
}


$(document).ready(function() {

    $(".select-kas").select2({
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

    // $(".select-kas").selectize({
    //     placeholder: 'Click here to select ...',
    //     maxItems: 1,
    //     plugins: ['remove_button'],
    //     valueField: "id",
    //     labelField: "nama_kas",
    //     searchField: "nama_kas",
    //     create: false,
    //     load: function (query, callback) {
    //         if (!query.length) return callback();
    //         $.ajax({
    //             url: "{{ url('getkasselect2') }}",
    //             dataType: 'json',
    //             delay: 250,
    //             data: {
    //                 q: query
    //             },
    //             error: function () {
    //                 callback();
    //             },
    //             success: function (res) {
    //                 console.log(res);
    //                 callback(res);
    //             },
    //         });
    //     },
    //     render: {
    //         option: (item, escape) => {
    //             return `
    //                 <div>${escape(item.nama_kas)}</div>
    //             `;
    //         }
    //     }
    // });


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
            beforeSend: function(){
                $.loaderStart('#panel-buat-item')
            },
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
            },
            complete: function(){
                $.loaderStop('#panel-buat-item')
            }
        });



    });




});

</script>
@endsection
