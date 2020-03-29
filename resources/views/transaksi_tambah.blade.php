@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Tambah Transaksi
                        <a href="{{url('/transaksi')}}" class="btn  btn-primary float-right">Kembali</a>
                    </div>
                    <div class="card-body">
                        <form action="{{url('/transaksi/aksi')}}" method="post">
                            @csrf
                            <div class="form-group">
                            <label for="">Tanggal Transaksi</label>
                            <input type="date" name="tanggal" class="form-control">
                            @if($errors->has('tanggal'))
                                <span class="text-danger">
                                <strong>{{ $errors->first('tanggal') }}</strong>
                                </span>
                            @endif
                            </div>
                            <div class="form-group">
                                <label for="">Jenis</label>
                                <select name="jenis" class="form-control">
                                    <option value="">--Pilih Jenis--</option>
                                    <option value="Pemasukan">Pemasukan</option>
                                    <option value="Pengeluaran">Pengeluaran</option>
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
                                        <option value="{{$k->id}}">{{$k->kategori}}</option>
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
                                <input type="number" name="nominal" class="form-control">
                                @if($errors->has('nominal'))
                                    <span class="text-danger">
                                        <strong>{{ $errors->first('nominal') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="">Keterangan</label>
                               <textarea name="keterangan" class="form-control"></textarea>
                                @if($errors->has('keterangan'))
                                    <span class="text-danger">
                                        <strong>{{ $errors->first('keterangan') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <input type="submit" class="btn btn-primary" value="Simpan">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
