@extends('backoffice.layout.backofficelayout')
@section('title'){{ env("SET_TOKO", "NOT SET") }} | Set Akun Awal - Buat Set Nilai Akun @endsection
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
		<div class="page-title">
			<a href="{{ url('sistem/set-nilai-akun') }}"><h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Data Nilai Awal Akun</span></h4></a>
		<a class="heading-elements-toggle"><i class="icon-more"></i></a></div>
	</div>

	<div class="breadcrumb-line"><a class="breadcrumb-elements-toggle"><i class="icon-menu-open"></i></a>
		<ul class="breadcrumb">
			<li><a href="index.html"><i class="icon-equalizer2 position-left"></i> Sistem</a></li>
			<li class="active">Buat Set Nilai Awal Akun</li>
		</ul>
	</div>
</div>

<div class="content">
	<div class="row">
		<div class="col-sm-12">
            

			<div class="panel panel-flat border-top-xlg border-top-green-600" id="panel-buat-item">
                

				<div class="panel-heading">
					<h5 class="panel-title"><span class="text-semibold"><i class="icon-file-plus"></i> Buat Set Nilai Awal Akun</span></h5>
				</div>
				
				<div class="panel-body">
					<form action="{{ url(Request::segment(1).'/'.Request::segment(2)) }}" method="POST" enctype="multipart/form-data" class="form-horizontal" id="form-transaksi">
                    @csrf
					<div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Kode Transaksi :</label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" placeholder="Kode Transaksi" readonly name="kode_transaksi" value="{{ $kode }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Tanggal :</label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" placeholder="Tanggal" name="tgl_set">
                            </div>
                        </div>
                    </div>
				</div>
                <div class="panel-body">
                        <div class="form-group">
                            <div class="col-lg-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Cari Akun" id="pencarian-akun">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="button"><i class="icon-search4"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="table-responsive">

                    
                    
                    <table class="table table-sm table-bordered" id="table-transaksi">
                        <thead>
                            <tr class="bg-success">
                                <th class="text-center" width="150">Kode Akun</th>
                                <th class="text-center">Nama Akun</th>
                                <th class="text-center" width="200">Debit</th>
                                <th class="text-center" width="200">Kredit</th>
                                <th width="10">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="empty-rows">
                                <td colspan="6" class="text-bold text-center">Data Belum Ada</td>
                            </tr>
                        </tbody>
                    </table>



                </div>
				<div class="panel-footer"><a class="heading-elements-toggle" style="margin-top: 200px;"><i class="icon-more"></i></a>
					<div class="heading-elements">
						<div class="pull-right">
							<a href="{{ url('sistem/set-nilai-akun') }}" class="btn btn-default"><b><i class="icon-circle-left2"></i></b> Kembali</a>
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
<script src="{{ asset('back/bootstrap-editable.min.js') }}"></script>
<script src="{{ asset('back/jquery.number.js') }}"></script>
<script src="{{ asset('back/global_assets/js/plugins/jquery-mask/dist/jquery.mask.js') }}"></script>
<script src="{{ asset('back/jquery.autocomplete.js') }}"></script>
<script src="{{ asset('back/assets/js/tambahan.js') }}"></script>
<script>

window.onload = function () {
	show_loading('#panel-buat-item');
	hide_loading('#panel-buat-item', 500);
}

$('input[name="tgl_set"]').daterangepicker({ 
    singleDatePicker: true
});

$("#pencarian-akun").autocomplete({
        serviceUrl: "{{ url('getakunautocomplete')}}", 
        dataType: "JSON",
        onSelect: function (suggestion) {
            console.log(suggestion);
            $(this).val("");
            if ($('#table-transaksi tbody tr#empty-rows').length) {
                $('#table-transaksi tbody tr#empty-rows').detach();
            }
            var html = `<tr>
                            <td class="text-bold text-center"></td>
                            <td class="text-bold text-left"></td>
                            <td class="text-right">
                                <a href="#" id="debit" data-type="text" data-title="Debit">0</a>
                            </td>
                            <td class="text-right">
                                <a href="#" id="kredit" data-type="text" data-title="Kredit">0</a>    
                            </td>
                            <td class="text-center">
                                <a id="remove" href="#"><i class="icon-trash text-danger"></i></a>
                            </td>
                        </tr>`;
            $(html)
            .find('td:eq(0)').attr("data-id", suggestion.item.id).text(suggestion.item.kode_akun).end()
            .find('td:eq(1)').text(suggestion.item.nama_akun).end()
            .find('#debit').editable({
                display: function(value, sourceData){
                    $(this).text(value).number(true, 0)
                }
            }).on('shown', function(ev, editable){
                setTimeout(function() {
                    editable.input.$input.select();
                },0);
            }).on('hidden', function(e, reason){
                let kredit = $(this).closest('tr').find('#kredit').text().replace(/,/g, '');
                if(kredit > 0){
                    Swal.fire({ type: 'error', title: 'Ada Masalah.', text: 'Debit dan Kredit Tidak Boleh Bersamaan Terisi', })
                    $(this).text('0');
                }
            }).end()
            .find('#kredit').editable({
                display: function(value, sourceData){
                    $(this).text(value).number(true, 0)
                }
            }).on('hidden', function(e, reason){
                let debit = $(this).closest('tr').find('#debit').text().replace(/,/g, '');
                if(debit > 0){
                    Swal.fire({ type: 'error', title: 'Ada Masalah.', text: 'Debit dan Kredit Tidak Boleh Bersamaan Terisi', })
                    $(this).text('0');  
                }
            }).on('shown', function(ev, editable){
                setTimeout(function() {
                    editable.input.$input.select();
                },0);
            }).end()
            .find('a#remove').on('click', function(){
                event.preventDefault()
                var html_table_empty = `<tr id="empty-rows"><td colspan="6" class="text-bold text-center">Data Item Belum Ada</td></tr>`;
                $(this).closest('tr').detach();
                if($('#table-transaksi tbody tr').length == 0){
                    table.find('tbody').append(html_table_empty);
                }
            }).end()
            .appendTo("table tbody");
        }
    });

    $('#form-transaksi').on('submit', function() {
        event.preventDefault();
        var form = $(this);
        var url = form.attr('action');
        var tr = $('#table-transaksi tbody tr:not(#empty-rows)');

        var jsonObj = [];
    	tr.each(function(){
	        item = {}
	        item ["akun_id"]         = $(this).find('td:eq(0)').attr('data-id');
            item ["nominal_debit"]   = parseInt($(this).find('a#debit').text().replace(/,/g, ''));
	        item ["nominal_kredit"]  = parseInt($(this).find('a#kredit').text().replace(/,/g, ''));
	        jsonObj.push(item);
    	});

        var formData = {
            kode_transaksi : $('input[name="kode_transaksi"]').val(),
            tgl_set : $('input[name="tgl_set"]').val(),
            list_item: JSON.stringify(jsonObj)
        }

        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            success: function(response){
                if(response.status == "sukses"){
                    window.location.href = "{{ url('sistem/set-nilai-akun') }}";
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


</script>
@endsection