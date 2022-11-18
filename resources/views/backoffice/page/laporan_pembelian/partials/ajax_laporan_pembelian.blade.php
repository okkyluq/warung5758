<div class="panel-body">
    <h4 class="no-margin text-bold text-center">WARUNG 5758</h4>
    <h5 class="no-margin text-bold text-center text-primary">LAPORAN TRANSAKSI PEMBELIAN</h5>
    <h6 class="no-margin text-bold text-center text-danger">Tanggal : {{ date('d/m/Y', strtotime($tgl_awal)) }} s/d {{ date('d/m/Y', strtotime($tgl_akhir)) }}</h6>
    <a id="print-me" href="{{ url('laporan-toko/pdf-pembelian') }}" target="_blank" class="pull-right btn bg-teal-400 btn-labeled"><b><i class="icon-file-pdf"></i></b> Export Ke Pdf</a>
</div>
<table class="table datatable-basic table-xs table-bordered table-framed">
    <thead class="bg-slate-800 text-semibold">
        <tr class="bg-slate-800 text-semibold">
            <th width="30" class="text-center">Tanggal</th>
            <th width="120" class="text-center">Kode. Trans</th>
            <th class="text-center">Supplier</th>
            <th width="30" class="text-center">Termin</th>
            <th width="100" class="text-center">Kas</th>
            <th width="150" class="text-center">Total</th>
            <th width="130" class="text-center">Status</th>
            <th width="130" class="text-center">Sisa Pembayaran</th>
            <th width="30" class="text-center">Tgl.J.Tempo</th>
        </tr>
    </thead>
    <tbody>
        @if ($transaksi->count() != 0 )
        @foreach ($transaksi as $isi)
        <tr>
            <td class="text-center text-bold">{{ date('d/m/Y', strtotime($isi->tgl_pembelian)) }}</td>
            <td class="text-left text-bold">{{ $isi->no_pembelian }}</td>
            <td class="text-center text-bold">{{ $isi->supplier->nama_supplier }}</td>
            <td class="text-center text-bold">{{ $isi->termin == '1' ? 'CASH' : 'CREDIT' }}</td>
            <td class="text-center text-bold">{{ $isi->kas->nama_kas }}</td>
            <td class="text-right text-bold">Rp. {{ number_format($isi->total, 0) }}</td>
            <td class="text-center text-bold  {{ $isi->history_hutang ? ($isi->history_hutang->status_lunas == '0' ? 'bg-danger' : 'bg-success') : 'bg-success' }}">
                {{ $isi->history_hutang ? ($isi->history_hutang->status_lunas == '0' ? 'BELUM LUNAS' : 'LUNAS') : 'LUNAS' }}
            </td>
            <td class="text-right text-bold">
                Rp. {{ $isi->history_hutang ? number_format($isi->history_hutang->sisa_pembayaran, 0) : '-' }}
            </td>
            <td class="text-center text-bold">{{ $isi->termin == '1' ? '-' : date('d/m/Y', strtotime($isi->tgl_jatuh_tempo))  }}</td>
        </tr>
        @endforeach
        @else
        <tr>
            <td colspan="9" class="text-center text-bold">Data Tidak Ditemukan !</td>
        </tr>
        @endif
    </tbody>
    <tfoot>
        <tr class="bg-info">
            <td colspan="7" class="text-center text-bold">Total Pembelian</td>
            <td colspan="2" class="text-bold text-center">Rp. {{ number_format($transaksi->sum('total'), 0) }}</td>
        </tr>
    </tfoot>
</table>
</div>