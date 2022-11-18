@extends('backoffice.layout.backofficelayout')
@section('title'){{ env("SET_TOKO", "NOT SET") }} | Dashboard @endsection
@section('content')
<div class="page-header page-header-default">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-home2 position-left"></i> <span class="text-semibold">Home</span> - Dashboard</h4>
		<a class="heading-elements-toggle"><i class="icon-more"></i></a></div>
	</div>

	<div class="breadcrumb-line"><a class="breadcrumb-elements-toggle"><i class="icon-menu-open"></i></a>
		<ul class="breadcrumb">
			<li><a href="index.html"><i class="icon-home2 position-left"></i> Home</a></li>
			<li class="active">Dashboard</li>
		</ul>

	</div>
</div>

<div class="content">
	<div class="row">
		<div class="col-lg-3">
			<div class="panel bg-success-400">
				<div class="panel-body">
					<h3 class="no-margin">Rp. {{ number_format($omzet, 0) }}</h3>
					Total Omzet
				</div>
				<div id="today-revenue"><svg width="253.375" height="50"><g transform="translate(0,0)" width="253.375"><defs><clipPath id="clip-line-small"><rect class="clip" width="253.375" height="50"></rect></clipPath></defs><path d="M20,8.46153846153846L55.5625,25.76923076923077L91.125,5L126.6875,15.384615384615383L162.24999999999997,5L197.81250000000003,36.15384615384615L233.375,8.46153846153846" clip-path="url(#clip-line-small)" class="d3-line d3-line-medium" style="stroke: rgb(255, 255, 255);"></path><g><line class="d3-line-guides" x1="20" y1="50" x2="20" y2="8.46153846153846" style="stroke: rgba(255, 255, 255, 0.3); stroke-dasharray: 4, 2; shape-rendering: crispedges;"></line><line class="d3-line-guides" x1="55.5625" y1="50" x2="55.5625" y2="25.76923076923077" style="stroke: rgba(255, 255, 255, 0.3); stroke-dasharray: 4, 2; shape-rendering: crispedges;"></line><line class="d3-line-guides" x1="91.125" y1="50" x2="91.125" y2="5" style="stroke: rgba(255, 255, 255, 0.3); stroke-dasharray: 4, 2; shape-rendering: crispedges;"></line><line class="d3-line-guides" x1="126.6875" y1="50" x2="126.6875" y2="15.384615384615383" style="stroke: rgba(255, 255, 255, 0.3); stroke-dasharray: 4, 2; shape-rendering: crispedges;"></line><line class="d3-line-guides" x1="162.24999999999997" y1="50" x2="162.24999999999997" y2="5" style="stroke: rgba(255, 255, 255, 0.3); stroke-dasharray: 4, 2; shape-rendering: crispedges;"></line><line class="d3-line-guides" x1="197.81250000000003" y1="50" x2="197.81250000000003" y2="36.15384615384615" style="stroke: rgba(255, 255, 255, 0.3); stroke-dasharray: 4, 2; shape-rendering: crispedges;"></line><line class="d3-line-guides" x1="233.375" y1="50" x2="233.375" y2="8.46153846153846" style="stroke: rgba(255, 255, 255, 0.3); stroke-dasharray: 4, 2; shape-rendering: crispedges;"></line></g><g><circle class="d3-line-circle d3-line-circle-medium" cx="20" cy="8.46153846153846" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle><circle class="d3-line-circle d3-line-circle-medium" cx="55.5625" cy="25.76923076923077" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle><circle class="d3-line-circle d3-line-circle-medium" cx="91.125" cy="5" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle><circle class="d3-line-circle d3-line-circle-medium" cx="126.6875" cy="15.384615384615383" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle><circle class="d3-line-circle d3-line-circle-medium" cx="162.24999999999997" cy="5" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle><circle class="d3-line-circle d3-line-circle-medium" cx="197.81250000000003" cy="36.15384615384615" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle><circle class="d3-line-circle d3-line-circle-medium" cx="233.375" cy="8.46153846153846" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle></g></g></svg></div>
			</div>
		</div>

		<div class="col-lg-3">
			<div class="panel bg-danger-400">
				<div class="panel-body">
					<h3 class="no-margin">Rp. {{ number_format($saldo_kas, 0) }}</h3>
					Total Kas
				</div>
				<div id="today-revenue"><svg width="253.375" height="50"><g transform="translate(0,0)" width="253.375"><defs><clipPath id="clip-line-small"><rect class="clip" width="253.375" height="50"></rect></clipPath></defs><path d="M20,8.46153846153846L55.5625,25.76923076923077L91.125,5L126.6875,15.384615384615383L162.24999999999997,5L197.81250000000003,36.15384615384615L233.375,8.46153846153846" clip-path="url(#clip-line-small)" class="d3-line d3-line-medium" style="stroke: rgb(255, 255, 255);"></path><g><line class="d3-line-guides" x1="20" y1="50" x2="20" y2="8.46153846153846" style="stroke: rgba(255, 255, 255, 0.3); stroke-dasharray: 4, 2; shape-rendering: crispedges;"></line><line class="d3-line-guides" x1="55.5625" y1="50" x2="55.5625" y2="25.76923076923077" style="stroke: rgba(255, 255, 255, 0.3); stroke-dasharray: 4, 2; shape-rendering: crispedges;"></line><line class="d3-line-guides" x1="91.125" y1="50" x2="91.125" y2="5" style="stroke: rgba(255, 255, 255, 0.3); stroke-dasharray: 4, 2; shape-rendering: crispedges;"></line><line class="d3-line-guides" x1="126.6875" y1="50" x2="126.6875" y2="15.384615384615383" style="stroke: rgba(255, 255, 255, 0.3); stroke-dasharray: 4, 2; shape-rendering: crispedges;"></line><line class="d3-line-guides" x1="162.24999999999997" y1="50" x2="162.24999999999997" y2="5" style="stroke: rgba(255, 255, 255, 0.3); stroke-dasharray: 4, 2; shape-rendering: crispedges;"></line><line class="d3-line-guides" x1="197.81250000000003" y1="50" x2="197.81250000000003" y2="36.15384615384615" style="stroke: rgba(255, 255, 255, 0.3); stroke-dasharray: 4, 2; shape-rendering: crispedges;"></line><line class="d3-line-guides" x1="233.375" y1="50" x2="233.375" y2="8.46153846153846" style="stroke: rgba(255, 255, 255, 0.3); stroke-dasharray: 4, 2; shape-rendering: crispedges;"></line></g><g><circle class="d3-line-circle d3-line-circle-medium" cx="20" cy="8.46153846153846" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle><circle class="d3-line-circle d3-line-circle-medium" cx="55.5625" cy="25.76923076923077" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle><circle class="d3-line-circle d3-line-circle-medium" cx="91.125" cy="5" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle><circle class="d3-line-circle d3-line-circle-medium" cx="126.6875" cy="15.384615384615383" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle><circle class="d3-line-circle d3-line-circle-medium" cx="162.24999999999997" cy="5" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle><circle class="d3-line-circle d3-line-circle-medium" cx="197.81250000000003" cy="36.15384615384615" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle><circle class="d3-line-circle d3-line-circle-medium" cx="233.375" cy="8.46153846153846" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle></g></g></svg></div>
			</div>
		</div>

		<div class="col-lg-3">
			<div class="panel bg-primary-400">
				<div class="panel-body">
					<h3 class="no-margin">Rp. {{ number_format($piutang, 0) }}</h3>
					Total Piutang
				</div>
				<div id="today-revenue"><svg width="253.375" height="50"><g transform="translate(0,0)" width="253.375"><defs><clipPath id="clip-line-small"><rect class="clip" width="253.375" height="50"></rect></clipPath></defs><path d="M20,8.46153846153846L55.5625,25.76923076923077L91.125,5L126.6875,15.384615384615383L162.24999999999997,5L197.81250000000003,36.15384615384615L233.375,8.46153846153846" clip-path="url(#clip-line-small)" class="d3-line d3-line-medium" style="stroke: rgb(255, 255, 255);"></path><g><line class="d3-line-guides" x1="20" y1="50" x2="20" y2="8.46153846153846" style="stroke: rgba(255, 255, 255, 0.3); stroke-dasharray: 4, 2; shape-rendering: crispedges;"></line><line class="d3-line-guides" x1="55.5625" y1="50" x2="55.5625" y2="25.76923076923077" style="stroke: rgba(255, 255, 255, 0.3); stroke-dasharray: 4, 2; shape-rendering: crispedges;"></line><line class="d3-line-guides" x1="91.125" y1="50" x2="91.125" y2="5" style="stroke: rgba(255, 255, 255, 0.3); stroke-dasharray: 4, 2; shape-rendering: crispedges;"></line><line class="d3-line-guides" x1="126.6875" y1="50" x2="126.6875" y2="15.384615384615383" style="stroke: rgba(255, 255, 255, 0.3); stroke-dasharray: 4, 2; shape-rendering: crispedges;"></line><line class="d3-line-guides" x1="162.24999999999997" y1="50" x2="162.24999999999997" y2="5" style="stroke: rgba(255, 255, 255, 0.3); stroke-dasharray: 4, 2; shape-rendering: crispedges;"></line><line class="d3-line-guides" x1="197.81250000000003" y1="50" x2="197.81250000000003" y2="36.15384615384615" style="stroke: rgba(255, 255, 255, 0.3); stroke-dasharray: 4, 2; shape-rendering: crispedges;"></line><line class="d3-line-guides" x1="233.375" y1="50" x2="233.375" y2="8.46153846153846" style="stroke: rgba(255, 255, 255, 0.3); stroke-dasharray: 4, 2; shape-rendering: crispedges;"></line></g><g><circle class="d3-line-circle d3-line-circle-medium" cx="20" cy="8.46153846153846" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle><circle class="d3-line-circle d3-line-circle-medium" cx="55.5625" cy="25.76923076923077" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle><circle class="d3-line-circle d3-line-circle-medium" cx="91.125" cy="5" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle><circle class="d3-line-circle d3-line-circle-medium" cx="126.6875" cy="15.384615384615383" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle><circle class="d3-line-circle d3-line-circle-medium" cx="162.24999999999997" cy="5" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle><circle class="d3-line-circle d3-line-circle-medium" cx="197.81250000000003" cy="36.15384615384615" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle><circle class="d3-line-circle d3-line-circle-medium" cx="233.375" cy="8.46153846153846" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle></g></g></svg></div>
			</div>
		</div>

		<div class="col-lg-3">
			<div class="panel bg-warning-400">
				<div class="panel-body">
					<h3 class="no-margin">Rp. {{ number_format($hutang, 0) }}</h3>
					Total Hutang
				</div>
				<div id="today-revenue"><svg width="253.375" height="50"><g transform="translate(0,0)" width="253.375"><defs><clipPath id="clip-line-small"><rect class="clip" width="253.375" height="50"></rect></clipPath></defs><path d="M20,8.46153846153846L55.5625,25.76923076923077L91.125,5L126.6875,15.384615384615383L162.24999999999997,5L197.81250000000003,36.15384615384615L233.375,8.46153846153846" clip-path="url(#clip-line-small)" class="d3-line d3-line-medium" style="stroke: rgb(255, 255, 255);"></path><g><line class="d3-line-guides" x1="20" y1="50" x2="20" y2="8.46153846153846" style="stroke: rgba(255, 255, 255, 0.3); stroke-dasharray: 4, 2; shape-rendering: crispedges;"></line><line class="d3-line-guides" x1="55.5625" y1="50" x2="55.5625" y2="25.76923076923077" style="stroke: rgba(255, 255, 255, 0.3); stroke-dasharray: 4, 2; shape-rendering: crispedges;"></line><line class="d3-line-guides" x1="91.125" y1="50" x2="91.125" y2="5" style="stroke: rgba(255, 255, 255, 0.3); stroke-dasharray: 4, 2; shape-rendering: crispedges;"></line><line class="d3-line-guides" x1="126.6875" y1="50" x2="126.6875" y2="15.384615384615383" style="stroke: rgba(255, 255, 255, 0.3); stroke-dasharray: 4, 2; shape-rendering: crispedges;"></line><line class="d3-line-guides" x1="162.24999999999997" y1="50" x2="162.24999999999997" y2="5" style="stroke: rgba(255, 255, 255, 0.3); stroke-dasharray: 4, 2; shape-rendering: crispedges;"></line><line class="d3-line-guides" x1="197.81250000000003" y1="50" x2="197.81250000000003" y2="36.15384615384615" style="stroke: rgba(255, 255, 255, 0.3); stroke-dasharray: 4, 2; shape-rendering: crispedges;"></line><line class="d3-line-guides" x1="233.375" y1="50" x2="233.375" y2="8.46153846153846" style="stroke: rgba(255, 255, 255, 0.3); stroke-dasharray: 4, 2; shape-rendering: crispedges;"></line></g><g><circle class="d3-line-circle d3-line-circle-medium" cx="20" cy="8.46153846153846" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle><circle class="d3-line-circle d3-line-circle-medium" cx="55.5625" cy="25.76923076923077" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle><circle class="d3-line-circle d3-line-circle-medium" cx="91.125" cy="5" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle><circle class="d3-line-circle d3-line-circle-medium" cx="126.6875" cy="15.384615384615383" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle><circle class="d3-line-circle d3-line-circle-medium" cx="162.24999999999997" cy="5" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle><circle class="d3-line-circle d3-line-circle-medium" cx="197.81250000000003" cy="36.15384615384615" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle><circle class="d3-line-circle d3-line-circle-medium" cx="233.375" cy="8.46153846153846" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle></g></g></svg></div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-6">
			<div class="panel panel-primary border-grey" id="panel-omset-penjualan">
				<div class="panel-heading">
					<h6 class="panel-title">Omset Penjualan Bulan Ini<a class="heading-elements-toggle"><i class="icon-more"></i></a></h6>
					<div class="heading-elements">
						<ul class="icons-list">
							<li><a id="btn-modal-omzet-penjualan"><i class="icon-cog3"></i></a></li>
							<li><a id="btn-reload-omzet-penjualan"><i class="icon-reset"></i></a></li>
						</ul>
					</div>
				</div>

				<div class="panel-body">
					<canvas id="eChartOmsetPenjualan" style="height: 300px;"></canvas>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="panel panel-success panel-bordered" id="panel-omset-bulan">
				<div class="panel-heading">
					<h6 class="panel-title">Omzet Bulanan Tahun Ini<a class="heading-elements-toggle"><i class="icon-more"></i></a></h6>
					<div class="heading-elements">
						<ul class="icons-list">
							<li><a id="btn-modal-omzet-bulan"><i class="icon-cog3"></i></a></li>
							<li><a id="btn-reload-omzet-bulan"><i class="icon-reset"></i></a></li>
						</ul>
					</div>
				</div>

				<div class="panel-body">
					<canvas id="bar" style="height: 300px;"></canvas>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-danger panel-bordered" id="panel-omset-tgl">
				<div class="panel-heading">
					<h6 class="panel-title">Grafik Penjualan Per Hari Bulan Ini<a class="heading-elements-toggle"><i class="icon-more"></i></a></h6>
					<div class="heading-elements">
						<ul class="icons-list">
							<li><a id="btn-modal-omzet-tgl"><i class="icon-cog3"></i></a></li>
							<li><a id="btn-reload-omzet-tgl"><i class="icon-reset"></i></a></li>
						</ul>
					</div>
				</div>

				<div class="panel-body">
					<canvas id="hari" style="height: 300px;"></canvas>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="modal_omzet_penjualan" class="modal fade" tabindex="-1" style="display: none;">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<h5 class="modal-title"><i class="icon-cog3"></i> Opsi Bulan Omset Penjualan</h5>
			</div>

			<form action="" class="form-horizontal" id="form_omzet_penjualan">
			<div class="modal-body">
					<div class="form-group">
						<label class="col-lg-3 control-label text-right">Bulan:</label>
						<div class="col-lg-9">
							<select name="bulan" id="bulan" class="form-control">
								<option value="" selected disabled>Pilih Bulan</option>
								<option value="1">Januari</option>
								<option value="2">Februari</option>
								<option value="3">Maret</option>
								<option value="4">April</option>
								<option value="5">Mei</option>
								<option value="6">Juni</option>
								<option value="7">Juli</option>
								<option value="8">Agustus</option>
								<option value="9">September</option>
								<option value="10">Oktober</option>
								<option value="11">November</option>
								<option value="12">Desember</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label text-right">Tahun:</label>
						<div class="col-lg-9">
							<select name="tahun" id="tahun" class="form-control">
								<option value="" selected disabled>Pilih Tahun</option>
								@foreach (range(2021, 2021+5) as $item)
								<option value="{{$item}}">{{$item}}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal">Tutup</button>
					<button type="submit" class="btn btn-primary">Terapkan</button>
				</div>
			</form>
		</div>
	</div>
