<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Kas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    public function index(Request $request)
    {
        $data = Kas::get();
        $balance = Kas::sum('debet') - Kas::sum('credit');

        return view('kas.index', compact('balance', 'data'));
    }
    public function create()
    {
        return view('kas.create');
    }
    public function store(Request $request)
    {

        $tanggal = $request->tanggal;
        $kas = $request->kas;
        $ket = $request->ket;
        $nominal = $request->nominal;
        if ($kas == 'd') {
            $data = [
                'tanggal' => $tanggal,
                'debet' => $nominal,
                'credit' => '0',
                'ket' => $ket
            ];
            $simpan = DB::table('kas')->insert($data);
        }
        if ($kas == 'c') {
            $data = [
                'tanggal' => $tanggal,
                'debet' => '0',
                'credit' => $nominal,
                'ket' => $ket
            ];
            $simpan = DB::table('kas')->insert($data);
        }

        if ($simpan) {
            return redirect('/kas')->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return redirect('/kas')->with(['success' => 'Data Gagal Disimpan']);
        }
    }
    public function show(Request $request, $id)
    {
        $data = Kas::where('id', $id)
            ->get();
        $karyawan = Karyawan::where('jabatan', 'Karyawan')->get();
        return view('kas.show', compact('data', 'karyawan'));
    }
    public function edit($id)
    {
        $kas = Kas::find($id);

        if (!$kas) {
            return redirect('/kas')->with(['error' => 'Data tidak ditemukan']);
        }

        return view('kas.edit', compact('kas'));
    }

    public function update(Request $request, $id)
    {
        $kasRecord = Kas::find($id);

        if (!$kasRecord) {
            return redirect('/kas')->with(['error' => 'Data tidak ditemukan']);
        }

        $tanggal = $request->tanggal;
        $kasType = $request->kas; // Use a different variable name
        $ket = $request->ket;
        $nominal = $request->nominal;

        // Periksa apakah data debit atau kredit yang akan diupdate
        if ($kasType == 'd') {
            $updateData = [
                'tanggal' => $tanggal,
                'debet' => $nominal,
                'credit' => '0',
                'ket' => $ket
            ];
        } elseif ($kasType == 'c') {
            $updateData = [
                'tanggal' => $tanggal,
                'debet' => '0',
                'credit' => $nominal,
                'ket' => $ket
            ];
        } else {
            return redirect('/kas')->with(['error' => 'Kas harus berupa "d" atau "c"']);
        }

        // Lakukan update data
        $updated = DB::table('kas')->where('id', $id)->update($updateData);

        if ($updated) {
            return redirect('/kas')->with(['success' => 'Data Berhasil Diupdate']);
        } else {
            return redirect('/kas')->with(['error' => 'Data Gagal Diupdate']);
        }
    }

    public function destroy($id)
    {
        $kas = Kas::find($id);
        $kas->delete();

        return redirect('/kas')
            ->with('success', 'kas berhasil dihapus!');
    }
}
