@if (!empty($item))
    <table class="table table-striped table-xs table-bordered">
        <thead>
            <tr>
                <th class="text-center" width="100">Tanggal</th>
                <th class="text-center">Keterangan</th>
                <th class="text-center">Mitra Bisnis</th>
                <th class="text-center">Satuan</th>
                <th class="text-center">Saldo Awal</th>
                <th class="text-center">Debet</th>
                <th class="text-center">Kredit</th>
                <th class="text-center">Saldo</th>
            </tr>
        </thead>
        <tbody>
            @if (count($item))
            @foreach ($item as $list)
            <tr>
                <td class="text-center">{{ date('d/m/Y', strtotime($list->tgl_transaksi)) }}</td>
                <td class="text-left">{{ $list->keterangan }}</td>
                <td class="text-left">{{ $list->mitra_bisnis }}</td>
                <td class="text-right" width="100">{{ $list->satuan_item->first()->satuan->satuan }}</td>
                <td class="text-right" width="100">{{ $list->stock_awal }}</td>
                <td class="text-right" width="100">{{ $list->debit }}</td>
                <td class="text-right" width="100">{{ $list->kredit }}</td>
                <td class="text-right" width="100">{{ $list->sisa_stock }}</td>
            </tr>
            @endforeach
            @else
            <tr>
                <td class="text-center" colspan="8">Data Tidak Ditemukan</td>
            </tr>
            @endif
        </tbody>
    </table>
@endif