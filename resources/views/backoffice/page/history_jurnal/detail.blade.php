@extends('backoffice.layout.backofficelayout')
@section('title'){{ env("SET_TOKO", "NOT SET") }} | Bill Of Material - Buat Bill Of Material @endsection
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
			<a href="{{ url('akutansi/jurnal') }}"><h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Data Jurnal Umum</span></h4></a>
		<a class="heading-elements-toggle"><i class="icon-more"></i></a></div>
	</div>

	<div class="breadcrumb-line"><a class="breadcrumb-elements-toggle"><i class="icon-menu-open"></i></a>
		<ul class="breadcrumb">
			<li><a href="index.html"><i class="icon-grid position-left"></i> Akutansi</a></li>
			<li class="active">Detail Jurnal Umum</li>
		</ul>
	</div>
</div>

<div class="content">
	<div class="row">
		<div class="col-sm-12">
            

			<div class="panel panel-flat border-top-xlg border-top-green-600" id="panel-buat-item">
                

				<div class="panel-heading">
					<h5 class="panel-title"><span class="text-semibold"><i class="icon-file-eye"></i> Detail Jurnal Umum</span></h5>
				</div>
				
				<div class="panel-body">
					<form action="{{ url(Request::segment(1).'/'.Request::segment(2)) }}" method="POST" enctype="multipart/form-data" class="form-horizontal" id="form-transaksi">
                        @csrf
					<div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Kode Jurnal :</label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" placeholder="No. B.O.M" readonly name="no_bom" value="{{ $history->kode_journal }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Tanggal :</label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" readonly value="{{ date('d/m/Y', strtotime($history->tgl_set)) }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Tipe :</label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" readonly value='{{ preg_replace('/([a-z])([A-Z])/s','$1 $2', str_replace('App\\', '', $history->historyjurnalable_type))  }}'>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" class="readonly" {{$history->autogen == '1' ? 'checked' : ''}} readonly onclick="event.preventDefault()">
                                    Auto Generate
                                </label>
                            </div>
                        </div>
                    </div>
				</div>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered" id="table-transaksi">
                        <thead>
                            <tr class="bg-success">
                                <th class="text-center" width="60">No.</th>
                                <th class="text-center" width="150">No. Akun</th>
                                <th class="text-center">Nama Akun</th>
                                <th class="text-center" width="250">Debit</th>
                                <th class="text-center" width="250">Kredit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($history->det_history_jurnal as $isi)
                            <tr>
                                <td class="text-center text-bold">{{ $loop->index+1 }}.</td>
                                <td class="text-center text-bold">{{ $isi->akun->kode_akun }}</td>
                                <td class="text-left text-bold">{{ $isi->akun->nama_akun }}</td>
                                <td class="text-right text-bold">Rp. {{ number_format($isi->nominal_debit, 0) }}</td>
                                <td class="text-right text-bold">Rp. {{ number_format($isi->nominal_kredit, 0) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="panel-body" style="margin-bottom: -50px;">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="content-group">
                                    <div class="form-group">
                                        <div class="col-lg-6">
                                            <textarea name="" id="" cols="10" rows="3" class="form-control" readonly>{{ $history->keterangan }}</textarea>
                                        </div>
                                    </div>
                                
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="content-group">
                                    <div class="table-responsive no-border">
                                        <table class="table table-xxs table-framed">
                                            <tbody>
                                                <tr>
                                                    <th>Total Debit :</th>
                                                    <td class="text-right"><input type="text" name="total_akhir" id="total_akhir" class="form-control input-xs text-right text-bold" readonly value="Rp. {{ number_format($history->det_history_jurnal->sum('nominal_debit'), 0) }}"></td>
                                                </tr>
                                                <tr>
                                                    <th>Total Kredit :</th>
                                                    <td class="text-right"><input type="text" name="total_akhir" id="total_akhir" class="form-control input-xs text-right text-bold" readonly value="Rp. {{ number_format($history->det_history_jurnal->sum('nominal_kredit'), 0) }}"></td>
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
							<a href="{{ url('akutansi/jurnal') }}" class="btn btn-danger"><b><i class="icon-circle-left2"></i></b> Kembali</a>
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
<script src="{{ asset('back/global_assets/js/plugins/forms/styling/switchery.min.js') }}"></script>
<script src="{{ asset('back/jquery.number.js') }}"></script>
<script src="{{ asset('back/global_assets/js/plugins/jquery-mask/dist/jquery.mask.js') }}"></script>
<script src="{{ asset('back/assets/js/tambahan.js') }}"></script>
<script>

window.onload = function () {
	show_loading('#panel-buat-item');
	hide_loading('#panel-buat-item', 500);
}


$(document).ready(function() {
    


    



});

</script>
@endsection