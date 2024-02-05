<?php

namespace App\Http\Controllers;

use App\Models\pengrajin;
use App\Models\Po_out;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class PoOutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $po_out = DB::table("po_outs")
            ->join('pengrajins', 'po_outs.pengrajin_id', '=', 'pengrajins.id')
            ->orderBy('po_out')
            ->get();
        $pengrajin = DB::table('pengrajins')->get();
        // $cabang = DB::table('cabangs')->orderBy('kode_cabang')->get();
        return view('po_out.index', compact('po_out', 'pengrajin'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pengrajin = pengrajin::get();
        return view('po_out.create', compact('pengrajin'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $po_out = $request->po_out;
            $pengrajin_id = $request->pengrajin_id;
            $order = $request->order;
            $qty = $request->qty;
            $harga = $request->harga;
            $file = null;

            if ($request->hasFile('file')) {
                $file = $po_out . "." . $request->file('file')->getClientOriginalExtension();

                // Store the photo
                $folderPath = "public/uploads/po_out/";
                $request->file('file')->storeAs($folderPath, $file);
            }

            $data = [
                'po_out' => $po_out,
                'pengrajin_id' => $pengrajin_id,
                'order' => $order,
                'qty' => $qty,
                'harga' => $harga,
                'file' => $file,
            ];

            $simpan = DB::table('po_outs')->insert($data);

            if ($simpan) {
                return redirect('/po_out')->with('success', 'Data berhasil disimpan');
            } else {
                return redirect()->back()->with('error', 'Data gagal disimpan');
            }
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                $message = "Data dengan nomor " . $po_out . " gagal Disimpan";
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
    public function edit(Request $request, $po_out)
    {
        $pengrajin = pengrajin::get();
        $po_out = DB::table('po_outs')->where('po_out', $po_out)->first();
        return view('po_out.edit', compact('po_out', 'pengrajin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $po_out)
    {
        try {
            // Validasi apakah data dengan $po_out ditemukan
            $po_out_record = DB::table('po_outs')->where('po_out', $po_out)->first();

            if (!$po_out_record) {
                return redirect('/po_out')->with('error', 'Data tidak ditemukan');
            }

            $pengrajin_id = $request->pengrajin_id;
            $order = $request->order;
            $qty = $request->qty;
            $harga = $request->harga;
            $old_file = $po_out_record->file;

            if ($request->hasFile('file')) {
                $file = $po_out . "." . $request->file('file')->getClientOriginalExtension();

                // Hapus file lama
                $folderPathOld = "public/uploads/po_out/$old_file";
                Storage::delete($folderPathOld);

                // Simpan file baru
                $folderPath = "public/uploads/po_out/";
                $request->file('file')->storeAs($folderPath, $file);
            } else {
                $file = $old_file;
            }

            // Perhatikan bahwa $po_out_record diubah menjadi $po_out pada bagian berikut
            $data = [
                'pengrajin_id' => $pengrajin_id,
                'order' => $order,
                'qty' => $qty,
                'harga' => $harga,
                'file' => $file,
            ];

            // Perhatikan bahwa update dilakukan pada model Eloquent, bukan pada hasil query
            $update = DB::table('po_outs')->where('po_out', $po_out)->update($data);

            if ($update) {
                return redirect('/po_out')->with('success', 'Data Berhasil di Update');
            } else {
                return redirect('/po_out')->with('error', 'Data Gagal di Update');
            }
        } catch (\Exception $e) {
            return redirect('/po_out')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($po_out)
    {
        try {
            $po_out = Po_out::find($po_out);

            if ($po_out) {
                // Hapus file yang tersimpan
                $folderPath = "public/uploads/po_out/";
                Storage::delete($folderPath . $po_out->file);

                // Hapus record dari database
                $hapus = $po_out->delete();

                if ($hapus) {
                    return redirect('/po_out')->with('success', 'Data berhasil dihapus');
                } else {
                    return redirect('/po_out')->with('error', 'Data gagal dihapus');
                }
            } else {
                return redirect('/po_out')->with('error', 'Data tidak ditemukan');
            }
        } catch (\Exception $e) {
            return redirect('/po_out')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
