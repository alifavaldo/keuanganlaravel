@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    Filter Laporan Keuangan
                </div>
                <div class="card-body">

                    <?php
                    $dari = $_GET['dari'];
                    $sampai = $_GET['sampai'];
                    $kat = $_GET['kategori'];
                    ?>

                    <form method="get" action="{{ url('/laporan/hasil') }}">

                        @csrf

                        <div class="row justify-content-center">
                            <div class="col-md-3">
                                <div class="form-group">

                                    <label>Dari Tanggal</label>
                                    <input type="date" name="dari" class="form-control" value="{{ $dari }}">

                                    @if($errors->has('dari'))
                                    <span class="text-danger">
                                        <strong>{{ $errors->first('dari') }}</strong>
                                    </span>
                                    @endif

                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">

                                    <label>Sampai Tanggal</label>
                                    <input type="date" name="sampai" class="form-control" value="{{ $sampai }}">

                                    @if($errors->has('sampai'))
                                    <span class="text-danger">
                                        <strong>{{ $errors->first('sampai') }}</strong>
                                    </span>
                                    @endif

                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">

                                    <label>Kategori</label>
                                    <select class="form-control" name="kategori">
                                        <option value="semua">- Semua Kategori</option>
                                        @foreach($kategori as $k)
                                        <option <?php if($k->id == $kat){echo "selected='selected'";} ?> value="{{ $k->id }}">{{ $k->kategori }}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>

                            <div class="col-md-2">

                                <input type="submit" class="btn btn-primary mt-4" value="Tampilkan">
                            </div>

                        </div>

                    </form>

                    <hr>

                    <a target="_blank" href="{{ url('/laporan/print?dari='.$dari.'&sampai='.$sampai.'&kategori='.$kat) }}" class="btn btn- btn-secondary">Print</a>
                    <a target="_blank" href="{{ url('/laporan/excel?dari='.$dari.'&sampai='.$sampai.'&kategori='.$kat) }}" class="btn btn- btn-success">Export Excel</a>

                    <table class="table table-bordered mt-4">
                        <thead>
                            <tr>
                                <th class="text-center" rowspan="2" width="11%">Tanggal</th>
                                <th class="text-center" rowspan="2" width="5%">Jenis</th>
                                <th class="text-center" rowspan="2">Keterangan</th>
                                <th class="text-center" rowspan="2">Kategori</th>
                                <th class="text-center" colspan="2">Transaksi</th>
                            </tr>
                            <tr>
                                <th class="text-center">Pemasukan</th>
                                <th class="text-center">Pengeluaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $total_pemasukan = 0;
                            $total_pengeluaran = 0;
                            @endphp
                            @foreach($laporan as $t)
                            <tr>
                                <td class="text-center">{{ date('d-m-Y',strtotime($t->tanggal)) }}</td>
                                <td class="text-center">{{ $t->jenis }}</td>
                                <td class="text-center">{{ $t->keterangan }}</td>
                                <td class="text-center">{{ $t->kategori->kategori }}</td>
                                <td class="text-center">
                                    @if($t->jenis == "Pemasukan")
                                    {{ "Rp.".number_format($t->nominal).",-" }}
                                    @else
                                    -
                                    @endif
                                </td>
                                <td class="text-center">
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
                        <td class="text-center font-weight-bold" colspan="4">TOTAL</td>
                        <td class="text-center bg-success text-white font-weight-bold">{{ "Rp. ".number_format($total_pemasukan )." ,-"}}</td>
                        <td class="text-center bg-danger text-white font-weight-bold">{{ "Rp. ".number_format($total_pengeluaran )." ,-" }}</td>
                    </tr>
                </tfoot>
            </table>

        </div>
    </div>

</div>
</div>
</div>
@endsection