</div>


<div id="modal_omzet_bulan" class="modal fade" tabindex="-1" style="display: none;">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<h5 class="modal-title"><i class="icon-cog3"></i> Opsi Omset Bulanan</h5>
			</div>

			<form action="" class="form-horizontal" id="form_omzet_bulan">
			<div class="modal-body">
					<div class="form-group">
						<label class="col-lg-3 control-label text-right">Tahun:</label>
						<div class="col-lg-9">
							<select name="tahun" id="tahun" class="form-control">
								<option value="" selected disabled>Pilih Tahun</option>
								@foreach (range(2021, 2021+5) as $item)
								<option value="{{$item}}">{{$item}}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal">Tutup</button>
					<button type="submit" class="btn btn-primary">Terapkan</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div id="modal_omzet_tgl" class="modal fade" tabindex="-1" style="display: none;">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<h5 class="modal-title"><i class="icon-cog3"></i> Opsi Omset Per Tanggal</h5>
			</div>

			<form action="" class="form-horizontal" id="form_omzet_tgl">
			<div class="modal-body">
					<div class="form-group">
						<label class="col-lg-3 control-label text-right">Bulan:</label>
						<div class="col-lg-9">
							<select name="bulan" id="bulan" class="form-control" required>
								<option value="" selected disabled>Pilih Bulan</option>
								<option value="1">Januari</option>
								<option value="2">Februari</option>
								<option value="3">Maret</option>
								<option value="4">April</option>
								<option value="5">Mei</option>
								<option value="6">Juni</option>
								<option value="7">Juli</option>
								<option value="8">Agustus</option>
								<option value="9">September</option>
								<option value="10">Oktober</option>
								<option value="11">November</option>
								<option value="12">Desember</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label text-right">Tahun:</label>
						<div class="col-lg-9">
							<select name="tahun" id="tahun" class="form-control" required>
								<option value="" selected disabled>Pilih Tahun</option>
								@foreach (range(2021, 2021+5) as $item)
								<option value="{{$item}}">{{$item}}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal">Tutup</button>
					<button type="submit" class="btn btn-primary">Terapkan</button>
				</div>
			</form>
		</div>
	</div>
