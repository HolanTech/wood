<?php

namespace App\Http\Controllers;


use App\Models\Beli;
use App\Models\pengrajin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class BeliController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $beli = DB::table("belis")
            ->join('pengrajins', 'belis.pengrajin_id', '=', 'pengrajins.id')
            ->join('po_outs', 'belis.po_out_id', '=', 'po_outs.po_out')
            ->orderBy('tanggal')
            ->select('belis.*', 'pengrajins.nama as pengrajin_nama')
            ->get();

        $pengrajin = DB::table('pengrajins')->get();
        $po_out = DB::table('po_outs')->get();

        return view('beli.index', compact('beli', 'pengrajin', 'po_out'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pengrajin = pengrajin::get();
        $po_out = DB::table('po_outs')->get();
        return view('beli.create', compact('pengrajin', 'po_out'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Input validation (add more if needed)
            $validator = Validator::make($request->all(), [
                'tanggal' => 'required',
                'po_out_id' => 'required',
                'pengrajin_id' => 'required',
                'order' => 'required',
                'qty' => 'required',
                'harga' => 'required',
                'file' => 'file|mimes:jpg,jpeg,png,pdf|max:2048', // Adjust the file validation as needed
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // File handling
            $file = null;

            if ($request->hasFile('file')) {
                // Validasi ekstensi file yang diizinkan
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf']; // Sesuaikan dengan ekstensi yang diizinkan
                $fileExtension = $request->file('file')->getClientOriginalExtension();

                if (!in_array($fileExtension, $allowedExtensions)) {
                    return redirect('/beli')->with('error', 'Ekstensi file tidak diizinkan');
                }

                // Get the original filename
                $filename = 'order_' . now()->format('YmdHis') . "." . $fileExtension;

                // Simpan file baru
                $file = $request->file('file')->storeAs('uploads/beli', $filename, 'public');

                // Extract only the filename without the path
                $file = pathinfo($file, PATHINFO_BASENAME);
            }

            // Data for the first insert
            $dataBeli = [
                'tanggal' => $request->tanggal,
                'po_out_id' => $request->po_out_id,
                'pengrajin_id' => $request->pengrajin_id,
                'order' => $request->order,
                'qty' => $request->qty,
                'harga' => $request->harga,
                'file' => $file,
            ];

            // Insert the first record and retrieve the inserted ID
            $beliId = DB::table('belis')->insertGetId($dataBeli);

            // Data for the second insert
            $dataModal = [
                'beli_id' => $beliId,
                'tanggal' => $request->tanggal,
                'po_id' => $request->po_out_id,
                'debet' => '0',
                'credit' => $request->harga,
                'ket' => 'pembayaran ' . ' ' . $request->po_out_id . ' ' . $request->qty,
            ];

            // Database transactions for data consistency
            DB::beginTransaction();

            try {
                // Insert the second record
                DB::table('modals')->insert($dataModal);

                // Commit the transaction
                DB::commit();

                return redirect('/beli')->with('success', 'Data berhasil disimpan');
            } catch (\Exception $e) {
                // Rollback the transaction on failure
                DB::rollBack();

                if ($e->getCode() == 23000) {
                    $message = "Data gagal Disimpan";
                } else {
                    $message = $e->getMessage();
                }

                return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $message);
            }
        } catch (\Exception $e) {
            $message = ($e->getCode() == 23000) ? "Data gagal Disimpan" : $e->getMessage();
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
        $pengrajin = pengrajin::get();
        $po_out = DB::table('po_outs')->get();
        $beli = Beli::find($id);
        return view('beli.edit', compact('beli', 'pengrajin', 'po_out'));
    }

    /**
     * Update the specified resource in storage.
     */


    public function update(Request $request, string $id)
    {
        try {
            // Validasi apakah data dengan $id ditemukan
            $id = (int)$id;

            if ($id <= 0) {
                return redirect('/beli')->with('error', 'Invalid ID format');
            }

            $beli_record = Beli::find($id);

            if (!$beli_record) {
                return redirect('/beli')->with('error', 'Data tidak ditemukan');
            }

            $tanggal = $request->tanggal;
            $po_out_id = $request->po_out_id;
            $pengrajin_id = $request->pengrajin_id;
            $order = $request->order;
            $qty = $request->qty;
            $harga = $request->harga;
            $old_file = $beli_record->file;

            if ($request->hasFile('file')) {
                // Validasi ekstensi file yang diizinkan
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf']; // Sesuaikan dengan ekstensi yang diizinkan
                $fileExtension = $request->file('file')->getClientOriginalExtension();

                if (!in_array($fileExtension, $allowedExtensions)) {
                    return redirect('/beli')->with('error', 'Ekstensi file tidak diizinkan');
                }

                // Generate nama file dengan tambahan tanggal dan jam
                $file = 'order_' . now()->format('YmdHis') . "." . $fileExtension;

                // Hapus file lama
                Storage::delete("uploads/beli/$old_file");

                // Simpan file baru
                $request->file('file')->storeAs('uploads/beli/', $file, 'public');
            } else {
                $file = $old_file;
            }

            $data = [
                'tanggal' => $tanggal,
                'po_out_id' => $po_out_id,
                'pengrajin_id' => $pengrajin_id,
                'order' => $order,
                'qty' => $qty,
                'harga' => $harga,
                'file' => $file,
            ];

            // Update dilakukan pada model Eloquent
            $beli_record->update($data);

            // Database transactions for data consistency
            DB::beginTransaction();

            try {
                $updatekas = DB::table('modals')->where('beli_id', $id)->update([
                    'tanggal' => $tanggal,
                    'debet' => '0',
                    'po_id' => $request->po_out_id,
                    'credit' => $harga,
                    'ket' => 'pembayaran ' . ' ' . $po_out_id . ' ' . $qty,
                ]);

                // dd($updatekas);  // Add this line for debugging


                if ($updatekas) {
                    DB::commit();

                    return redirect('/beli')->with('success', 'Data Berhasil di Update');
                } else {
                    throw new \Exception('Update modal data failed');
                }
            } catch (\Exception $e) {
                DB::rollBack();

                $message = ($e->getCode() == 23000) ? "Data gagal Disimpan" : $e->getMessage();
                return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $message);
            }
        } catch (\Exception $e) {
            $message = ($e->getCode() == 23000) ? "Data gagal Disimpan" : $e->getMessage();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $message);
        }
    }






    /**
     * Remove the specified resource from storage.
     */

    public function destroy($id)
    {
        try {
            $beli = Beli::find($id);

            if (!$beli) {
                return redirect('/beli')->with('error', 'Data tidak ditemukan');
            }

            // Database transactions for data consistency
            DB::beginTransaction();

            try {
                // Hapus file yang tersimpan
                $folderPath = "public/uploads/beli/";
                Storage::delete($folderPath . $beli->file);

                // Hapus record dari modals table
                DB::table('modals')->where('beli_id', $id)->delete();

                // Hapus record dari beli table
                $beli->delete();

                // Commit the transaction
                DB::commit();

                return redirect('/beli')->with('success', 'Data berhasil dihapus');
            } catch (\Exception $e) {
                // Rollback the transaction on failure
                DB::rollBack();

                return redirect('/beli')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
            }
        } catch (\Exception $e) {
            return redirect('/beli')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
