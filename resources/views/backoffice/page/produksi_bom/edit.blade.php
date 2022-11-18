@extends('backoffice.layout.backofficelayout')
@section('title'){{ env("SET_TOKO", "NOT SET") }} | Bill Of Material - Produksi Bill Of Material @endsection
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
			<a href="{{ url('produksi/bill-of-material') }}"><h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Data Produksi Bill Of Material</span></h4></a>
		<a class="heading-elements-toggle"><i class="icon-more"></i></a></div>
	</div>

	<div class="breadcrumb-line"><a class="breadcrumb-elements-toggle"><i class="icon-menu-open"></i></a>
		<ul class="breadcrumb">
			<li><a href="index.html"><i class="icon-grid position-left"></i> Data Produksi Bill Of Material</a></li>
			<li class="active">Buat Produksi Bill Of Material</li>
		</ul>
	</div>
</div>

<div class="content">
	<div class="row">
		<div class="col-sm-12">
            

			<div class="panel panel-flat border-top-xlg border-top-green-600" id="panel-buat-item">
                

				<div class="panel-heading">
					<h5 class="panel-title"><span class="text-semibold"><i class="icon-file-plus"></i> Buat Produksi Bill Of Material</span></h5>
				</div>
				
				<div class="panel-body">
					<form action="{{ url(Request::segment(1).'/'.Request::segment(2)) }}" method="POST" enctype="multipart/form-data" class="form-horizontal" id="form-transaksi">
                    @if ($errors->any())
                    <code>
                        {{ $errors }}
                    </code>
                    @endif
                        @csrf
					<div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-lg-3 control-label">No. Produksi :</label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" placeholder="No. Produksi" readonly name="no_produksi" value="{{ $produksi->no_produksi }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Bill Of Material :</label>
                            <div class="col-lg-6">
                                <select id="bom" class="form-control" name="bom"></select>
                                <input type="hidden" name="satuan_item_id" id="satuan_item_id">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Qty :</label>
                            <div class="col-lg-2">
                                <input type="text" class="form-control" placeholder="Qty" name="qty" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Tanggal :</label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" placeholder="Tanggal" name="tgl_produksi">
                            </div>
                        </div>
                    </div>
				</div>
                <div class="tabbable tab-content-bordered">
                    <ul class="nav nav-tabs nav-tabs-highlight">
                        <li class="active"><a href="#bordered-tab1" data-toggle="tab"><i class="icon-cube4"></i> Bahan-bahan</a></li>
                        <li><a href="#bordered-tab2" data-toggle="tab"><i class="icon-cube3"></i> Hasil Produksi</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane has-padding active" id="bordered-tab1">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered" id="table-transaksi-bahan">
                                    <thead>
                                        <tr class="bg-success">
                                            <th>Nama Bahan</th>
                                            <th class="text-center" width="250">Satuan</th>
                                            <th class="text-center" width="150">Qty Dibutuhakan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="empty-rows">
                                            <td colspan="6" class="text-bold text-center">Data Item Belum Ada</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane has-padding" id="bordered-tab2">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered" id="table-transaksi-produksi">
                                    <thead>
                                        <tr class="bg-success">
                                            <th>Nama Item</th>
                                            <th class="text-center" width="250">Satuan</th>
                                            <th class="text-center" width="150">Qty Hasil Produksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="empty-rows">
                                            <td colspan="6" class="text-bold text-center">Data Item Belum Ada</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
				<div class="panel-footer"><a class="heading-elements-toggle" style="margin-top: 200px;"><i class="icon-more"></i></a>
					<div class="heading-elements">
						<div class="pull-right">
							<a href="{{ url('produksi/produksi-bom') }}" class="btn btn-default"><b><i class="icon-circle-left2"></i></b> Kembali</a>
							<button type="submit" class="btn btn-success"><b><i class="icon-floppy-disk"></i></b> Simpan Transaksi Produksi</button>
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


