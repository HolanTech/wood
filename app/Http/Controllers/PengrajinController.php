<?php

namespace App\Http\Controllers;

use App\Models\Pengrajin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class PengrajinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pengrajin = DB::table("pengrajins")->orderBy('nama')
            // ->join('departemens', 'pengrajins.kode_dept', '=', 'departemens.kode_dept')
            ->get();
        // $departemen = DB::table('departemens')->get();
        // $cabang = DB::table('cabangs')->orderBy('kode_cabang')->get();
        return view('pengrajin.index', compact('pengrajin'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pengrajin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $nama = $request->nama;
            $alamat = $request->alamat;
            $email = $request->email;
            $no_hp = $request->no_hp;
            $badan_hukum = $request->badan_hukum;
            $foto = null;

            if ($request->hasFile('foto')) {
                $foto = $nama . "." . $request->file('foto')->getClientOriginalExtension();

                // Store the photo
                $folderPath = "public/uploads/pengrajin/";
                $request->file('foto')->storeAs($folderPath, $foto);
            }

            $data = [
                'nama' => $nama,
                'no_hp' => $no_hp,
                'email' => $email,
                'foto' => $foto,
                'alamat' => $alamat,
                'badan_hukum' => $badan_hukum,
            ];

            $simpan = DB::table('pengrajins')->insert($data);

            if ($simpan) {
                return redirect('/pengrajin')->with('success', 'Data berhasil disimpan');
            } else {
                return redirect()->back()->with('error', 'Data gagal disimpan');
            }
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                $message = "Data dengan nama " . $nama . " gagal Disimpan";
            } else {
                $message = $e->getMessage();
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $message);
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(string $nama)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {

        $pengrajin = DB::table('pengrajins')->where('id', $id)->first();
        return view('pengrajin.edit', compact('pengrajin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // Validasi apakah data dengan $id ditemukan
            $pengrajin = Pengrajin::find($id);

            if (!$pengrajin) {
                return redirect('/pengrajin')->with('error', 'Data tidak ditemukan');
            }

            $nama = $request->nama;
            $alamat = $request->alamat;
            $no_hp = $request->no_hp;
            $email = $request->email;
            $badan_hukum = $request->badan_hukum;
            $old_foto = $pengrajin->foto;

            if ($request->hasFile('foto')) {
                $foto = $nama . "." . $request->file('foto')->getClientOriginalExtension();

                // Hapus foto lama
                $folderPathOld = "public/uploads/pengrajin/$old_foto";
                Storage::delete($folderPathOld);

                // Simpan foto baru
                $folderPath = "public/uploads/pengrajin/";
                $request->file('foto')->storeAs($folderPath, $foto);
            } else {
                $foto = $old_foto;
            }

            $data = [
                'nama' => $nama,
                'no_hp' => $no_hp,
                'email' => $email,
                'foto' => $foto,
                'badan_hukum' => $badan_hukum,
                'alamat' => $alamat,
            ];

            // Update data pada database
            $update = $pengrajin->update($data);

            if ($update) {
                return redirect('/pengrajin')->with('success', 'Data Berhasil di Update');
            } else {
                return redirect('/pengrajin')->with('error', 'Data Gagal di Update');
            }
        } catch (\Exception $e) {
            return redirect('/pengrajin')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $pengrajin = Pengrajin::find($id);

            if ($pengrajin) {
                // Hapus foto yang tersimpan
                $folderPath = "public/uploads/pengrajin/";
                Storage::delete($folderPath . $pengrajin->foto);

                // Hapus record dari database
                $hapus = $pengrajin->delete();

                if ($hapus) {
                    return redirect('/pengrajin')->with('success', 'Data berhasil dihapus');
                } else {
                    return redirect('/pengrajin')->with('error', 'Data gagal dihapus');
                }
            } else {
                return redirect('/pengrajin')->with('error', 'Data tidak ditemukan');
            }
        } catch (\Exception $e) {
            return redirect('/pengrajin')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
