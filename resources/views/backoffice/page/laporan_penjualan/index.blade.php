@extends('backoffice.layout.backofficelayout')
@section('title'){{ env("SET_TOKO", "NOT SET") }} | Laporan - Laporan Penjualan @endsection
@section('style')

@endsection
@section('content')
<div class="content">
	<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-flat border-top-xlg border-top-slate-800" id="panel-laporan">
				<div class="panel-heading">
					<h5 class="panel-title"><span class="text-semibold"><i class="icon-table2"></i> Laporan Penjualan</span></h5>
				</div>
				<div class="panel-body">
                    <form action="{{ url(Request::url()) }}" class="form-horizontal" method="POST" id="form-transaksi">
                        @csrf
                        <div class="form-group">
                            <label class="col-lg-2 control-label text-right text-bold">Periode :</label>
                            <div class="col-lg-2">
                                <input type="text" name="tgl_periode" id="tgl_periode" class="form-control" placeholder="Rentang Tanggal">
                            </div>
                            <label class="col-lg-1 control-label text-right text-bold">Costumer :</label>
                            <div class="col-lg-2">
                                <select name="costumer" id="costumer" class="form-control"></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label text-right text-bold">Tgl.Jatuh Tempo :</label>
                            <div class="col-lg-2">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <input type="checkbox" name="check_tgl_jatuh_tempo">
                                    </span>
                                    <input type="text" class="form-control" name="tgl_jatuh_tempo" id="tgl_jatuh_tempo" placeholder="Tanggal Jatuh Tempo" readonly>
                                </div>
                            </div>
                            <label class="col-lg-1 control-label text-right text-bold">Termin :</label>
                            <div class="col-lg-2">
                                <select name="termin" id="termin" class="form-control">
                                    <option value="">Cash & Credit</option>
                                    <option value="1">Cash</option>
                                    <option value="2">Credit</option>
                                </select>
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
            locale: {
                format: 'DD/MM/YYYY'
            }
        });

        $('input[name="check_tgl_jatuh_tempo"]').on('change', function(){
            if(this.checked){
                $('input[name="tgl_jatuh_tempo"]').prop('readonly', false).daterangepicker({
                    singleDatePicker:true,
                    locale: {
                        format: 'DD/MM/YYYY'
                    }
                });
            } else {
                $('input[name="tgl_jatuh_tempo"]').prop('readonly', true).val('');
            }
        });

        $("#costumer").select2({
            allowClear: true,
            placeholder: 'Pilih Costumer',
            dropdownAutoWidth : true,
            width: 'resolve',
            ajax: {
                url: "{{ url('getcostumer') }}",
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results:  $.map(data, function (item) {
                            return {
                                text: item.nama_costumer,
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
                data: { tgl_periode: $('#tgl_periode').val(), costumer: $('#costumer').val(), tgl_jatuh_tempo: $('#tgl_jatuh_tempo').val(), termin: $('#termin').val(), },
                beforeSend: function(jqXHR, settings){
                    show_loading('#panel-laporan');
                },
                success: function(response){
                    $('.panel-respon').html("").html(response);
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

