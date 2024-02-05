<?php

namespace App\Http\Controllers;


use App\Models\Pks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class PksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pks = DB::table("pks")
            // ->join('departemens', 'pkss.kode_dept', '=', 'departemens.kode_dept')
            ->get();
        // $departemen = DB::table('departemens')->get();
        // $cabang = DB::table('cabangs')->orderBy('kode_cabang')->get();
        return view('pks.index', compact('pks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'no_pks' => 'required|unique:pks,no_pks', // Ensure no_pks is unique in the pks table
                'nama' => 'required',
                'file' => 'file|mimes:pdf,doc,docx|max:2048', // Adjust file validation rules
            ]);

            $no_pks = $request->no_pks;
            $nama = $request->nama;

            $file = null;
            if ($request->hasFile('file')) {
                $file = $no_pks . "." . $request->file('file')->getClientOriginalExtension();

                // Store the file in the specified folder
                $folderPath = "public/uploads/pks/";
                $request->file('file')->storeAs($folderPath, $file);
            }

            $data = [
                'no_pks' => $no_pks,
                'nama' => $nama,
                'file' => $file,
            ];

            $simpan = DB::table('pks')->insert($data);
            if ($simpan) {
                return redirect('/pks')->with('success', 'Data berhasil disimpan');
            } else {
                return redirect()->back()->with('error', 'Data gagal disimpan');
            }
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                $message = "Data dengan no pks " . $no_pks . " sudah digunakan";
            } else {
                $message = $e->getMessage();
            }

            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $message);
        }
    }



    /**
     * Display the specified resource.
     */


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {

        $pks = DB::table('pks')->where('id', $id)->first();
        return view('pks.edit', compact('pks'));
    }

    /**
     * Update the specified resource in storage.
     */
    // Sesuaikan dengan namespace model Anda
    // Sesuaikan dengan namespace model Anda

    public function update(Request $request, $id)
    {
        try {
            // Pastikan data dengan ID yang diberikan ditemukan
            $existingpks = Pks::find($id);

            if (!$existingpks) {
                return redirect()->back()->with('error', 'Data tidak ditemukan');
            }

            $no_pks = $request->no_pks;
            $nama = $request->nama;
            $old_file = $existingpks->file;

            $file = null;
            if ($request->hasFile('file')) {
                $file = $no_pks . "." . $request->file('file')->getClientOriginalExtension();

                // Store the photo
                $folderPath = "public/uploads/pks/";
                $request->file('file')->storeAs($folderPath, $file);
            } else {
                // Jika tidak ada file yang diunggah, gunakan nama file yang sudah ada
                $file = $old_file;
            }

            $data = [
                'no_pks' => $no_pks,
                'nama' => $nama,
                'file' => $file,
            ];

            // Gunakan fungsi update dari model Eloquent
            $existingpks->update($data);

            return redirect('/pks')->with('success', 'Data Berhasil di Update');
        } catch (\Exception $e) {
            // Tangani exception jika terjadi kesalahan
            return redirect()->back()->with(['error' => 'Data Gagal di Update. Pesan Kesalahan: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $pks = PKS::findOrFail($id);
        return view('pks.show', compact('pks'));
    }


    /**
     * Remove the specified resource from storage.
     */

    public function destroy($id)
    {
        try {
            // Temukan proyek berdasarkan ID
            $pks = Pks::find($id);

            if ($pks) {
                // Hapus proyek
                $pks->delete();
                return redirect('/pks')->with('success', 'Data berhasil dihapus');
            }

            return redirect()->back()->with('error', 'Data tidak ditemukan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
