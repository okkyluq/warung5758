@extends('backoffice.layout.backofficelayout')
@section('title'){{ env("SET_TOKO", "NOT SET") }} | Transaksi Penjualan - Buat Transaksi Penjualan @endsection
@section('style')
<link href="{{ asset('back/bootstrap-editable.css') }}" rel="stylesheet"/>
<style>
    .autocomplete-suggestions {
       border: 1px solid #999;
       background: #FFF;
       overflow: auto;
   }
   .autocomplete-suggestion {
       padding: 2px 5px;
       white-space: nowrap;
       overflow: hidden;
   }
   .autocomplete-selected {
       background: #F0F0F0;
   }
   .autocomplete-suggestions strong {
       font-weight: normal;
       color: #3399FF;
   }
   .autocomplete-group {
       padding: 2px 5px;
   }
   .autocomplete-group strong {
       display: block;
       border-bottom: 1px solid #000;
   }
</style>
@endsection
@section('content')
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title" style="padding:15px 36px 15px 0;">
			<a href="{{ url('/penjualan') }}"><h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Data Transaksi Penjualan</span></h4></a>
		<a class="heading-elements-toggle"><i class="icon-more"></i></a></div>
	</div>
</div>

<div class="content">
	<div class="row">
        <div class="col-sm-5">
            <div class="panel panel-flat border-top-xlg border-top-green-600" id="panel-item-list">
                <div class="panel-heading">
                    <h6 class="panel-title"><span class="text-semibold"><i class="icon-cube4"></i> Daftar Item</span></h6>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="icon-search4"></i></span>
                                <input type="text" class="form-control" placeholder="Cari Item" id="input-pencarian-item">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-body" style="overflow-y: scroll; height: 350px; background-color: rgb(226, 226, 226); border-top-color:rgb(162, 162, 162); border-top-style:solid; border-bottom-color:rgb(162, 162, 162); border-bottom-style:solid;">

                    <div class="row" id="container-list-item">

                    </div>
                </div>
                <div class="panel-footer panel-footer-condensed"><a class="heading-elements-toggle"><i class="icon-more"></i></a>
                    <div class="heading-elements">
                    </div>
                </div>
            </div>
        </div>
		<div class="col-sm-7">
			<div class="panel panel-flat border-top-xlg border-top-green-600" id="panel-buat-item">
				<div class="panel-heading">
					<h5 class="panel-title"><span class="text-semibold"><i class="icon-file-plus"></i> Buat Transaksi Penjualan</span></h5>
				</div>

				<div class="panel-body">
					<form action="{{ url(Request::segment(1)) }}" method="POST" enctype="multipart/form-data" class="form-horizontal" id="form-transaksi">
                    @csrf

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-lg-4 control-label text-right">No. Penjualan :</label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" placeholder="No. Penjualan" readonly name="no_penjualan" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-4 control-label text-right">Tanggal :</label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" placeholder="Tanggal" name="tgl_penjualan">
                            </div>
                        </div>

                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-lg-4 control-label text-right">Costumer :</label>
                            <div class="col-lg-6">
                                <select id="costumer" class="form-control" name="costumer"></select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="col-lg-2 control-label text-right">Termin :</label>
                            <div class="col-lg-3">
                                <select class="form-control" name="termin" id="termin">
                                    <option value="1">Cash</option>
                                    <option value="2">Credit</option>
                                </select>
                            </div>
                            <div class="col-lg-3" id="opsi_cash">
                                <select id="kas" class="form-control" name="kas"></select>
                            </div>
                            <div class="col-lg-6 hidden" id="opsi_credit">
                                <div class="col-lg-3">
                                    <input type="text" class="form-control" name="hari" placeholder="Hari" value="12" id="hari">
                                </div>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" placeholder="Tanggal" id="tgl_jatuh_tempo" name="tgl_jatuh_tempo">
                                </div>
                            </div>

                        </div>
                    </div>



				</div>
                <div class="table-responsive">
                    <table class="table text-nowrap" id="table-transaksi">
                        <thead class="bg-primary">
                            <tr>
                                <th>Nama Item</th>
                                <th class="col-md-2">Qty</th>
                                <th class="col-md-2">Satuan</th>
                                <th class="col-md-2">Harga</th>
                                <th class="col-md-2">Sub Total</th>
                                <th class="text-center" style="width: 20px;"><i class="icon-gear"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="empty-rows">
                                <td colspan="6" class="text-bold text-center">Data Item Belum Ada</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="panel-body" style="margin-bottom: -50px;">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                            </div>
                            <div class="col-sm-6">
                                <div class="content-group">
                                    <div class="table-responsive no-border">
                                        <table class="table table-xxs table-framed">
                                            <tbody>
                                                <tr>
                                                    <th>Total :</th>
                                                    <td class="text-right"><input type="text" name="total_akhir" id="total_akhir" class="form-control input-xs text-right text-bold" readonly value="0"></td>
                                                </tr>
                                                <tr class="hidden" id="kolom_uang_muka">
                                                    <th>Uang Muka:</th>
                                                    <td class="text-right">
                                                        <div class="input-group">
                                                            <input type="text" value="0" id="uang_muka" name="uang_muka" class="form-control input-xs text-right text-bold" readonly>
                                                            <span id="btn-uang-muka" class="input-group-addon"><i class="icon-plus-circle2"></i></span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

				<div class="panel-footer"><a class="heading-elements-toggle" style="margin-top: 200px;"><i class="icon-more"></i></a>
					<div class="heading-elements">
						<div class="pull-right">
							<a href="{{ url('penjualan') }}" class="btn btn-default"><b><i class="icon-circle-left2"></i></b> Kembali</a>
							<button type="submit" class="btn btn-success"><b><i class="icon-floppy-disk"></i></b> Simpan Transaksi</button>
						</div>
					</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="modal_uang_muka" class="modal fade" tabindex="-1" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h5 class="modal-title"><i class="icon-plus-circle2"></i> Tambah Uang Muka</h5>
            </div>

            <div class="modal-body">
                <form action="" class="form-horizontal">
                    <div class="form-group">
                        <label class="control-label col-lg-2">Kas / Bank :</label>
                        <div class="col-lg-5">
                            <select name="kas_kredit" id="kas_kredit" class="form-control"></select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-2">Jumlah :</label>
                        <div class="col-lg-5">
                            <input type="text" class="form-control text-right" id="jumlah_uang_muka" name="jumlah_uang_muka">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-2">Keterangan :</label>
                        <div class="col-lg-10">
                            <textarea name="keterangan" id="keterangan" cols="30" rows="3" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-12 text-right">
                            <button type="button" id="btn-cancel-uang-muka" class="btn btn-danger btn-labeled btn-xs"><b><i class=" icon-circle-left2"></i></b> Batal</button>
                            <button type="button" id="btn-update-uang-muka" class="btn btn-info btn-labeled btn-xs"><b><i class="icon-floppy-disk"></i></b> Simpan</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>