</div>

@endsection
@section('script')
<script src="{{ asset('back/assets/js/tambahan.js') }}"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.0/dist/chart.min.js"></script> --}}
<script src="{{ asset('back/chart-js/chart.js') }}"></script>
<script type="text/javascript">

$(document).ready(function(){
	let this_month_chart_penjualan = "{{ date('m') }}";
	let this_year_chart_penjualan  = "{{ date('Y') }}";
	let this_month_chart_bulan = "{{ date('m') }}";
	let this_year_chart_bulan  = "{{ date('Y') }}";
	let this_month_chart_tgl = "{{ date('m') }}";
	let this_year_chart_tgl  = "{{ date('Y') }}";

	let chartOmsetPenjualan = new Chart( $('#eChartOmsetPenjualan'), {
		type: 'pie',
		data: {
			labels: ['Cash', 'Kredit'],
			datasets: [{
				label: 'My First dataset',
				backgroundColor: ["#66bb6a", "#ef5350"],
				borderColor: ["#66bb6a", "#ef5350"],
				data: [0, 0],
			}]
		},
		options: {
			maintainAspectRatio: false,
		}
	});

	$.chartOmsetPenjualan = function(month, year, panel_element, link){
		$.ajax({
			url: link,
			type: "POST",
			data : {bulan: month, tahun: year},
			beforeSend: function(jqXHR, settings){
				show_loading(panel_element);
			},
			success:function(data, status, xhr){
				if(data.status == 'sukses'){
					chartOmsetPenjualan.data.datasets[0].data = data.data;
					chartOmsetPenjualan.update();
				}
				hide_loading(panel_element);
			},
			fail: function(jqXhr, textStatus, errorMessage){
				hide_loading(panel_element);
				console.log(errorMessage);
			}
		});
	}

	$('#btn-reload-omzet-penjualan').on('click', function(){
		$.chartOmsetPenjualan(this_month_chart_penjualan, this_year_chart_penjualan, '#panel-omset-penjualan', "{{ url('chart-omset-penjualan') }}");
	});

	$('#btn-modal-omzet-penjualan').on('click', function(e){
		e.preventDefault();
		$('#modal_omzet_penjualan').modal('show');
	});

	$('#form_omzet_penjualan').submit(function(e){
		e.preventDefault();
		let bulan = $(this).find('select#bulan').val();
		let tahun = $(this).find('select#tahun').val();
		let bulan_text = $(this).find('select#bulan option:selected').text();
		let tahun_text = $(this).find('select#tahun option:selected').text();
		$('#panel-omset-penjualan').find('h6.panel-title').text(`Omset Penjualan Bulan ${bulan_text} Tahun ${tahun_text}`);
		this_month_chart_penjualan = bulan;
		this_year_chart_penjualan = tahun;
		$.chartOmsetPenjualan(bulan, tahun, '#panel-omset-penjualan', "{{ url('chart-omset-penjualan') }}");
		$('#modal_omzet_penjualan').modal('hide');
	});

	$.chartOmsetPenjualan(this_month_chart_penjualan, this_year_chart_penjualan, '#panel-omset-penjualan', "{{ url('chart-omset-penjualan') }}");


	let chartOmsetBulan = new Chart( $('#bar'), {
				type: 'bar',
				data: {
					labels: [],
					datasets: [{
						label: 'Penjualan Berdasarkan Bulan',
						backgroundColor: ["#ff7043"],
						borderColor: ["#ff7043"],
						data: [],
					}]
				},
				options: {
					maintainAspectRatio: false,
				}
			});

	$.chartOmsetBulan = function(year, panel_element, link){
		$.ajax({
			url: link,
			type: "POST",
			data : {tahun: year},
			beforeSend: function(jqXHR, settings){
				show_loading(panel_element);
			},
			success:function(data, status, xhr){
				if(data.status == 'sukses'){
					let label = data.data.map((value, index) => {
						return value.month
					});

					let sum = data.data.map((value, index) => {
						return value.sum
					});

					chartOmsetBulan.data.labels = label;
					chartOmsetBulan.data.datasets[0].data = sum;
					chartOmsetBulan.update();
				}
				hide_loading(panel_element);
			},
			fail: function(jqXhr, textStatus, errorMessage){
				hide_loading(panel_element);
				console.log(errorMessage);
			}
		});
	}

	$('#btn-reload-omzet-bulan').on('click', function(){
		$.chartOmsetBulan(this_year_chart_bulan, '#panel-omset-bulan', "{{ url('chart-omset-bulan') }}");
	});

	$('#btn-modal-omzet-bulan').on('click', function(e){
		e.preventDefault();
		$('#modal_omzet_bulan').modal('show');
	});

	$('#form_omzet_bulan').submit(function(e){
		e.preventDefault();
		let tahun = $(this).find('select#tahun').val();
		let tahun_text = $(this).find('select#tahun option:selected').text();
		$('#panel-omset-bulan').find('h6.panel-title').text(`Omset Bulanan Tahun ${tahun_text}`);
		this_year_chart_bulan = tahun;
		$.chartOmsetBulan(tahun, '#panel-omset-bulan', "{{ url('chart-omset-bulan') }}");
		$('#modal_omzet_bulan').modal('hide');
	});

	$.chartOmsetBulan(this_year_chart_bulan, '#panel-omset-bulan', "{{ url('chart-omset-bulan') }}");

	let chartOmsetTgl = new Chart( $('#hari'), {
		type: 'bar',
		data: {
			labels: [],
			datasets: [{
				label: 'Penjualan Berdasarkan Tanggal',
				backgroundColor: ["#42a5f5"],
				borderColor: ["#42a5f5"],
				data: [],
			}]
		},
		options: {
			maintainAspectRatio: false,
			scaleLabel: function(label){
				return '$' + label.value.toString();
			}
		}
	});

	$.chartOmsetTgl = function(month, year, panel_element, link){
		$.ajax({
			url: link,
			type: "POST",
			data : {bulan: month, tahun: year},
			beforeSend: function(jqXHR, settings){
				show_loading(panel_element);
			},
			success:function(data, status, xhr){
				if(data.status == 'sukses'){
					let label = data.data.map((value, index) => {
						return value.date
					});

					let sum = data.data.map((value, index) => {
						return value.sum
					});

					chartOmsetTgl.data.labels = label;
					chartOmsetTgl.data.datasets[0].data = sum;
					chartOmsetTgl.update();
				}
				hide_loading(panel_element);
			},
			fail: function(jqXhr, textStatus, errorMessage){
				hide_loading(panel_element);
				console.log(errorMessage);
			}
		});
	}

	$.chartOmsetTgl(this_month_chart_tgl, this_year_chart_tgl, '#panel-omset-tgl', "{{ url('chart-omset-tgl') }}");

	$('#btn-reload-omzet-tgl').on('click', function(){
		$.chartOmsetTgl(this_month_chart_tgl, this_year_chart_tgl, '#panel-omset-tgl', "{{ url('chart-omset-tgl') }}");
	});

	$('#btn-modal-omzet-tgl').on('click', function(e){
		e.preventDefault();
		$('#modal_omzet_tgl').modal('show');
	});

	$('#form_omzet_tgl').submit(function(e){
		e.preventDefault();
		let bulan = $(this).find('select#bulan').val();
		let tahun = $(this).find('select#tahun').val();
		let bulan_text = $(this).find('select#bulan option:selected').text();
		let tahun_text = $(this).find('select#tahun option:selected').text();
		$('#panel-omset-tgl').find('h6.panel-title').text(`Grafik Penjualan Per Hari Bulan ${bulan_text} Tahun ${tahun_text}`);
		this_month_chart_tgl = bulan;
		this_year_chart_tgl  = tahun;
		$.chartOmsetTgl(bulan, tahun, '#panel-omset-tgl', "{{ url('chart-omset-tgl') }}");
		$('#modal_omzet_tgl').modal('hide');
	});
});




</script>
@endsection
