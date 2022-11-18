@extends('backoffice.layout.backofficelayout')
@section('title'){{ env("SET_TOKO", "NOT SET") }} | Laporan - Saldo Stock @endsection
@section('style')

@endsection
@section('content')
<div class="content">
	<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-flat border-top-xlg border-top-slate-800">
				<div class="panel-heading">
					<h5 class="panel-title"><span class="text-semibold"><i class="icon-file-presentation"></i> Laporan Saldo Stock</span></h5>
				</div>
				<div class="panel-body">
                    <form action="{{ url('laporan/saldo-stock') }}" class="form-horizontal" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="col-lg-1 control-label text-right">Periode:</label>
                            <div class="col-lg-3">
                                <input type="text" class="form-control" placeholder="Eugene Kopyov">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-1 control-label text-right">Tipe Item:</label>
                            <div class="col-lg-3">
                                <input type="text" class="form-control" placeholder="Eugene Kopyov">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-1 col-lg-3">
                                <button type="submit" class="btn btn-success"><i class="icon-file-eye2 position-left"></i> Tampilkan</button>
                            </div>
                        </div>
                    </form>
                </div>
                @if (!empty($item))
                <div class="table-responsive">
                    <table class="table table-striped table-xs">
                        <thead>
                            <tr>
                                <th class="text-center" width="50">No.</th>
                                <th class="text-center">Kode Item</th>
                                <th class="text-center">Nama Item</th>
                                <th class="text-center">Satuan Stock</th>
                                <th class="text-center">Harga Rata-Rata Pembelian</th>
                                <th class="text-center">Harga Rata-Rata Penjualan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($item as $list)
                            <tr>
                                <td class="text-center">{{ $loop->index + 1 }}</td>
                                <td class="text-center">{{ $list->kode_item }}</td>
                                <td class="text-left">{{ $list->nama_item }}</td>
                                <td class="text-center">{{ $list->get_satuan_stock->satuan }}</td>
                                <td class="text-right" width="200">Kopyov</td>
                                <td class="text-right" width="200">Kopyov</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
			</div>
		</div>
	</div>
</div>

@endsection
@section('script')
<script src="{{ asset('back/global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('back/assets/js/datatable.custom.js') }}"></script>
<script>



	
</script>
@endsection

