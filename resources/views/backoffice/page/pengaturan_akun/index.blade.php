@extends('backoffice.layout.backofficelayout')
@section('title'){{ env("SET_TOKO", "NOT SET") }} | Pengaturan - Pengaturan Akun @endsection
@section('style')

@endsection
@section('content')
<!-- Page header -->
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h2><i class="icon-cog2 position-left"></i> <span class="text-semibold">Pengaturan Akun</span></h2>
		</div>
	</div>
	<div class="breadcrumb-line"></div>
</div>
<!-- /page header -->


<div class="content">
	<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-flat border-top-xlg border-top-green-600" id="panel-buat-barang">
				<div class="panel-heading">
					<h5 class="panel-title"><span class="text-semibold"><i class="icon-wrench"></i> Pengaturan Akun</span></h5>
				</div>

				<div class="panel-body">
					<form action="{{ url(Request::segment(1).'/'.Request::segment(2)) }}" method="POST" id="form-pengaturan-akun" class="form-horizontal">
                            
                        <div class="tabbable">
                            <ul class="nav nav-tabs nav-tabs-highlight">
                                <li class="active"><a href="#umum" data-toggle="tab"><i class="icon-grid6"></i> Umum</a></li>
                                <li><a href="#pembelian_hutang" data-toggle="tab"><i class="icon-basket"></i> Pembelian/Hutang</a></li>
                                <li><a href="#penjualan_piutang" data-toggle="tab"><i class="icon-store2"></i> Penjualan/Piutang</a></li>
                                <li><a href="#penyesuian" data-toggle="tab"><i class="icon-equalizer3"></i> Penyesuaian</a></li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="umum">
                                    
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label text-right">Saldo Awal :</label>
                                        <div class="col-sm-4">
                                            <select name="saldo_awal" id="saldo_awal" class="form-control select-2-akun"></select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label text-right">Kas :</label>
                                        <div class="col-sm-4">
                                            <select name="kas" id="kas" class="form-control select-2-akun"></select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label text-right">Inventory :</label>
                                        <div class="col-sm-4">
                                            <select name="inventory" id="inventory" class="form-control select-2-akun"></select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label text-right">HPP :</label>
                                        <div class="col-sm-4">
                                            <select name="hpp" id="hpp" class="form-control select-2-akun"></select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label text-right">Penjualan :</label>
                                        <div class="col-sm-4">
                                            <select name="penjualan" id="penjualan" class="form-control select-2-akun"></select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label text-right">Retur Jual :</label>
                                        <div class="col-sm-4">
                                            <select name="retur_jual" id="retur_jual" class="form-control select-2-akun"></select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label text-right">Bahan Baku :</label>
                                        <div class="col-sm-4">
                                            <select name="bahan_baku" id="bahan_baku" class="form-control select-2-akun"></select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label text-right">Listrik, Air, Telpon & Internet :</label>
                                        <div class="col-sm-4">
                                            <select name="listrik_air_telepon_internet" id="listrik_air_telepon_internet" class="form-control select-2-akun"></select>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="pembelian_hutang">
                                    
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label text-right">Hutang :</label>
                                        <div class="col-sm-4">
                                            <select name="hutang" id="hutang" class="form-control select-2-akun"></select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label text-right">Deposit Supplier :</label>
                                        <div class="col-sm-4">
                                            <select name="deposit_supplier" id="deposit_supplier" class="form-control select-2-akun"></select>
                                        </div>
                                    </div>
                                    

                                </div>

                                <div class="tab-pane fade" id="penjualan_piutang">
                                    
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label text-right">Piutang :</label>
                                        <div class="col-sm-4">
                                            <select name="piutang" id="piutang" class="form-control select-2-akun"></select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label text-right">Deposit Costumer :</label>
                                        <div class="col-sm-4">
                                            <select name="deposit_costumer" id="deposit_costumer" class="form-control select-2-akun"></select>
                                        </div>
                                    </div>

                                </div>

                                <div class="tab-pane fade" id="penyesuian">

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label text-right">P/K Kas :</label>
                                        <div class="col-sm-4">
                                            <select name="p_k_kas" id="p_k_kas" class="form-control select-2-akun"></select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label text-right">P/K Stok :</label>
                                        <div class="col-sm-4">
                                            <select name="p_k_stok" id="p_k_stok" class="form-control select-2-akun"></select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label text-right">P/K Hutang :</label>
                                        <div class="col-sm-4">
                                            <select name="p_k_hutang" id="p_k_hutang" class="form-control select-2-akun"></select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label text-right">P/K Piutang :</label>
                                        <div class="col-sm-4">
                                            <select name="p_k_piutang" id="p_k_piutang" class="form-control select-2-akun"></select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label text-right">Laba Rugi :</label>
                                        <div class="col-sm-4">
                                            <select name="laba_rugi" id="laba_rugi" class="form-control select-2-akun"></select>
                                        </div>
                                    </div>

                                </div>
                                
                            </div>

                            
						


                        </div>    
                        

						
					</div>
                    
                    <div class="panel-footer"><a class="heading-elements-toggle"><i class="icon-more"></i></a><a class="heading-elements-toggle"><i class="icon-more"></i></a>
                        <div class="heading-elements">
                            <div class="pull-right">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <button type="submit" class="btn btn-sm bg-success btn-labeled text-bold"><b><i class="icon-floppy-disk"></i></b> Simpan Pengaturan</button>
                            </div>
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
<script src="{{ asset('back/global_assets/js/plugins/jquery-mask/dist/jquery.mask.js') }}"></script>
<script src="{{ asset('back/global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('back/assets/js/tambahan.js') }}"></script>
<script>
var pengaturan = {!! $pengaturan !!};

pengaturan.forEach(function(item){
    var newOption = new Option(item.kode+' - '+item.value , item.id, false, false);
    $(`#${item.setting}`).append(newOption).trigger('change');
});


$("#saldo_awal,#kas,#inventory,#hpp,#penjualan,#retur_jual,#bahan_baku, #listrik_air_telepon_internet, #hutang, #deposit_supplier, #piutang, #deposit_costumer, #p_k_kas,#p_k_stok,#p_k_hutang,#p_k_piutang,#laba_rugi").select2({
    allowClear: true,
    placeholder: 'Silahkan Pilih Akun',
    dropdownAutoWidth : true,
    ajax: {
        url: "{{ url('getakunselect2') }}",
        dataType: 'json',
        data: function (params) {
            return {
                q: params.term,
            };
        },
        delay: 250,
        processResults: function (data) {
            return {
                results:  $.map(data, function (item) {
                    return {
                        text: item.kode_akun + ' - ' +item.nama_akun,
                        id: item.id,
                        item : item
                    }
                })
            };
    }, 
    cache: true, 
    }
});


</script>
@endsection

