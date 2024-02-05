<?php

namespace App\Http\Controllers;


use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $project = DB::table("projects")
            // ->join('departemens', 'projects.kode_dept', '=', 'departemens.kode_dept')
            ->get();
        // $departemen = DB::table('departemens')->get();
        // $cabang = DB::table('cabangs')->orderBy('kode_cabang')->get();
        return view('project.index', compact('project'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('project.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $project = $request->project;
            $nama_perusahaan = $request->nama_perusahaan;
            $no_kontrak = $request->no_kontrak;
            $status = $request->status;
            $keterangan = $request->keterangan;
            //           // Check if the employee with the same project already exists
            // $existingproject = DB::table('projects')->where('project', $project)->first();
            // if ($existingproject) {
            //     return redirect()->back()->with('error', 'Data dengan project ' . $project . ' sudah ada.');
            // }

            // $foto = null;
            // if ($request->hasFile('foto')) {
            //     $foto = $project . "." . $request->file('foto')->getClientOriginalExtension();

            //     // Store the photo
            //     $folderPath = "public/uploads/project/";
            //     $request->file('foto')->storeAs($folderPath, $foto);
            // }

            $data = [
                'project' => $project,
                'nama_perusahaan' => $nama_perusahaan,
                'status' => $status,
                'no_kontrak' => $no_kontrak,
                'keterangan' => $keterangan,

            ];

            $simpan = DB::table('projects')->insert($data);
            if ($simpan) {
                return redirect('/project')->with('success', 'Data berhasil disimpan');
            } else {
                return redirect()->back()->with('error', 'Data gagal disimpan');
            }
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                $message = "Data dengan project " . $project . " sudah digunakan";
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
    public function edit(Request $request, $id)
    {

        $project = DB::table('projects')->where('id', $id)->first();
        return view('project.edit', compact('project'));
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
            $existingProject = Project::find($id);

            if (!$existingProject) {
                return redirect()->back()->with('error', 'Data tidak ditemukan');
            }

            // Validasi input di sini jika diperlukan

            $data = [
                'nama_perusahaan' => $request->nama_perusahaan,
                'status' => $request->status,
                'no_kontrak' => $request->no_kontrak,
                'project' => $request->project,
                'keterangan' => $request->keterangan,
            ];

            // Gunakan fungsi update dari model Eloquent
            $existingProject->update($data);

            return redirect('/project')->with('success', 'Data Berhasil di Update');
        } catch (\Exception $e) {
            // Tangani exception jika terjadi kesalahan
            return redirect()->back()->with(['error' => 'Data Gagal di Update. Pesan Kesalahan: ' . $e->getMessage()]);
        }
    }



    /**
     * Remove the specified resource from storage.
     */

    public function destroy($id)
    {
        try {
            // Temukan proyek berdasarkan ID
            $project = Project::find($id);

            if ($project) {
                // Hapus proyek
                $project->delete();
                return redirect('/project')->with('success', 'Data berhasil dihapus');
            }

            return redirect()->back()->with('error', 'Data tidak ditemukan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
