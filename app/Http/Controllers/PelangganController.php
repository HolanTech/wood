<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pelanggan = DB::table("pelanggans")->orderBy('nama')
            // ->join('departemens', 'pelanggans.kode_dept', '=', 'departemens.kode_dept')
            ->get();
        // $departemen = DB::table('departemens')->get();
        // $cabang = DB::table('cabangs')->orderBy('kode_cabang')->get();
        return view('pelanggan.index', compact('pelanggan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pelanggan.create');
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
                $folderPath = "public/uploads/pelanggan/";
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

            $simpan = DB::table('pelanggans')->insert($data);

            if ($simpan) {
                return redirect('/pelanggan')->with('success', 'Data berhasil disimpan');
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

        $pelanggan = DB::table('pelanggans')->where('id', $id)->first();
        return view('pelanggan.edit', compact('pelanggan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // Validasi apakah data dengan $id ditemukan
            $pelanggan = Pelanggan::find($id);

            if (!$pelanggan) {
                return redirect('/pelanggan')->with('error', 'Data tidak ditemukan');
            }

            $nama = $request->nama;
            $alamat = $request->alamat;
            $no_hp = $request->no_hp;
            $email = $request->email;
            $badan_hukum = $request->badan_hukum;
            $old_foto = $pelanggan->foto;

            if ($request->hasFile('foto')) {
                $foto = $nama . "." . $request->file('foto')->getClientOriginalExtension();

                // Hapus foto lama
                $folderPathOld = "public/uploads/pelanggan/$old_foto";
                Storage::delete($folderPathOld);

                // Simpan foto baru
                $folderPath = "public/uploads/pelanggan/";
                $request->file('foto')->storeAs($folderPath, $foto);
            } else {
                $foto = $old_foto;
            }

            $data = [
                'nama' => $nama,
                'no_hp' => $no_hp,
                'email' => $email,
                'foto' => $foto,
                'alamat' => $alamat,
                'badan_hukum' => $badan_hukum,
            ];

            // Update data pada database
            $update = $pelanggan->update($data);

            if ($update) {
                return redirect('/pelanggan')->with('success', 'Data Berhasil di Update');
            } else {
                return redirect('/pelanggan')->with('error', 'Data Gagal di Update');
            }
        } catch (\Exception $e) {
            return redirect('/pelanggan')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $pelanggan = Pelanggan::find($id);

            if ($pelanggan) {
                // Hapus foto yang tersimpan
                $folderPath = "public/uploads/pelanggan/";
                Storage::delete($folderPath . $pelanggan->foto);

                // Hapus record dari database
                $hapus = $pelanggan->delete();

                if ($hapus) {
                    return redirect('/pelanggan')->with('success', 'Data berhasil dihapus');
                } else {
                    return redirect('/pelanggan')->with('error', 'Data gagal dihapus');
                }
            } else {
                return redirect('/pelanggan')->with('error', 'Data tidak ditemukan');
            }
        } catch (\Exception $e) {
            return redirect('/pelanggan')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
