<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $karyawan = DB::table("karyawans")->orderBy('nama_lengkap')
            // ->join('departemens', 'karyawans.kode_dept', '=', 'departemens.kode_dept')
            ->get();
        // $departemen = DB::table('departemens')->get();
        // $cabang = DB::table('cabangs')->orderBy('kode_cabang')->get();
        return view('karyawan.index', compact('karyawan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('karyawan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $nik = $request->nik;
            $nama_lengkap = $request->nama_lengkap;
            $jabatan = $request->jabatan;
            $no_hp = $request->no_hp;
            $password = Hash::make('123456');

            // Check if the employee with the same NIK already exists
            $existingKaryawan = DB::table('karyawans')->where('nik', $nik)->first();
            if ($existingKaryawan) {
                return redirect()->back()->with('error', 'Data dengan NIK ' . $nik . ' sudah ada.');
            }

            $foto = null;
            if ($request->hasFile('foto')) {
                $foto = $nik . "." . $request->file('foto')->getClientOriginalExtension();

                // Store the photo
                $folderPath = "public/uploads/karyawan/";
                $request->file('foto')->storeAs($folderPath, $foto);
            }

            $data = [
                'nik' => $nik,
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'jabatan' => $jabatan,
                'foto' => $foto,
                'password' => $password,
            ];

            $simpan = DB::table('karyawans')->insert($data);
            if ($simpan) {
                return redirect('/karyawan')->with('success', 'Data berhasil disimpan');
            } else {
                return redirect()->back()->with('error', 'Data gagal disimpan');
            }
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                $message = "Data dengan NIK " . $nik . " sudah digunakan";
            } else {
                $message = $e->getMessage();
            }

            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $message);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $nik = $request->nik;
        $karyawan = DB::table('karyawans')->where('nik', $nik)->first();
        return view('karyawan.edit', compact('karyawan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $nik)
    {
        $karyawan = DB::table('karyawans')->where('nik', $nik)->first();
        $nik = $request->nik;
        $nama_lengkap = $request->nama_lengkap;
        $jabatan = $request->jabatan;
        $no_hp = $request->no_hp;
        $password = $request->password;
        $old_foto = $request->old_foto;
        if ($request->hasFile('foto')) {
            $foto = $nik . "." . $request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = $old_foto;
        }
        try {
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'jabatan' => $jabatan,
                'foto' => $foto,
                'password' => $password,
            ];
            $update = DB::table('karyawans')->where('nik', $nik)->update($data);
            if ($update) {
                if ($request->hasFile('foto')) {
                    $folderPath = "public/uploads/karyawan/";
                    $folderPathOld = "public/uploads/karyawan/.$old_foto";
                    Storage::delete($folderPathOld);
                    $request->file('foto')->storeAs($folderPath, $foto);
                }
                return redirect()->back()->with('success', 'Data Berhasil di Update');
            }
        } catch (\Exception $e) {
            // dd($e->getMessage());
            return Redirect::back()->with(['error' => 'Data Gagal di Update']);
        }
    }
    public function editprofile()
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $karyawan = DB::table('karyawans')->where('nik', $nik)->first();
        return view('karyawan.editprofile', compact('karyawan'));
    }
    public function updateprofile(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $nama_lengkap = $request->nama_lengkap;
        $no_hp = $request->no_hp;
        $password = bcrypt($request->password);

        $karyawan = DB::table('karyawans')->where('nik', $nik)->first();

        if ($request->hasFile('foto')) {
            $foto = $nik . "." . $request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = $karyawan->foto;
        }

        $data = [
            'nama_lengkap' => $nama_lengkap,
            'no_hp' => $no_hp,
            'foto' => $foto,
        ];

        if (!empty($request->password)) {
            $data['password'] = $password;
        }

        $update = DB::table('karyawans')->where('nik', $nik)->update($data);

        if ($update) {
            if ($request->hasFile('foto')) {
                $folderPath = "public/uploads/karyawan/";
                $request->file('foto')->storeAs($folderPath, $foto);
            }
            return Redirect::back()->with(['success' => 'Data Berhasil di Update']);
        } else {
            return Redirect::back()->with(['error' => 'Data Gagal di Update']);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($nik)
    {
        try {
            $karyawan = DB::table('karyawans')->where('nik', $nik)->first();

            if ($karyawan) {
                // Hapus foto yang tersimpan
                $folderPath = "public/uploads/karyawan/";
                Storage::delete($folderPath . $karyawan->foto);

                // Hapus record dari database
                $hapus = DB::table('karyawans')->where('nik', $nik)->delete();

                if ($hapus) {
                    return redirect()->back()->with('success', 'Data berhasil dihapus');
                }
            }

            return redirect()->back()->with('error', 'Data tidak ditemukan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
