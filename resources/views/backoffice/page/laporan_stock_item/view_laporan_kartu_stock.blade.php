@extends('backoffice.layout.backofficelayout')
@section('title'){{ env("SET_TOKO", "NOT SET") }} | Laporan - Kartu Stock @endsection
@section('style')

@endsection
@section('content')
<div class="content">
	<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-flat border-top-xlg border-top-slate-800" id="panel-laporan">
				<div class="panel-heading">
					<h5 class="panel-title"><span class="text-semibold"><i class="icon-file-presentation"></i> Laporan Kartu Stock</span></h5>
				</div>
				<div class="panel-body">
                    <form action="{{ url('laporan/kartu-stock') }}" class="form-horizontal" method="POST" id="form-transaksi">
                        @csrf
                        <div class="form-group">
                            <label class="col-lg-1 control-label text-right">Periode:</label>
                            <div class="col-lg-3">
                                <input type="text" class="form-control" placeholder="Tanggal Periode" name="tgl_periode" id="tgl_periode">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-1 control-label text-right">Item:</label>
                            <div class="col-lg-3">
                                <select name="item" id="item" class="form-control select-item"></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-1 col-lg-3">
                                <button type="submit" class="btn btn-success"><i class="icon-file-eye2 position-left"></i> Tampilkan</button>
                                <a href="{{ url('laporan/stock-item') }}" class="btn btn-danger"><i class="icon-circle-left2 position-left"></i> Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="table-responsive">
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
        locale: {
            format: 'DD/MM/YYYY'
        }
    });

    $('.select-item').select2({
        allowClear: true,
        maximumSelectionLength: 3,
        placeholder: 'Pilih Item',
        ajax: {
            url: "{{ url('getitemselect2') }}",
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results:  $.map(data, function (item) {
                        return {
                            text: item.nama_item,
                            id: item.id,
                            item: item,
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
            data: { tgl_periode: $('#tgl_periode').val(), item: $('#item').val() },
            beforeSend: function(jqXHR, settings){
                show_loading('#panel-laporan');
            },
            success: function(response){
                $('.table-responsive').html("").html(response);
                hide_loading('#panel-laporan', 500);
            },
            error: function(response){
                hide_loading('#panel-laporan', 500);
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

