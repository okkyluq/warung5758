@extends('backoffice.layout.backofficelayout')
@section('title'){{ env("SET_TOKO", "NOT SET") }} | Keuangan - Buat Pembayaran @endsection
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
			<a href="{{ url('keuangan/pembayaran') }}"><h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Data Pembayaran</span></h4></a>
		<a class="heading-elements-toggle"><i class="icon-more"></i></a></div>
	</div>

	<div class="breadcrumb-line"><a class="breadcrumb-elements-toggle"><i class="icon-menu-open"></i></a>
		<ul class="breadcrumb">
			<li><a href="index.html"><i class="icon-grid position-left"></i> Keuangan</a></li>
			<li class="active">Buat Pembayaran</li>
		</ul>
	</div>
</div>

<div class="content">
	<div class="row">
		<div class="col-sm-12">


			<div class="panel panel-flat border-top-xlg border-top-green-600" id="panel-buat-item">


				<div class="panel-heading">
					<h5 class="panel-title"><span class="text-semibold"><i class="icon-file-plus"></i> Buat Pembayaran Baru</span></h5>
				</div>

				<div class="panel-body">
					<form action="{{ url(Request::segment(1).'/'.Request::segment(2)) }}" method="POST" enctype="multipart/form-data" class="form-horizontal" id="form-transaksi">
                    @csrf
					<div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-lg-3 control-label">No. Pembayaran :</label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" placeholder="No. Pembayaran" readonly name="kode_pembayaran" value="{{ $pembayaran->kode_pembayaran }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Tanggal :</label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" disabled placeholder="Tanggal" value="{{Carbon\Carbon::createFromFormat('Y-m-d', $pembayaran->tgl_pembayaran)->format('d/m/Y') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Supplier :</label>
                            <div class="col-lg-6">
                                <select disabled  id="supplier" class="form-control" name="supplier">
                                    <option value="" selected>{{ $pembayaran->supplier->nama_supplier }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
				</div>

                <div class="table-responsive">
                    <table class="table table-sm table-bordered" id="table-transaksi">
                        <thead>
                            <tr class="bg-success">
                                <th class="text-center" width="80">Tipe</th>
                                <th class="text-center" width="150">No. Ref</th>
                                <th class="text-center" width="100">Jumlah Bayar</th>
                                <th class="text-center" width="220">Keterangan</th>
                                <th class="text-center" width="10">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pembayaran->det_pembayaran as $isi)
                            <tr id="empty-rows">
                                <td class="text-center">{{ preg_replace('/([a-z])([A-Z])/s','$1 $2', str_replace('App\\', '', $isi->pembayaran_type)) }}</td>
                                <td>{{ $isi->no_ref }}</td>
                                <td class="text-right">{{ number_format($isi->jumlah_bayar, 0) }}</td>
                                <td>{{ $isi->keterangan }}</td>
                                <td class="text-center">
                                    <a id="remove" href="#"><i class="icon-trash text-danger"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="panel-body" style="margin-bottom: -50px;">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-7">
                                <div class="content-group">
                                    <div class="form-group">
                                        <div class="col-lg-6">
                                            <textarea disabled name="keterangan" id="keterangan" cols="10" rows="3" class="form-control" placeholder="Masukan Keterangan Jika Ada">{{ $pembayaran->keterangan }}</textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-sm-5">
                                <div class="content-group">
                                    <div class="table-responsive no-border">
                                        <table class="table table-xxs table-framed">
                                            <tbody>
                                                <tr>
                                                    <th>Total Hutang :</th>
                                                    <td class="text-right"><input type="text" name="total_hutang" id="total_hutang" class="form-control input-xs text-right text-bold" readonly value="{{ number_format($pembayaran->total_hutang, 0) }}"></td>
                                                </tr>
                                                <tr>
                                                    <th>Total Pembayaran :</th>
                                                    <td class="text-right">
                                                        <div class="input-group">
                                                            <input type="text" value="{{ number_format($pembayaran->total_pembayaran, 0) }}" id="total_pembayaran" name="total_pembayaran" class="form-control input-xs text-right text-bold" readonly>
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
							<a href="{{ url('keuangan/pembayaran') }}" class="btn btn-default"><b><i class="icon-circle-left2"></i></b> Kembali</a>
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
<script src="{{ asset('back/global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('back/assets/js/datatable.custom.js') }}"></script>
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
    let pembayaran =@json($pembayaran);

    $(document).ready(function(){

        console.log(pembayaran);


    });

    </script>
@endsection
