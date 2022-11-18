@extends('backoffice.layout.backofficelayout')
@section('title'){{ env("SET_TOKO", "NOT SET") }} | Transaksi Pembelian - Edit Transaksi Pembelian @endsection
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
			<a href="{{ url('/pembelian') }}"><h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Data Transaksi Pembelian</span></h4></a>
		<a class="heading-elements-toggle"><i class="icon-more"></i></a></div>
	</div>

	<div class="breadcrumb-line"><a class="breadcrumb-elements-toggle"><i class="icon-menu-open"></i></a>
		<ul class="breadcrumb">
			<li><a href="index.html"><i class="icon-grid position-left"></i> Data Transaksi</a></li>
			<li class="active">Edit Transaksi Pembelian</li>
		</ul>
	</div>
</div>

<div class="content">
	<div class="row">
		<div class="col-sm-12">
            

			<div class="panel panel-flat border-top-xlg border-top-green-600" id="panel-buat-item">
                

				<div class="panel-heading">
					<h5 class="panel-title"><span class="text-semibold"><i class="icon-file-plus"></i> Edit Transaksi Pembelian</span></h5>
				</div>
				
				<div class="panel-body">
					<form action="{{ url(Request::segment(1).'/'.$transaksi->id) }}" method="POST" enctype="multipart/form-data" class="form-horizontal" id="form-transaksi">
                    @if ($errors->any())
                    <code> 
                        {{ $errors }}
                    </code>
                    @endif
                        @csrf
					<div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-lg-3 control-label">No. Pembelian :</label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" placeholder="No. Pembelian" readonly name="no_pembelian" value="{{ $transaksi->no_pembelian }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Tanggal :</label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" placeholder="Tanggal" name="tgl_pembelian">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Supplier :</label>
                            <div class="col-lg-6">
                                <select id="supplier" class="form-control" name="supplier"></select>
                            </div>
                        </div>
                    </div>
				</div>
                <div class="panel-body">
                        <div class="form-group">
                            <div class="col-lg-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Cari Item" id="pencarian-item">
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
                                <th>Nama Item</th>
                                <th class="text-center" width="80">Qty</th>
                                <th class="text-center" width="90">Satuan</th>
                                <th class="text-center" width="200">Harga</th>
                                <th class="text-center" width="220">Subtotal</th>
                                <th width="10">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="empty-rows">
                                <td colspan="6" class="text-bold text-center">Data Item Belum Ada</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
				<div class="panel-footer"><a class="heading-elements-toggle" style="margin-top: 200px;"><i class="icon-more"></i></a>
					<div class="heading-elements">
						<div class="pull-right">
							<a href="{{ url('pembelian') }}" class="btn btn-default"><b><i class="icon-circle-left2"></i></b> Kembali</a>
							<button type="submit" class="btn btn-success"><b><i class="icon-floppy-disk"></i></b> Update Transaksi</button>
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
<script src="{{ asset('back/numeral.min.js') }}"></script>
<script src="{{ asset('back/global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('back/global_assets/js/plugins/ui/moment/moment.min.js') }}"></script>
<script src="{{ asset('back/global_assets/js/plugins/pickers/daterangepicker.js') }}"></script>
<script src="{{ asset('back/bootstrap-editable.min.js') }}"></script>
<script src="{{ asset('back/jquery.number.js') }}"></script>
<script src="{{ asset('back/global_assets/js/plugins/jquery-mask/dist/jquery.mask.js') }}"></script>
<script src="{{ asset('back/jquery.autocomplete.js') }}"></script>
<script src="{{ asset('back/assets/js/tambahan.js') }}"></script>
<script>
var transaksi = {!! $transaksi !!};
// console.log(transaksi);

window.onload = function () {
	show_loading('#panel-buat-item');
	hide_loading('#panel-buat-item', 500);
}

