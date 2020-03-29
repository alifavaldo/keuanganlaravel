<table>
    <thead>
        <tr>
            <th rowspan="2">Tanggal</th>
            <th rowspan="2">Jenis</th>
            <th rowspan="2">Keterangan</th>
            <th rowspan="2">Kategori</th>
            <th colspan="2">Transaksi</th>
        </tr>
        <tr>
            <th>Pemasukan</th>
            <th>Pengeluaran</th>
        </tr>
    </thead>
    <tbody>
        @php
        $total_pemasukan = 0;
        $total_pengeluaran = 0;
        @endphp

        @foreach($laporan as $t)
        <tr>
            <td>{{ date('d-m-Y',strtotime($t->tanggal)) }}</td>
            <td>{{ $t->jenis }}</td>
            <td>{{ $t->keterangan }}</td>
            <td>{{ $t->kategori->kategori }}</td>
            <td>
                @if($t->jenis == "Pemasukan")
                {{ "Rp.".number_format($t->nominal).",-" }}
                @else
                -
                @endif
            </td>
            <td>
                @if($t->jenis == "Pengeluaran")
                {{ "Rp.".number_format($t->nominal).",-" }}
                @else
                -
                @endif
            </td>
        </tr>
        @php
        if($t->jenis == "Pemasukan"){
        $total_pemasukan += $t->nominal;
        }else if($t->jenis == "Pengeluaran"){
            $total_pengeluaran += $t->nominal;
        }
        @endphp

        @endforeach

    </tbody>
    <tfoot>
        <tr>
            <td colspan="4">TOTAL</td>
            <td>{{ "Rp. ".number_format($total_pemasukan )." ,-"}}</td>
            <td>{{ "Rp. ".number_format($total_pengeluaran )." ,-" }}</td>
        </tr>
    </tfoot>
</table>
