<?php

namespace App\Http\Controllers;



use App\Models\QC;
use App\Models\Beli;
use App\Models\Jual;
use App\Models\Modal;
use App\Models\Po_in;
use App\Models\Expedisi;
use App\Models\Karyawan;
use App\Models\Pelanggan;
use App\Models\Transport;
use App\Models\Oprational;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class QCController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $qc = DB::table("q_c_s")
            ->join('expedisis', 'q_c_s.expedisi_id', '=', 'expedisis.id')
            ->join('po_outs', 'q_c_s.po_out_id', '=', 'po_outs.po_out')
            ->join('pelanggans', 'q_c_s.customer_id', '=', 'pelanggans.id')
            ->orderBy('tanggal', 'desc')
            ->select('q_c_s.*', 'expedisis.nama as expedisi_nama', 'pelanggans.nama as customer_nama')
            ->get();

        $customer = DB::table('pelanggans')->get();
        $expedisi = DB::table('expedisis')->get();
        $po_out = DB::table('po_outs')->get();
        $po_in = DB::table('po_ins')->get();

        return view('qc.index', compact('qc', 'expedisi', 'po_out', 'po_in', 'customer'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $customer = Pelanggan::get();
        $expedisi = Expedisi::get();
        $po_out = DB::table('po_outs')->get();
        $po_in = DB::table('po_ins')->get();
        return view('qc.create', compact('expedisi', 'po_out', 'po_in', 'customer'));
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
                'po_in_id' => 'required',
                'expedisi_id' => 'required',
                'customer_id' => 'required',
                'order' => 'required',
                'qty' => 'required',
                'harga' => 'required',
                'hargaqc' => 'required',
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

                if (!in_array($fileExtension, $allowedExtensions, true)) {

                    return redirect('/beli')->with('error', 'Ekstensi file tidak diizinkan');
                }

                // Get the original filename
                $filename = 'qc_' . now()->format('YmdHis') . "." . $fileExtension;

                // Simpan file baru
                $file = $request->file('file')->storeAs('uploads/qc', $filename, 'public');

                // Extract only the filename without the path
                $file = pathinfo($file, PATHINFO_BASENAME);
            }

            // Data for the first insert
            $dataqc = [
                'tanggal' => $request->tanggal,
                'po_out_id' => $request->po_out_id,
                'po_in_id' => $request->po_in_id,
                'expedisi_id' => $request->expedisi_id,
                'customer_id' => $request->customer_id,
                'order' => $request->order,
                'qty' => $request->qty,
                'harga' => $request->harga,
                'hargaqc' => $request->hargaqc,
                'file' => $file,
            ];

            // Insert the first record and retrieve the inserted ID
            $qcId = DB::table('q_c_s')->insertGetId($dataqc);

            // Fetch related data
            // Menghitung total biaya transport berdasarkan ekspedisi dan ID PO
            $transport = Transport::where('expedisi_id', $request->expedisi_id)
                ->where('po_out_id', $request->po_out_id)
                ->sum('biaya');

            // Menghitung total modal beli berdasarkan ID PO
            $modalbeli = Beli::where('po_out_id', $request->po_out_id)
                ->sum('harga');

            // Menghitung total modal balik sebenarnya
            $modalbaliksebenarnya = $transport + $modalbeli;

            // Mengecek apakah ada data modal berdasarkan ID PO yang memiliki jual_id tidak null
            $cek = Modal::where('po_id', $request->po_out_id)
                ->whereNotNull('jual_id')
                ->sum('debet');

            // Menghitung total modal balik seharusnya
            $modalbalikseharusnya = $modalbaliksebenarnya - $cek;

            // Menentukan nilai modal balik berdasarkan permintaan harga
            if ($request->harga >= $modalbalikseharusnya) {
                $modalbalik = $modalbalikseharusnya;
            } else {
                $modalbalik = $request->harga;
            }

            $sisamodalbelumbalik = $modalbalikseharusnya - $request->harga;
            $biayaoprational = Oprational::where('po_id', $request->po_out_id)->SUM('credit');
            if ($biayaoprational <= $request->harga - $modalbalikseharusnya) {
                $oprationalbalik = $biayaoprational;
            } else {
                $oprationalbalik = '0';
            }

            $karyawan = Karyawan::where('jabatan', '!=', 'Pihak Pertama')->count();

            $cekuntung = Jual::where('po_out_id', $request->po_out_id)
                ->sum('harga');
            $untung = $cekuntung + $request->harga - $transport - $modalbeli - $biayaoprational;



            if ($untung > '0') {
                $yatim = $untung * (2.5 / 100);
                $laba = $untung - $yatim;
                $perorang = $laba / $karyawan;
            } else {
                $yatim =  '0';
                $laba =  '0';
                $perorang =  '0';
            }
            // Data for the modal insert
            $dataModal = [
                'jual_id' => $qcId,
                'po_id' => $request->po_out_id,
                'tanggal' => $request->tanggal,
                'debet' => $modalbalik,
                'credit' => 0,
                'ket' => 'pembayaran transport dan modal beli' . ' ' . $request->po_out_id . ' ' . $request->qty
            ];
            $dataOprational = [
                'jual_id' => $qcId,
                'po_id' => $request->po_out_id,
                'tanggal' => $request->tanggal,
                'debet' => $oprationalbalik,
                'credit' => 0,
                'ket' => 'pengembalian biaya opratinal' . ' ' . $request->po_out_id . ' ' . $request->qty
            ];
            $datakas = [

                'jual_id' => $qcId,
                'tanggal' => $request->tanggal,
                'laba' => $laba,
                'perorang' =>  $perorang,
                'debet' => $laba,
                'credit' => 0,
                'ket' => 'untuk karyawan dari_' . ' ' . $request->po_out_id

            ];
            $datakasyatim = [

                'jual_id' => $qcId,
                'tanggal' => $request->tanggal,
                'laba' => $untung,
                'debet' => $yatim,
                'credit' => 0,
                'ket' => 'untuk yatim dari_' . ' ' . $request->po_out_id

            ];

            // Data for the jual insert
            $dataJual = [
                'qcID' => $qcId,
                'tanggal' => $request->tanggal,
                'po_out_id' => $request->po_out_id,
                'po_in_id' => $request->po_in_id,
                'customer_id' => $request->customer_id,
                'transport' => $transport,
                'oprational' => $biayaoprational,
                'order' => $request->order,
                'qty' => $request->qty,
                'harga' => $request->harga,
                'hasil' => $untung,
                'hargaqc' => $request->hargaqc,
                'file' => $file,
                'yatim' => $yatim,
                'laba' => $laba,
            ];

            // Database transactions for data consistency
            DB::beginTransaction();

            try {
                // Insert the second records
                DB::table('modals')->insert($dataModal);
                DB::table('oprationals')->insert($dataOprational);
                DB::table('juals')->insert($dataJual);
                DB::table('kas')->insert($datakas);
                DB::table('kas_yatims')->insert($datakasyatim);

                // Commit the transaction
                DB::commit();

                return redirect('/qc')->with('success', 'Data berhasil disimpan');
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
        $po_in = DB::table('po_ins')->get();
        $qc = QC::find($id);

        return view('qc.edit', compact('qc', 'expedisi', 'po_out', 'po_in', 'customer'));
    }


    public function update(Request $request, string $id)
    {
        try {
            // Validasi apakah data dengan $id ditemukan
            $id = (int)$id;

            if ($id <= 0) {
                return redirect('/qc')->with('error', 'Invalid ID format');
            }

            $qc_record = qc::find($id);

            if (!$qc_record) {
                return redirect('/qc')->with('error', 'Data tidak ditemukan');
            }

            $tanggal = $request->tanggal;
            $po_out_id = $request->po_out_id;
            $expedisi_id = $request->expedisi_id;
            $customer_id = $request->customer_id;
            $qty = $request->qty;
            $order = $request->order;
            $harga = $request->harga;
            $hargaqc = $request->hargaqc;
            $old_file = $qc_record->file;

            if ($request->hasFile('file')) {
                // Validasi ekstensi file yang diizinkan
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf']; // Sesuaikan dengan ekstensi yang diizinkan
                $fileExtension = $request->file('file')->getClientOriginalExtension();

                if (!in_array($fileExtension, $allowedExtensions)) {
                    return redirect('/qc')->with('error', 'Ekstensi file tidak diizinkan');
                }

                // Generate nama file dengan tambahan tanggal dan jam
                $file = 'qc_' . now()->format('YmdHis') . "." . $fileExtension;

                // Hapus file lama
                Storage::delete("uploads/qc/$old_file");

                // Simpan file baru
                $request->file('file')->storeAs('uploads/qc/', $file, 'public');
            } else {
                $file = $old_file;
            }

            $data = [
                'tanggal' => $tanggal,
                'po_out_id' => $po_out_id,
                'expedisi_id' => $expedisi_id,
                'customer_id' => $customer_id,
                'qty' => $qty,
                'order' => $order,
                'harga' => $harga,
                'hargaqc' => $hargaqc,
                'file' => $file,
            ];

            // Update dilakukan pada model Eloquent

            DB::table('q_c_s')->where('id', $id)->update($data);
            $qcRecord = qc::find($id);
            $qcId = $qcRecord->id;


            // Fetch related data
            $transport = Transport::where('expedisi_id', $request->expedisi_id)
                ->where('po_out_id', $request->po_out_id)
                ->SUM('biaya');
            $modalbeli = Beli::where('po_out_id', $request->po_out_id)
                ->SUM('harga');
            $modalbalikseharusnya = $transport + $modalbeli;
            if ($request->harga >= $modalbalikseharusnya) {
                $modalbalik = $modalbalikseharusnya;
            } else {
                $modalbalik = $request->harga;
            }
            $biayaoprational = Oprational::where('po_id', $request->po_out_id)->SUM('credit');
            if ($biayaoprational <= $request->harga - $modalbalikseharusnya) {
                $oprationalbalik = $biayaoprational;
            } else {
                $oprationalbalik = '0';
            }
            // $sisamodalbelumbalik = $modalbalikseharusnya + $oprationalbalik - $request->harga;

            $karyawan = Karyawan::where('jabatan', '!=', 'Pihak 1')->count();


            $untung = $request->harga - $transport - $modalbeli - $biayaoprational;

            if ($untung > '0') {
                $yatim = $untung * (2.5 / 100);
                $laba = $untung - $yatim;
                $perorang = $laba / $karyawan;
            } else {
                $yatim =  '0';
                $laba =  '0';
                $perorang =  '0';
            }
            // Data for the modal insert
            $updateModal = [
                'jual_id' => $qcId,
                'po_id' => $po_out_id,
                'tanggal' => $request->tanggal,
                'debet' => $modalbalik,
                'credit' => 0,
                'ket' => 'pembayaran transport dan modal beli dari CS untuk' . ' ' . $request->po_out_id . ' ' . $request->qty
            ];
            $updateOprational = [
                'jual_id' => $qcId,
                'po_id' => $request->po_out_id,
                'tanggal' => $request->tanggal,
                'debet' => $oprationalbalik,
                'credit' => 0,
                'ket' => 'pengembalian biaya opratinal dari CS untuk' . ' ' . $request->po_out_id . ' ' . $request->qty
            ];
            $updatekas = [

                'jual_id' => $qcId,
                'tanggal' => $request->tanggal,
                'laba' => $laba,
                'perorang' =>  $perorang,
                'debet' => $laba,
                'credit' => 0,
                'ket' => 'untuk karyawan dari CS untuk _' . ' ' . $request->po_out_id

            ];
            $updatekasyatim = [

                'jual_id' => $qcId,
                'tanggal' => $request->tanggal,
                'laba' => $untung,
                'debet' => $yatim,
                'credit' => 0,
                'ket' => 'untuk yatim  dari CS untuk _' . ' ' . $request->po_out_id

            ];

            // update for the jual insert
            $updateJual = [
                'qcID' => $qcId,
                'tanggal' => $request->tanggal,
                'po_out_id' => $request->po_out_id,
                'po_in_id' => $request->po_in_id,
                'customer_id' => $request->customer_id,
                'transport' => $transport,
                'oprational' => $biayaoprational,
                'order' => $request->order,
                'qty' => $request->qty,
                'harga' => $request->harga,
                'hasil' => $untung,
                'hargaqc' => $request->hargaqc,
                'file' => $file,
                'yatim' => $yatim,
                'laba' => $laba,
            ];
            // Database transactions for data consistency
            DB::beginTransaction();

            try {

                DB::table('modals')->where('jual_id', $qcId)->update($updateModal);
                DB::table('oprationals')->where('jual_id', $qcId)->update($updateOprational);
                DB::table('juals')->where('qcID', $qcId)->update($updateJual);
                DB::table('kas')->where('jual_id', $qcId)->update($updatekas);
                DB::table('kas_yatims')->where('jual_id', $qcId)->update($updatekasyatim);
                // dd($updatekas);  // Add this line for debugging


                if ($updatekas) {
                    DB::commit();

                    return redirect('/qc')->with('success', 'Data Berhasil di Update');
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
            $qc = qc::find($id);

            if (!$qc) {
                return redirect('/qc')->with('error', 'Data tidak ditemukan');
            }

            // Database transactions for data consistency
            DB::beginTransaction();

            try {
                // Hapus file yang tersimpan
                $folderPath = "public/uploads/qc/";
                Storage::delete($folderPath . $qc->file);

                // Hapus record dari modals table
                DB::table('modals')->where('jual_id', $id)->delete();
                DB::table('kas')->where('jual_id', $id)->delete();
                DB::table('kas_yatims')->where('jual_id', $id)->delete();
                DB::table('oprationals')->where('jual_id', $id)->delete();
                DB::table('juals')->where('qcID', $id)->delete();

                // Hapus record dari qc table
                $qc->delete();

                // Commit the transaction
                DB::commit();

                return redirect('/qc')->with('success', 'Data berhasil dihapus');
            } catch (\Exception $e) {
                // Rollback the transaction on failure
                DB::rollBack();

                return redirect('/qc')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
            }
        } catch (\Exception $e) {
            return redirect('/qc')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
