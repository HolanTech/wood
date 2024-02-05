<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Po_in;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class PoInController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $po_in = DB::table("po_ins")
            ->join('pelanggans', 'po_ins.customer_id', '=', 'pelanggans.id')
            ->orderBy('po_in')
            ->get();
        $pelanggan = DB::table('pelanggans')->get();
        // $cabang = DB::table('cabangs')->orderBy('kode_cabang')->get();
        return view('po_in.index', compact('po_in', 'pelanggan'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customer = Pelanggan::get();
        return view('po_in.create', compact('customer'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $po_in = $request->po_in;
            $customer_id = $request->customer_id;
            $order = $request->order;
            $qty = $request->qty;
            $harga = $request->harga;
            $file = null;

            if ($request->hasFile('file')) {
                $file = $po_in . "." . $request->file('file')->getClientOriginalExtension();

                // Store the photo
                $folderPath = "public/uploads/po_in/";
                $request->file('file')->storeAs($folderPath, $file);
            }

            $data = [
                'po_in' => $po_in,
                'customer_id' => $customer_id,
                'order' => $order,
                'qty' => $qty,
                'harga' => $harga,
                'file' => $file,
            ];

            $simpan = DB::table('po_ins')->insert($data);

            if ($simpan) {
                return redirect('/po_in')->with('success', 'Data berhasil disimpan');
            } else {
                return redirect()->back()->with('error', 'Data gagal disimpan');
            }
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                $message = "Data dengan nomor " . $po_in . " gagal Disimpan";
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
    public function edit(Request $request, $po_in)
    {
        $customer = Pelanggan::get();
        $po_in = DB::table('po_ins')->where('po_in', $po_in)->first();
        return view('po_in.edit', compact('po_in', 'customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $po_in)
    {
        try {
            // Validasi apakah data dengan $po_in ditemukan
            $po_in_record = DB::table('po_ins')->where('po_in', $po_in)->first();

            if (!$po_in_record) {
                return redirect('/po_in')->with('error', 'Data tidak ditemukan');
            }

            $customer_id = $request->customer_id;
            $order = $request->order;
            $qty = $request->qty;
            $harga = $request->harga;
            $old_file = $po_in_record->file;

            if ($request->hasFile('file')) {
                $file = $po_in . "." . $request->file('file')->getClientOriginalExtension();

                // Hapus file lama
                $folderPathOld = "public/uploads/po_in/$old_file";
                Storage::delete($folderPathOld);

                // Simpan file baru
                $folderPath = "public/uploads/po_in/";
                $request->file('file')->storeAs($folderPath, $file);
            } else {
                $file = $old_file;
            }

            // Perhatikan bahwa $po_in_record diubah menjadi $po_in pada bagian berikut
            $data = [
                'customer_id' => $customer_id,
                'order' => $order,
                'qty' => $qty,
                'harga' => $harga,
                'file' => $file,
            ];

            // Perhatikan bahwa update dilakukan pada model Eloquent, bukan pada hasil query
            $update = DB::table('po_ins')->where('po_in', $po_in)->update($data);

            if ($update) {
                return redirect('/po_in')->with('success', 'Data Berhasil di Update');
            } else {
                return redirect('/po_in')->with('error', 'Data Gagal di Update');
            }
        } catch (\Exception $e) {
            return redirect('/po_in')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($po_in)
    {
        try {
            $po_in = Po_in::find($po_in);

            if ($po_in) {
                // Hapus file yang tersimpan
                $folderPath = "public/uploads/po_in/";
                Storage::delete($folderPath . $po_in->file);

                // Hapus record dari database
                $hapus = $po_in->delete();

                if ($hapus) {
                    return redirect('/po_in')->with('success', 'Data berhasil dihapus');
                } else {
                    return redirect('/po_in')->with('error', 'Data gagal dihapus');
                }
            } else {
                return redirect('/po_in')->with('error', 'Data tidak ditemukan');
            }
        } catch (\Exception $e) {
            return redirect('/po_in')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
