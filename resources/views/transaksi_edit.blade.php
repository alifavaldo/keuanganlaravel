@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Edit Transaksi
                        <a href="{{url('/transaksi')}}" class="btn  btn-primary float-right">Kembali</a>
                    </div>
                    <div class="card-body">
                        <form action="{{url('/transaksi/update/'.$transaksi->id)}}" method="post">
                            @csrf
                            {{ method_field('PUT') }}
                            <div class="form-group">
                            <label for="">Tanggal Transaksi</label>
                            <input type="date" name="tanggal" class="form-control" value="{{$transaksi->tanggal}}">
                            @if($errors->has('tanggal'))
                                <span class="text-danger">
                                <strong>{{ $errors->first('tanggal') }}</strong>
                                </span>
                            @endif
                            </div>
                            <div class="form-group">
                                <label for="">Jenis</label>
                                <select name="jenis" class="form-control" >
                                    <option value="">--Pilih Jenis--</option>
                                    <option <?php if($transaksi->jenis == "Pemasukan"){echo "selected='selected'";} ?> value="Pemasukan">Pemasukan</option>
                                    <option <?php if($transaksi->jenis == "Pengeluaran"){echo "selected='selected'";} ?> value="Pengeluaran">Pengeluaran</option>
                                </select>
                                @if($errors->has('jenis'))
                                    <span class="text-danger">
                                    <strong>{{ $errors->first('jenis') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="">Kategori</label>
                                <select name="kategori" id="" class="form-control">
                                    <option value="">--Pilih Kategori--</option>
                                    @foreach ($kategori as $k)
                                        <option <?php if($k->id == $transaksi->kategori_id){
                                            echo "selected='selected'"; } ?> value="{{$k->id}}">{{$k->kategori}}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('kategori'))
                                    <span class="text-danger">
                                        <strong>{{ $errors->first('kategori') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="">Nominal</label>
                                <input type="number" name="nominal" class="form-control" value="{{ $transaksi->nominal }}">
                                @if($errors->has('nominal'))
                                    <span class="text-danger">
                                        <strong>{{ $errors->first('nominal') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="">Keterangan</label>
                                <textarea name="keterangan" class="form-control">{{$transaksi->keterangan}}</textarea>
                                @if($errors->has('keterangan'))
                                    <span class="text-danger">
                                        <strong>{{ $errors->first('keterangan') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <input type="submit" class="btn btn-primary" value="Update">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
