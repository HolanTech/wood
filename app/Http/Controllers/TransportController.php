<?php

namespace App\Http\Controllers;


use App\Models\Expedisi;
use App\Models\Pelanggan;
use App\Models\Transport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class TransportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transport = DB::table("transports")
            ->join('expedisis', 'transports.expedisi_id', '=', 'expedisis.id')
            ->join('po_outs', 'transports.po_out_id', '=', 'po_outs.po_out')
            ->join('pelanggans', 'transports.customer_id', '=', 'pelanggans.id')
            ->orderBy('tanggal', 'desc')
            ->select('transports.*', 'expedisis.nama as expedisi_nama', 'pelanggans.nama as customer_nama')
            ->get();

        $customers = DB::table('pelanggans')->get();
        $expedisi = DB::table('expedisis')->get();
        $po_out = DB::table('po_outs')->get();

        return view('transport.index', compact('transport', 'expedisi', 'po_out', 'customers'));
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customer = Pelanggan::get();
        $expedisi = Expedisi::get();
        $po_out = DB::table('po_outs')->get();
        return view('transport.create', compact('expedisi', 'po_out', 'customer'));
    }

    /**
     * Store a newly created resource in storage.
     */ public function store(Request $request)
    {
        try {
            // Input validation (add more if needed)
            $validator = Validator::make($request->all(), [
                'tanggal' => 'required',
                'po_out_id' => 'required',
                'expedisi_id' => 'required',
                'customer_id' => 'required',
                'qty' => 'required',
                'biaya' => 'required',
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
                $filename = 'transport_' . now()->format('YmdHis') . "." . $fileExtension;

                // Simpan file baru
                $file = $request->file('file')->storeAs('uploads/transport', $filename, 'public');

                // Extract only the filename without the path
                $file = pathinfo($file, PATHINFO_BASENAME);
            }

            // Data for the first insert
            $dataTransport = [
                'tanggal' => $request->tanggal,
                'po_out_id' => $request->po_out_id,
                'expedisi_id' => $request->expedisi_id,
                'customer_id' => $request->customer_id,
                'qty' => $request->qty,
                'biaya' => $request->biaya,
                'file' => $file,
            ];

            // Insert the first record and retrieve the inserted ID
            $transportId = DB::table('transports')->insertGetId($dataTransport);

            // Data for the second insert
            $dataModal = [
                'transport_id' => $transportId,
                'po_id' => $request->po_out_id,
                'tanggal' => $request->tanggal,
                'debet' => '0',
                'credit' => $request->biaya, // Changed from $request->harga
                'ket' => 'Transportasi ' . ' ' . $request->po_out_id . ' ' . $request->qty
            ];

            // Database transactions for data consistency
            DB::beginTransaction();

            try {
                // Insert the second record
                DB::table('modals')->insert($dataModal);

                // Commit the transaction
                DB::commit();

                return redirect('/transport')->with('success', 'Data berhasil disimpan');
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
        $customer = Pelanggan::get();
        $expedisi = Expedisi::get();
        $po_out = DB::table('po_outs')->get();
        $transport = Transport::find($id);
        return view('transport.edit', compact('transport', 'expedisi', 'po_out', 'customer'));
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
                return redirect('/transport')->with('error', 'Invalid ID format');
            }

            $transport_record = Transport::find($id);

            if (!$transport_record) {
                return redirect('/transport')->with('error', 'Data tidak ditemukan');
            }

            $tanggal = $request->tanggal;
            $po_out_id = $request->po_out_id;
            $expedisi_id = $request->expedisi_id;
            $customer_id = $request->customer_id;
            $qty = $request->qty;
            $biaya = $request->biaya;
            $old_file = $transport_record->file;

            if ($request->hasFile('file')) {
                // Validasi ekstensi file yang diizinkan
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf']; // Sesuaikan dengan ekstensi yang diizinkan
                $fileExtension = $request->file('file')->getClientOriginalExtension();

                if (!in_array($fileExtension, $allowedExtensions)) {
                    return redirect('/transport')->with('error', 'Ekstensi file tidak diizinkan');
                }

                // Generate nama file dengan tambahan tanggal dan jam
                $file = 'transport_' . now()->format('YmdHis') . "." . $fileExtension;

                // Hapus file lama
                Storage::delete("uploads/transport/$old_file");

                // Simpan file baru
                $request->file('file')->storeAs('uploads/transport/', $file, 'public');
            } else {
                $file = $old_file;
            }

            $data = [
                'tanggal' => $tanggal,
                'po_out_id' => $po_out_id,
                'expedisi_id' => $expedisi_id,
                'customer_id' => $customer_id,
                'qty' => $qty,
                'biaya' => $biaya,
                'file' => $file,
            ];

            // Update dilakukan pada model Eloquent
            $transport_record->update($data);

            // Database transactions for data consistency
            DB::beginTransaction();

            try {
                $updatekas = DB::table('modals')->where('transport_id', $id)->update([
                    'transport_id' => $id,
                    'po_id' => $po_out_id,
                    'tanggal' => $tanggal,
                    'debet' => '0',
                    'credit' => $biaya,
                    'ket' => 'transport_ ' . ' ' . $po_out_id . ' ' . $qty,
                ]);

                // dd($updatekas);  // Add this line for debugging


                if ($updatekas) {
                    DB::commit();

                    return redirect('/transport')->with('success', 'Data Berhasil di Update');
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
            $transport = Transport::find($id);

            if (!$transport) {
                return redirect('/transport')->with('error', 'Data tidak ditemukan');
            }

            // Database transactions for data consistency
            DB::beginTransaction();

            try {
                // Hapus file yang tersimpan
                $folderPath = "public/uploads/transport/";
                Storage::delete($folderPath . $transport->file);

                // Hapus record dari modals table
                DB::table('modals')->where('transport_id', $id)->delete();

                // Hapus record dari Transport table
                $transport->delete();

                // Commit the transaction
                DB::commit();

                return redirect('/transport')->with('success', 'Data berhasil dihapus');
            } catch (\Exception $e) {
                // Rollback the transaction on failure
                DB::rollBack();

                return redirect('/transport')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
            }
        } catch (\Exception $e) {
            return redirect('/transport')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
