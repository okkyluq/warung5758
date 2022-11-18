@extends('backoffice.layout.backofficelayout')
@section('title'){{ env("SET_TOKO", "NOT SET") }} | Laporan - Laporan Pembelian @endsection
@section('style')
<style>
	div.tooltip {
		z-index: 9999;
	}
</style>
@endsection
@section('content')
<div class="content">
	<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-flat border-top-xlg border-top-slate-800" id="panel-laporan">
				<div class="panel-heading">
					<h5 class="panel-title"><span class="text-semibold"><i class="icon-table2"></i> Laporan Neraca Saldo</span></h5>
				</div>
				<div class="panel-body">
                    <form action="{{ url(Request::url()) }}" class="form-horizontal" method="POST" id="form-transaksi">
                        @csrf
                        <div class="form-group">
                            <label class="col-lg-2 control-label text-right text-bold">Periode :</label>
                            <div class="col-lg-3">
                                <input type="text" name="tgl_periode" id="tgl_periode" class="form-control" placeholder="Rentang Tanggal">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-3">
                                <button type="submit" class="btn btn-success"><i class="icon-file-eye2 position-left"></i> Tampilkan</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="panel-respon">

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
<script src="{{ asset('back/assets/js/tambahan.js') }}"></script>
<script>
    $(document).ready(function(){
        $('input[name="tgl_periode"]').daterangepicker({
            startDate: moment().subtract(29, 'days'),
            endDate: moment(),
            opens: 'right',
            dateLimit: { days: 60 },
            locale: {
                format: 'DD/MM/YYYY'
            },
            ranges: {
                'Hari ini': [moment(), moment()],
                'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                '7 Hari Terakhir': [moment().subtract(6, 'days'), moment()],
                '30 Hari Terakhir': [moment().subtract(29, 'days'), moment()],
                'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
                'Bulan Kemarin': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
        });


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

        $('#form-transaksi').submit(function(e){
            e.preventDefault();
            let link = $(this).attr('action');
            $.ajax({
                url : link,
                type: 'POST',
                data: { tgl_periode: $('#tgl_periode').val(), supplier: $('#supplier').val(), tgl_jatuh_tempo: $('#tgl_jatuh_tempo').val(), termin: $('#termin').val(), },
                beforeSend: function(jqXHR, settings){
                    $.loaderStart('#panel-laporan');
                    $('.panel-respon').html("");
                },
                success: function(response){
                    $(response).find('h4').on('click', function(){
                        alert('dedede')
                    }).end().appendTo('.panel-respon');
                },
                error: function(response){
                    if ($.isEmptyObject(response.responseJSON.errors) == false) {
                        console.log(response.responseJSON.errors[Object.keys(response.responseJSON.errors)[0]])
                        Swal.fire({ type: 'error', title: 'Peringatan', text: response.responseJSON.errors[Object.keys(response.responseJSON.errors)[0]] })
                    }
                },
                complete: function(){
                    $.loaderStop('#panel-laporan');
                }
            });
        });


    });
</script>
@endsection