jQuery.TransaksiPembelianEdit = function(transaksi) {
    var data = transaksi;
    // console.log(data);

    var $newOptionCostumer = $("<option selected='selected'></option>").val(data.supplier.id).text(data.supplier.nama_supplier);
    $("#supplier").focus().append($newOptionCostumer).trigger('change');

    $('input[name="tgl_pembelian"]').daterangepicker({ 
        singleDatePicker: true,
        locale: { 
            format: 'MM/DD/YYYY'
        },
        startDate: moment(data.tgl_pembelian).format('MM/DD/YYYY'),
    });

    if ($('#table-transaksi tbody tr#empty-rows').length) {
        $('#table-transaksi tbody tr#empty-rows').detach();
    }

    data.det_transaksi_pembelian.forEach(isi => {
        console.log(isi);
        var html = `<tr>
                    <td data-id="${isi.id}" data-item_id="${isi.item.id}">Ayam Potong</td>
                    <td class="text-center">
                        <a href="#" id="qty" data-type="text" data-title="Masukan Jumlah">${numeral(isi.qty).format('0,0')}</a>
                    </td>
                    <td class="text-center">
                        <a href="#" id="satuan" data-type="select" data-title="Pilih Satuan"></a>
                    </td>
                    <td class="text-center">
                        <a href="#" id="harga" data-type="text" data-title="Masukan Harga Item"></a>    
                    </td>
                    <td class="text-center text-bold">${numeral(isi.sub_total).format('0,0')}</td>
                    <td class="text-center">
                        <a id="remove" href="#"><i class="icon-trash text-danger"></i></a>
                    </td>
                </tr>`;

        var satuan = isi.item.satuan_item.map(value => {
            let stn = {
                value: value.satuan.id, text: value.satuan.satuan,
            }
            return stn;
        });

        var selected = isi.item.satuan_item.map((el) => el.id).indexOf(isi.satuan_item_id);

        $(html)
        .find('td:eq(0)').attr("data-id", isi.id).attr("data-item_id", isi.item.id).text(isi.item.nama_item).end()
        .find('#qty').editable({
            display: function(value, sourceData){
                value: isi.qty,
                $(this).text(value).number( true, 0)
            }
        }).on('save', function(e, params) {
            let jumlah  = params.newValue.replace(/,/g, '');
            let harga = $(this).closest('tr').find('#harga').text().replace(/,/g, '');
            $(this).closest('tr').find('td:eq(4)').text(parseInt(jumlah) * parseInt(harga)).number( true, 0)
        }).on('shown', function(ev, editable){
            setTimeout(function() {
                editable.input.$input.select();
            },0);
        }).end()
        .find('td:eq(2)').end()
        .find('a#satuan').editable({
            value: isi.item.satuan_item[selected].satuan_id,
            source: satuan,
        }).end()
        .find('#harga').editable({
            value: isi.harga,
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
        }).end()
        .find('a#remove').on('click', function(){
            event.preventDefault()
            var html_table_empty = `<tr id="empty-rows"><td colspan="6" class="text-bold text-center">Data Item Belum Ada</td></tr>`;
            $(this).closest('tr').detach();
            if($('#table-transaksi tbody tr').length == 0){
                $('#table-transaksi').find('tbody').append(html_table_empty);
            }
        }).end()
        .appendTo("table tbody");
    });

   


}


$(document).ready(function() {
    

    $.TransaksiPembelianEdit(transaksi);


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

    

    var table = $('#table-transaksi');

    $("#pencarian-item").autocomplete({
        serviceUrl: "{{ url('getitem?not_category=1')}}",
        dataType: "JSON",
        onSelect: function (suggestion) {
            $(this).val("");
            if ($('#table-transaksi tbody tr#empty-rows').length) {
                $('#table-transaksi tbody tr#empty-rows').detach();
            }
            var html = `<tr>
                            <td data-id="" data-item_id="">Ayam Potong</td>
                            <td class="text-center">
                                <a href="#" id="qty" data-type="text" data-title="Masukan Jumlah">1</a>
                            </td>
                            <td class="text-center">
                                <a href="#" id="satuan" data-type="select" data-title="Pilih Satuan"></a>
                            </td>
                            <td class="text-center">
                                <a href="#" id="harga" data-type="text" data-title="Masukan Harga Item"></a>    
                            </td>
                            <td class="text-center text-bold">0</td>
                            <td class="text-center">
                                <a id="remove" href="#"><i class="icon-trash text-danger"></i></a>
                            </td>
                        </tr>`;

            var satuan = suggestion.item.satuan_item.map(value => {
                let stn = {
                    value: value.satuan.id, text: value.satuan.satuan,
                }
                return stn;
            });

            $(html)
            .find('td:eq(0)').attr("data-item_id", suggestion.item.id).text(suggestion.value).end()
            .find('#qty').editable({
                display: function(value, sourceData){
                    $(this).text(value).number( true, 0)
                }
            }).on('save', function(e, params) {
                let jumlah  = params.newValue.replace(/,/g, '');
                let harga = $(this).closest('tr').find('#harga').text().replace(/,/g, '');
                $(this).closest('tr').find('td:eq(4)').text(parseInt(jumlah) * parseInt(harga)).number( true, 0)
            }).on('shown', function(ev, editable){
                setTimeout(function() {
                    editable.input.$input.select();
                },0);
            }).end()
            .find('td:eq(2)').end()
            .find('a#satuan').editable({
                value: suggestion.item.get_satuan_pembelian.id,
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
	        item ["id"]                     = $(this).find('td:eq(0)').attr('data-id');
	        item ["item_id"]                = $(this).find('td:eq(0)').attr('data-item_id');
            item ["satuan"]                 = $(this).find('a#satuan').text();
	        item ["qty"]                    = parseInt($(this).find('a#qty').text());
	        item ["harga"]                  = parseInt($(this).find('a#harga').text().replace(/,/g, ''));
	        item ["sub_total"]              = parseInt($(this).find('td:eq(4)').text().replace(/,/g, ''));
	        jsonObj.push(item);
    	});

        var formData = {
            no_pembelian : $('input[name="no_pembelian"]').val(),
            supplier : $('select[name="supplier"]').val(),
            tgl_pembelian : $('input[name="tgl_pembelian"]').val(),
            _token: $('meta[name="csrf-token"]').attr('content'),
            list_item: JSON.stringify(jsonObj)
        }

        // console.log(formData);

        $.ajax({
            url: url,
            type: "PUT",
            data: formData,
            success: function(response){
                if(response.status == "sukses"){
                    window.location.href = "{{ url('/pembelian') }}";
                }
                // console.log(response);
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