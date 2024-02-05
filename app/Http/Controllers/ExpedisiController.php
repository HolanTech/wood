<?php

namespace App\Http\Controllers;

use App\Models\Expedisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class ExpedisiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expedisi = DB::table("expedisis")->orderBy('nama')
            // ->join('departemens', 'expedisis.kode_dept', '=', 'departemens.kode_dept')
            ->get();
        // $departemen = DB::table('departemens')->get();
        // $cabang = DB::table('cabangs')->orderBy('kode_cabang')->get();
        return view('expedisi.index', compact('expedisi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('expedisi.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $nama = $request->nama;
            $alamat = $request->alamat;
            $kendaraan = $request->kendaraan;
            $email = $request->email;
            $no_hp = $request->no_hp;
            $foto = null;

            if ($request->hasFile('foto')) {
                $foto = $nama . "." . $request->file('foto')->getClientOriginalExtension();

                // Store the photo
                $folderPath = "public/uploads/expedisi/";
                $request->file('foto')->storeAs($folderPath, $foto);
            }

            $data = [
                'nama' => $nama,
                'no_hp' => $no_hp,
                'email' => $email,
                'kendaraan' => $kendaraan,
                'foto' => $foto,
                'alamat' => $alamat,
            ];

            $simpan = DB::table('expedisis')->insert($data);

            if ($simpan) {
                return redirect('/expedisi')->with('success', 'Data berhasil disimpan');
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

        $expedisi = DB::table('expedisis')->where('id', $id)->first();
        return view('expedisi.edit', compact('expedisi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // Validasi apakah data dengan $id ditemukan
            $expedisi = Expedisi::find($id);

            if (!$expedisi) {
                return redirect('/expedisi')->with('error', 'Data tidak ditemukan');
            }

            $request->validate([
                'nama' => 'required',
                'alamat' => 'required',
                'no_hp' => 'required',
                'kendaraan' => 'required',
                'email' => 'required|email',
                'foto' => 'image|mimes:jpeg,png,jpg|max:2048', // Adjust the file validation as needed
            ]);

            $nama = $request->nama;
            $alamat = $request->alamat;
            $no_hp = $request->no_hp;
            $kendaraan = $request->kendaraan;
            $email = $request->email;
            $old_foto = $expedisi->foto;

            if ($request->hasFile('foto')) {
                $foto = $nama . "." . $request->file('foto')->getClientOriginalExtension();

                // Hapus foto lama
                $folderPathOld = "public/uploads/expedisi/$old_foto";
                Storage::delete($folderPathOld);

                // Simpan foto baru
                $folderPath = "public/uploads/expedisi/";
                $request->file('foto')->storeAs($folderPath, $foto);
            } else {
                $foto = $old_foto;
            }

            $data = [
                'nama' => $nama,
                'kendaraan' => $kendaraan,
                'no_hp' => $no_hp,
                'email' => $email,
                'foto' => $foto,
                'alamat' => $alamat,
            ];

            // Update data pada database
            $update = $expedisi->update($data);

            if ($update) {
                return redirect('/expedisi')->with('success', 'Data Berhasil di Update');
            } else {
                return redirect('/expedisi')->with('error', 'Data Gagal di Update');
            }
        } catch (\Exception $e) {
            return redirect('/expedisi')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $expedisi = Expedisi::find($id);

            if ($expedisi) {
                // Hapus foto yang tersimpan
                $folderPath = "public/uploads/expedisi/";
                Storage::delete($folderPath . $expedisi->foto);

                // Hapus record dari database
                $hapus = $expedisi->delete();

                if ($hapus) {
                    return redirect('/expedisi')->with('success', 'Data berhasil dihapus');
                } else {
                    return redirect('/expedisi')->with('error', 'Data gagal dihapus');
                }
            } else {
                return redirect('/expedisi')->with('error', 'Data tidak ditemukan');
            }
        } catch (\Exception $e) {
            return redirect('/expedisi')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