$(document).ready(function() {
    (function($) {
        $.rand = function(arg) {
            if ($.isArray(arg)) {
                return arg[$.rand(arg.length)];
            } else if (typeof arg === "number") {
                return Math.floor(Math.random() * arg);
            } else {
                return 4;  // chosen by fair dice roll
            }
        };
    })(jQuery);

    $('input[name="tgl_produksi"]').daterangepicker({ 
        singleDatePicker: true
    });

    var satuan;

    $("select[name='bom']").select2({
		allowClear: true,
		placeholder: 'Pilih BOM',
	    dropdownAutoWidth : true,
        width: 'resolve',
        
        ajax: {
            url: "{{ url('getbom') }}",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term,
                    category: 1
                };
            },
            processResults: function (data) {
                return {
                    results:  $.map(data, function (item) {
                        return {
                            text: item.item.nama_item,
                            id: item.id,
                            item : item
                        }
                    })
                };
        }, 
        cache: true, 
        }
	}).on('select2:select', function (e) {
        var data = e.params.data;
        
        Swal.fire({
            title: 'Masukan Jumlah Produksi',
            input: 'number',
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'SET',
            showLoaderOnConfirm: true,
        }).then((result) => {
            if(result.dismiss == 'cancel') {
                $('input[name="qty"]').val("");
                $('input[name="satuan_item_id"]').val("");
                $("select[name='bom']").val(null).trigger('change');
            } else {
                $('input[name="qty"]').val(result.value);
                $('input[name="satuan_item_id"]').val(data.item.satuan_item_id);
                
                if ($('#table-transaksi-bahan tbody tr#empty-rows').length && $('#table-transaksi-produksi tbody tr#empty-rows').length) {
                    $('#table-transaksi-bahan tbody tr#empty-rows').detach();
                    $('#table-transaksi-produksi tbody tr#empty-rows').detach();
                }

                $.each(data.item.det_bom, function(index, value) {
                    var html_info = [' <span class="label bg-success-400">Stock Tersedia</span>', ' <span class="label bg-danger">Stock Tidak Cukup</span>'];
                    var stock_dibutuhkan = Number(value.qty).toFixed(0) * $('input[name="qty"]').val();
                    var stock_tersedia = value.item.history_item_count / value.satuan_item.qty_konversi;
                    var html_bahan = `<tr> <td class="text-bold">Ayam Potong</td> <td class="text-center"> </td> <td class="text-center"> </td> </tr>`;
                    $(html_bahan)
                    .find('td:eq(0)').attr('data-ready', stock_tersedia >= stock_dibutuhkan ? 'ya' : 'tdk').attr('data-id', value.item_id).attr('data-stock', value.item.history_item_count).text(value.item.nama_item).end()
                    .find('td:eq(1)').text(value.satuan_item.satuan.satuan).end()
                    .find('td:eq(2)').text(stock_dibutuhkan).end()
                    .find('td:eq(0)').append(html_info[stock_tersedia >= stock_dibutuhkan ? 0 : 1]).end()
                    .find('td:eq(2)').addClass(stock_tersedia >= stock_dibutuhkan ? 'bg-success' : 'bg-danger').end()
                    .appendTo("#table-transaksi-bahan tbody");
                });

                console.log(data);

                var html_produksi = `<tr> <td class="text-bold">Ayam Potong</td> <td class="text-center"> </td> <td class="text-center"> </td> </tr>`;
                $(html_produksi)
                    .find('td:eq(0)').attr('data-id', data.id).text(data.text).end()
                    .find('td:eq(1)').text(data.item.satuan_item.satuan.satuan).end()
                    .find('td:eq(2)').text(Number(data.item.qty).toFixed(0) * $('input[name="qty"]').val()).end()
                    .appendTo("#table-transaksi-produksi tbody");

                

            }
        })
    }).on('select2:unselecting', function (e) {
        $('input[name="qty"]').val("");
        $('input[name="satuan_item_id"]').val("");
        var html_table_empty = `<tr id="empty-rows"><td colspan="6" class="text-bold text-center">Data Item Belum Ada</td></tr>`;
        $('#table-transaksi-bahan tbody').html("").append(html_table_empty);
        $('#table-transaksi-produksi tbody').html("").append(html_table_empty);
    });

    $('#change-satuan').on('click', function(){
        if(satuan.length == 1) {
            Swal.fire({ type: 'error', title: 'Peringatan', text: 'Item Hanya Memiliki 1 Satuan !' });
            return false;
        }
        show_loading('#panel-buat-item');
        $('input[name="satuan"]').val(jQuery.rand(satuan));
        hide_loading('#panel-buat-item', 500);
    });
    
    


    var table = $('#table-transaksi');

    

    $('#form-transaksi').on('submit', function() {
        event.preventDefault();
        var form = $(this);
        var url = form.attr('action');
        var tr_bahan = $('#table-transaksi-bahan tbody tr:not(#empty-rows)');

        var jsonObjBahan = [];
    	tr_bahan.each(function(){
	        item = {}
	        item ["item_id"]                = $(this).find('td:eq(0)').attr('data-id');
            item ["satuan"]                 = $(this).find('td:eq(1)').text();
	        item ["qty"]                    = parseInt($(this).find('td:eq(2)').text());
            item ["ready"]                  = $(this).find('td:eq(0)').attr('data-ready');
	        jsonObjBahan.push(item);
    	});

        var formData = {
            no_produksi : $('input[name="no_produksi"]').val(),
            tgl_produksi : $('input[name="tgl_produksi"]').val(),
            bom_id : $('select[name="bom"]').val(),
            satuan_item_id : $('input[name="satuan_item_id"]').val(),
            qty : $('input[name="qty"]').val(),
            list_bahan: JSON.stringify(jsonObjBahan)
        }

        var check_ready = $('#table-transaksi-bahan tbody tr td[data-ready="tdk"]');
        if(check_ready.length > 0){
            Swal.fire({ type: 'error', title: 'Peringatan', text: `Tidak Dapat Memproses Produksi, Karena Ada ${check_ready.length} Item Stocknya Habis !` });
            return false;
        }


        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            success: function(response){
                console.log(response);
                if(response.status == "sukses"){
                    window.location.href = "{{ url('produksi/produksi-bom') }}";
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