@endsection
@section('script')
<script src="{{ asset('back/global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('back/global_assets/js/plugins/ui/moment/moment.min.js') }}"></script>
<script src="{{ asset('back/global_assets/js/plugins/pickers/daterangepicker.js') }}"></script>
<script src="{{ asset('back/bootstrap-editable.min.js') }}"></script>
<script src="{{ asset('back/jquery.number.js') }}"></script>
<script src="{{ asset('back/global_assets/js/plugins/jquery-mask/dist/jquery.mask.js') }}"></script>
<script src="{{ asset('back/jquery.autocomplete.js') }}"></script>
<script src="{{ asset('back/assets/js/tambahan.js') }}"></script>
<script>

window.onload = function () {
	show_loading('#panel-buat-item');
	show_loading('#panel-item-list');
	hide_loading('#panel-buat-item', 500);
	hide_loading('#panel-item-list', 500);
}


$(document).ready(function() {

    $.getIdTransaksi = function(){
        $.ajax({
            url: "{{ url('get-id-penjualan') }}",
            type: "GET",
            data: {},
            success: function(data, status, xhr){
                $('input[name="no_penjualan"]').val(data);
            },
            error: function(jqXhr, textStatus, errorMessage){
                alert('Ada Masalah Saat Menampilkan No.Penjualan')
            }
        });
    }

    $.setupFormTambahPenjualan =  function(){
        $.getItemSearch({ key: '', });
        $.getIdTransaksi();
        $("#costumer").val(null).trigger('change');
        $("#kas").val(null).trigger('change');
        $("#kas_kredit").val(null).trigger('change');
        $("#termin").val('1').trigger('change');

        $("#jumlah_uang_muka").val('');
        $("#keterangan").val('');
        let html_table_empty = `<tr id="empty-rows"><td colspan="6" class="text-bold text-center">Data Item Belum Ada</td></tr>`;
        $('table#table-transaksi > tbody').empty().append(html_table_empty);
        $('#total_akhir').val(0);
        $('#uang_muka').val(0);
    }


    $.getTotalPembelian = function(){
        let total = 0;
        $('table#table-transaksi > tbody > tr ').each(function(index, tr){
            total += Number($(tr).find('td:eq(4)').html().replace(/,/g, ''));
        });
        return total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    $.getItemSearch = function(options){

        $.ajax({
            url: "{{ url('get-item-penjualan') }}",
            type: "GET",
            data: {
                key: options.key
            },
            success: function(data, status, xhr){

                let html_item = `@include('backoffice.page.transaksi_penjualan.partials.item_list')`;
                let url_image = '{{ asset("back/gambar-item") }}';
                $('#container-list-item').empty();
                data.forEach((value, index) => {
                    $(html_item)
                    .find('a#btn-heading-text').attr('data-item', JSON.stringify(value)).end()
                    .find('h6.media-heading').text(value.nama_item).end()
                    .find('img').attr('src', value.gambar_item != null ? url_image + '/' + value.gambar_item : url_image + '/' + 'not-found.jpg').end()
                    .find('ul li:eq(0)').text(value.history_item_count != null ? Number(value.history_item_count).toFixed(0) +' '+ value.get_satuan_penjualan.satuan : 0  +' '+ value.get_satuan_penjualan.satuan).end()
                    .find('a#btn-heading-text').on('click', function(){
                        show_loading('#panel-buat-item');
                        show_loading('#panel-item-list');


                        event.preventDefault();
                        let item = JSON.parse($(this).attr('data-item'));
                        console.log(item)
                        let item_table = `@include('backoffice.page.transaksi_penjualan.partials.item_table')`;
                        var satuan = item.satuan_item.map(value => {
                            let stn = {
                                value: value.satuan.id, text: value.satuan.satuan,
                            }
                            return stn;
                        });
                        if ($('#table-transaksi tbody tr#empty-rows').length) {
                            $('#table-transaksi tbody tr#empty-rows').detach();
                        }
                        $(item_table)
                        .find('td:eq(0)').attr("data-id", item.id).end()
                        .find('img').attr('src', item.gambar_item != null ? url_image + '/' + item.gambar_item : url_image + '/' + 'not-found.jpg').end()
                        .find('a#text-name-item').text(item.nama_item).end()
                        .find('#qty').editable({
                            display: function(value, sourceData){
                                $(this).text(value).number( true, 0)
                            }
                        }).on('save', function(e, params) {
                            let jumlah  = params.newValue.replace(/,/g, '');
                            let harga = $(this).closest('tr').find('#harga').text().replace(/,/g, '');
                            $(this).closest('tr').find('td:eq(4)').text(parseInt(jumlah) * parseInt(harga)).number( true, 0)
                            $('#total_akhir').val($.getTotalPembelian())
                        }).on('shown', function(ev, editable){
                            setTimeout(function() {
                                editable.input.$input.select();
                            },0);
                        }).end()
                        .find('a#satuan').editable({
                            value: item.get_satuan_penjualan.id,
                            source: satuan
                        }).end()
                        .find('#harga').editable({
                            display: function(value, sourceData){
                                $(this).text(value).number( true, 0)
                            }
                        }).on('shown', function(ev, editable){
                            setTimeout(function() {
                                editable.input.$input.select();
                            },0);
                        }).on('save', function(e, params) {
                            let jumlah = $(this).closest('tr').find('#qty').text().replace(/,/g, '');
                            let harga  = params.newValue.replace(/,/g, '');
                            $(this).closest('tr').find('td:eq(4)').text(parseInt(jumlah) * parseInt(harga)).number( true, 0)
                            $('#total_akhir').val($.getTotalPembelian());
                        }).end()
                        .find('a#remove').on('click', function(){
                            event.preventDefault()
                            var html_table_empty = `<tr id="empty-rows"><td colspan="6" class="text-bold text-center">Data Item Belum Ada</td></tr>`;
                            $(this).closest('tr').detach();
                            if($('#table-transaksi tbody tr').length == 0){
                                table.find('tbody').append(html_table_empty);
                                $('#total_akhir').val(0);
                            }
                            $('#total_akhir').val($.getTotalPembelian());
                        }).end()
                        .appendTo('table#table-transaksi tbody').promise().done(function(){
                            $('#input-pencarian-item').val('');
                            $.getItemSearch({ key: '' });
                            hide_loading('#panel-buat-item', 500);
                            hide_loading('#panel-item-list', 500);
                        });

                        // $.when($('#input-pencarian-item').val('')).done(function(){
                        //     $.getItemSearch({ key: '' });
                        // });

                    }).end()
                    .appendTo('#container-list-item');
                })
            },
            error: function(jqXhr, textStatus, errorMessage){
                alert('Ada Masalah !');
                console.log(errorMessage)
            }
        });
    }

    $('#input-pencarian-item').on('keyup', function(e){
        $.getItemSearch({
            key: e.target.value.length >= 3 ? e.target.value : ''
        });
    });

    $.setupFormTambahPenjualan();


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

    $("#kas").select2({
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

    $("#kas_kredit").select2({
		allowClear: true,
		placeholder: 'Pilih Kas',
	    dropdownAutoWidth : true,
        // width: 'resolve',
        dropdownParent: $('#modal_uang_muka'),
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



    $('input[name="tgl_penjualan"]').daterangepicker({
        locale: { format: 'DD/MM/YYYY' },
        singleDatePicker: true
    }).on('apply.daterangepicker', function(ev, picker){
        let tgl_sekarang = picker.startDate._d;
        let hari = $('#hari').val();
        $('#tgl_jatuh_tempo').data('daterangepicker').setStartDate(moment(tgl_sekarang).add(hari, 'days').format('DD/MM/YY'));
    });

    $('#tgl_jatuh_tempo').daterangepicker({
        locale: { format: 'DD/MM/YYYY' },
        singleDatePicker: true
    }).on('apply.daterangepicker', function(ev, picker){
        let tgl_sekarang = moment($('input[name="tgl_penjualan"]').data('daterangepicker').startDate._d);
        let tgl_jatuh_tempo = moment(picker.startDate._d);
        $('#hari').val(tgl_jatuh_tempo.diff(tgl_sekarang, 'days'));
    });

    $('#termin').select2().on('change', function(e){
        $('#kas').val(null).trigger('change');
        $('#jumlah_uang_muka').val('');
        $('#kas_kredit').val(null).trigger('change');
        $('#uang_muka').val('');
        $('#keterangan').val('');
        if(e.target.value == '1'){
            $('div#opsi_cash').removeClass('hidden');
            $('div#opsi_credit').addClass('hidden');
            $('#kolom_uang_muka').addClass('hidden');
        } else {
            $('div#opsi_cash').addClass('hidden');
            $('div#opsi_credit').removeClass('hidden');
            let tgl_sekarang = $('input[name="tgl_penjualan"]').data('daterangepicker').startDate._d;
            let hari = $('#hari').val();
            $('#tgl_jatuh_tempo').data('daterangepicker').setStartDate(moment(tgl_sekarang).add(hari, 'days').format('DD/MM/YY'));
            $('#uang_muka').val(0);
            $('#kolom_uang_muka').removeClass('hidden');
        }
    });

    $('#hari').on('change', function(e){
        let tgl_sekarang = $('input[name="tgl_penjualan"]').data('daterangepicker').startDate._d;
        let hari = e.target.value;
        $('#tgl_jatuh_tempo').data('daterangepicker').setStartDate(moment(tgl_sekarang).add(hari, 'days').format('DD/MM/YY'));
    });

    var table = $('#table-transaksi');

    $('#btn-uang-muka').on('click', function(){
        $('#modal_uang_muka').modal({backdrop: 'static', keyboard: false}).modal('show').on('shown.bs.modal', function(){
            $('#jumlah_uang_muka').mask("#,##0", {reverse: true});
        });
    });

    $('#btn-update-uang-muka').on('click', function(){

        let kas_kredit = $('#kas_kredit').val();
        let jumlah = $('#jumlah_uang_muka').val() != '' ? $('#jumlah_uang_muka').val() : 0;
        let keterangan = $('#keterangan').val();

        if(kas_kredit == null || kas_kredit == ''){
            Swal.fire({ type: 'error', title: 'Peringatan', text: 'Kas/Bank Belum DIpilih' });
            return false;
        }

        $('#uang_muka').val(jumlah).mask("#,##0", {reverse: true});
        $('#modal_uang_muka').modal('hide')
    });

    $('#btn-cancel-uang-muka').on('click', function(){
        $('#modal_uang_muka').modal('hide')
    });

    $('#form-transaksi').on('submit', function() {
        event.preventDefault();
        var form = $(this);
        var url = form.attr('action');
        var tr = $('#table-transaksi tbody tr:not(#empty-rows)');
        var termin = $('#termin').val();
        var kas;
        var uang_muka;
        var keterangan;
        var hari_jatuh_tempo;
        var tgl_jatuh_tempo;

        if(termin == '1'){
            kas = $('#kas').val()
            hari_jatuh_tempo = '',
            tgl_jatuh_tempo = '';
            uang_muka = '';
        } else {
            kas = $('#kas_kredit').val()
            hari_jatuh_tempo = $('#hari').val();
            tgl_jatuh_tempo = $('#tgl_jatuh_tempo').val();
            uang_muka = $('#uang_muka').val();
        }

        var jsonObj = [];
    	tr.each(function(){
	        item = {}
	        item ["item_id"]                = $(this).find('td:eq(0)').attr('data-id');
            item ["satuan"]                 = $(this).find('a#satuan').text();
	        item ["qty"]                    = parseInt($(this).find('a#qty').text());
	        item ["harga"]                  = parseInt($(this).find('a#harga').text().replace(/,/g, ''));
	        item ["sub_total"]              = parseInt($(this).find('td:eq(4)').text().replace(/,/g, ''));
	        jsonObj.push(item);
    	});

        var formData = {
            no_penjualan : $('input[name="no_penjualan"]').val(),
            costumer : $('select[name="costumer"]').val(),
            tgl_penjualan : $('input[name="tgl_penjualan"]').val(),
            termin: $('#termin').val(),
            kas: kas,
            uang_muka: uang_muka,
            keterangan: $('#keterangan').val(),
            hari_jatuh_tempo : hari_jatuh_tempo,
            tgl_jatuh_tempo : tgl_jatuh_tempo,
            total: $('#total_akhir').val(),
            list_item: JSON.stringify(jsonObj)
        }

        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            success: function(response){
                if(response.status == "sukses"){
                    $.setupFormTambahPenjualan();
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
