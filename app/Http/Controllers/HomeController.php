<?php

namespace App\Http\Controllers;

use App\Exports\LaporanExport;
use App\Kategori;
use Illuminate\Http\Request;
use App\Transaksi;
use Maatwebsite\Excel\Facades\Excel;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $tanggal_hari_ini = date('Y-m-d');
        $bulan_ini = date('m');
        $tahun_ini = date('Y');
        $pemasukan_hari_ini  = Transaksi::where('jenis', 'Pemasukan')
            ->whereDate('tanggal', $tanggal_hari_ini)
            ->sum('nominal');
        $pemasukan_bulan_ini  = Transaksi::where('jenis', 'Pemasukan')
            ->whereMonth('tanggal', $bulan_ini)
            ->sum('nominal');
        $pemasukan_tahun_ini  = Transaksi::where('jenis', 'Pemasukan')
            ->whereYear('tanggal', $tahun_ini)
            ->sum('nominal');
        $seluruh_pemasukan = Transaksi::where('jenis', 'Pemasukan')->sum('nominal');
        $pengeluaran_hari_ini  = Transaksi::where('jenis', 'Pengeluaran')
            ->whereDate('tanggal', $tanggal_hari_ini)
            ->sum('nominal');
        $pengeluaran_bulan_ini  = Transaksi::where('jenis', 'Pengeluaran')
            ->whereMonth('tanggal', $bulan_ini)
            ->sum('nominal');
        $pengeluaran_tahun_ini  = Transaksi::where('jenis', 'Pengeluaran')
            ->whereYear('tanggal', $tahun_ini)
            ->sum('nominal');
        $seluruh_pengeluaran = Transaksi::where('jenis', 'Pengeluaran')->sum('nominal');



        return view('home', [
            'pemasukan_hari_ini' => $pemasukan_hari_ini,
            'pemasukan_bulan_ini' => $pemasukan_bulan_ini,
            'pemasukan_tahun_ini' => $pemasukan_tahun_ini,
            'seluruh_pemasukan' => $seluruh_pemasukan,
            'pengeluaran_hari_ini' => $pengeluaran_hari_ini,
            'pengeluaran_bulan_ini' => $pengeluaran_bulan_ini,
            'pengeluaran_tahun_ini' => $pengeluaran_tahun_ini,
            'seluruh_pengeluaran' => $seluruh_pengeluaran
        ]);
    }

    public function kategori()
    {
        $kategori = Kategori::all();
        return view('kategori', ['kategori' => $kategori]);
    }
    public function kategori_tambah()
    {
        return view('kategori_tambah');
    }
    public function kategori_aksi(Request $data)
    {
        $data->validate([
            'kategori' => 'required'
        ]);
        $kategori = $data->kategori;
        Kategori::insert([
            'kategori' => $kategori
        ]);
        return redirect('kategori')->with("sukses", "Kategori Berhasil Di Simpan");
    }
    public function kategori_edit($id)
    {
        $kategori = Kategori::find($id);
        return view('kategori_edit', ['kategori' => $kategori]);
    }
    public function kategori_update($id, Request $data)
    {
        $data->validate([
            'kategori' => 'required'
        ]);
        $nama_kategori = $data->kategori;
        // update kategori
        $kategori = Kategori::find($id);
        $kategori->kategori = $nama_kategori;
        $kategori->save();

        // alihkan halaman
        return redirect('kategori')->with("sukses", "Kategori Berhasil Diupdate");
    }
    public function kategori_hapus($id)
    {
        $kategori = Kategori::find($id);
        $kategori->delete();
        // menghapus transaksi berdasarkan id kategori yang dihapus
        $transaksi = Transaksi::where('kategori_id', $id);
        $transaksi->delete();
        return redirect('kategori')->with("sukses", "Kategori Berhasil dihapus");
    }

    // --------------------------------------------------------------------------------
    // transaksi
    public function transaksi()
    {
        $transaksi = Transaksi::orderBy('id', 'desc')->paginate(6);
        // $transaksi = Transaksi::all();
        return view('transaksi', ['transaksi' => $transaksi]);
    }
    public function transaksi_tambah()
    {
        // mengambil data

        $kategori = Kategori::all();
        return view('transaksi_tambah', ['kategori' => $kategori]);
    }
    public function transaksi_aksi(Request $data)
    {
        // validai
        $data->validate([
            'tanggal' => 'required',
            'jenis' => 'required',
            'kategori' => 'required',
            'nominal' => 'required'
        ]);
        Transaksi::insert([
            'tanggal' => $data->tanggal,
            'jenis' => $data->jenis,
            'kategori_id' => $data->kategori,
            'nominal' => $data->nominal,
            'keterangan' => $data->keterangan,
        ]);
        return redirect('/transaksi')->with("sukses", "Transaksi Berhasil Di Simpan");
    }
    public function transaksi_edit($id)
    {
        $kategori = Kategori::all();
        $transaksi = Transaksi::find($id);
        return view('transaksi_edit', ['kategori' => $kategori, 'transaksi' => $transaksi]);
    }
    public function transaksi_update($id, Request $data)
    {
        $data->validate([
            'tanggal' => 'required',
            'jenis' => 'required',
            'kategori' => 'required',
            'nominal' => 'required'
        ]);
        $transaksi = Transaksi::find($id);
        // ubah data tanggal, jenis, kategori, nominal, keterangan
        $transaksi->tanggal = $data->tanggal;
        $transaksi->jenis = $data->jenis;
        $transaksi->kategori_id = $data->kategori;
        $transaksi->nominal = $data->nominal;
        $transaksi->keterangan = $data->keterangan;
        // simpan perubahan
        $transaksi->save();
        // alihkan halaman ke halaman transaksi sambil mengirim session pesan notifikasi
        return redirect('transaksi')->with("sukses", "Transaksi berhasil diubah");
    }
    public function transaksi_hapus($id)
    {
        $transaksi = Transaksi::find($id);
        $transaksi->delete();
        return redirect('transaksi')->with("sukses", "Transaksi berhasil dihapus");
    }
    public function transaksi_cari(Request $request)
    {
        $cari =  $request->cari;
        $transaksi = Transaksi::orderBy('id', 'desc')
            ->where('jenis', 'like', "%" . $cari . "%")
            ->orWhere('tanggal', 'like', "%" . $cari . "%")
            ->orWhere('keterangan', 'like', "%" . $cari . "%")
            ->orWhere('nominal', 'like', "%" . $cari . "%")
            ->paginate(6);
        $transaksi->appends($request->only('cari'));
        return view('transaksi', ['transaksi' => $transaksi]);
    }
    // ----------------------LAPORAN---------------------------

    public function laporan()
    {
        $kategori  =   Kategori::all();
        return view('laporan', ['kategori' => $kategori]);
    }
    public function laporan_hasil(Request $request)
    {
        $request->validate([
            'dari' => 'required',
            'sampai' => 'required'
        ]);
        // data kategori
        $kategori = Kategori::all();
        // data filter
        $dari = $request->dari;
        $sampai = $request->sampai;
        $id_kategori = $request->kategori;
        // periksa kaegori yang di pilh
        if ($id_kategori == "semua") {
            $laporan = Transaksi::whereDate('tanggal', '>=', $dari)
                ->whereDate('tanggal', '>=', $sampai)
                ->orderBy('id', 'desc')->get();
        } else {
            // jika yang dipilih bukan "semua",
            //tampilkan transaksi berdasarkan kategori yang dipilih
            $laporan = Transaksi::where('kategori_id', $id_kategori)
                ->whereDate('tanggal', '>=', $dari)
                ->whereDate('tanggal', '<=', $sampai)
                ->orderBy('id', 'desc')->get();
        }
        // passing data laporan ke view laporan
        return view('laporan_hasil', ['laporan' => $laporan, 'kategori' => $kategori]);
    }
    public function laporan_print(Request $req)
    {
        $req->validate([
            'dari' => 'required',
            'sampai' => 'required'
        ]);
        // data kategori
        $kategori = Kategori::all();
        // data filter
        $dari = $req->dari;
        $sampai = $req->sampai;
        $id_kategori = $req->kategori;
        // periksa kategori yang dipiliih
        if ($id_kategori == "semua") {
            // jika semua, tampilkan semua transaksi
            $laporan = Transaksi::whereDate('tanggal', '>=', $dari)
                ->whereDate('tanggal', '<=', $sampai)
                ->orderBy('id', 'desc')->get();
        } else {
            // jika yang dipilih bukan semua, tampilkan transaksi berdasarkan kategori yang dipilih
            $laporan = Transaksi::where('kategori_id', $id_kategori)
                ->whereDate('tanggal', '>=', $dari)
                ->whereDate('tanggal', '<=', $sampai)
                ->orderBy('id', 'desc')->get();
        }
        // passing data laporan ke view laporan
        return view('laporan_print', ['laporan' => $laporan, 'kategori' => $kategori]);
    }
    public function laporan_excel()
    {
        return Excel::download(new LaporanExport, 'laporan.xlsx');
    }
}
