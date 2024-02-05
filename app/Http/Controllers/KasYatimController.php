<?php

namespace App\Http\Controllers;


use App\Models\KasYatim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasYatimController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    public function index(Request $request)
    {
        $data = KasYatim::get();
        $balance = KasYatim::sum('debet') - KasYatim::sum('credit');

        return view('kasyatim.index', compact('balance', 'data'));
    }
    public function create()
    {
        return view('kasyatim.create');
    }
    public function store(Request $request)
    {
        $tanggal = $request->tanggal;
        $KasYatim = $request->kas;
        $ket = $request->ket;
        $nominal = $request->nominal;

        try {
            if ($KasYatim == 'd') {
                $data = [
                    'tanggal' => $tanggal,
                    'debet' => $nominal,
                    'credit' => '0',
                    'laba' => '0',
                    'ket' => $ket
                ];
            } elseif ($KasYatim == 'c') {
                $data = [
                    'tanggal' => $tanggal,
                    'debet' => '0',
                    'laba' => '0',
                    'credit' => $nominal,
                    'ket' => $ket
                ];
            } else {
                throw new \Exception('Invalid value for KasYatim');
            }

            $simpan = DB::table('kas_yatims')->insert($data);

            if ($simpan) {
                return redirect('/kasyatim')->with(['success' => 'Data Berhasil Disimpan']);
            } else {
                return redirect('/kasyatim')->with(['error' => 'Data Gagal Disimpan']);
            }
        } catch (\Exception $e) {
            return redirect('/kasyatim')->with(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
    public function edit($id)
    {
        $kas = KasYatim::find($id);

        if (!$kas) {
            return redirect('/kasyatim')->with(['error' => 'Data tidak ditemukan']);
        }

        return view('kasyatim.edit', compact('kas'));
    }

    public function update(Request $request, $id)
    {
        $kasRecord = KasYatim::find($id);

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
            return redirect('/kasyatim')->with(['error' => 'Kas harus berupa "d" atau "c"']);
        }

        // Lakukan update data
        $updated = DB::table('kas_yatims')->where('id', $id)->update($updateData);

        if ($updated) {
            return redirect('/kasyatim')->with(['success' => 'Data Berhasil Diupdate']);
        } else {
            return redirect('/kasyatim')->with(['error' => 'Data Gagal Diupdate']);
        }
    }

    public function destroy($id)
    {
        $kas = KasYatim::find($id);
        $kas->delete();

        return redirect('/kasyatim')
            ->with('success', 'kas berhasil dihapus!');
    }
}
