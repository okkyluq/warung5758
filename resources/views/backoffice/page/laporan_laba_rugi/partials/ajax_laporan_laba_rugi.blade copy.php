<div class="panel-body">
    <h4 class="no-margin text-bold text-center">WARUNG 5758</h4>
    <h5 class="no-margin text-bold text-center text-primary">LAPORAN LABA RUGI</h5>
    <h6 class="no-margin text-bold text-center text-danger" >Tanggal : {{ $tgl_awal.' s/d '.$tgl_akhir }}</h6>
</div>
<table class="table datatable-basic table-xs table-bordered table-framed">
    <thead class="bg-slate-800 text-semibold" id="datexx">
        <tr class="bg-slate-800 text-semibold">
            <th class="text-center">KETERANGAN</th>
            <th width="200" class="text-center">NOMINAL</th>
        </tr>
    </thead>
    <tbody>
        @php
            $total_debit = 0;
            $total_kredit = 0;
        @endphp
        @foreach ($akun as $isi)
        <tr>
            <td colspan="3" class="bg-blue-300">
                {{ strtoupper($isi->nama_kategori )}}
            </td>
        </tr>
            @foreach ($isi->akun as $isi2)
            <tr>
                <td>{{$isi2->nama_akun}}</td>
                <td class="text-right">Rp. {{ number_format(abs($isi2->debit - $isi2->kredit), 0) }}</td>
            </tr>
            @endforeach
        <tr>
            <td colspan="1" class="text-bold text-center">{{ strtoupper('TOTAL '.$isi->nama_kategori) }}</td>
            <td colspan="1" class="text-bold text-right">Rp. {{ number_format(abs($isi->akun->sum('debit') - $isi->akun->sum('kredit')), 0) }}</td>
            @php
                $total_debit += abs($isi->akun->sum('debit'));
                $total_kredit += abs($isi->akun->sum('kredit'));
            @endphp
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="bg-slate-800">
            <td colspan="1" class="text-bold text-center">GRAND TOTAL LABA BERSIH</td>
            <td colspan="1" class="text-bold text-right">Rp. {{ number_format(abs($total_debit - $total_kredit), 0) }}</td>
        </tr>
    </tfoot>
</table>
</div>

