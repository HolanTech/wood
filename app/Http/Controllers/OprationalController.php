<?php

namespace App\Http\Controllers;

use App\Models\Oprational;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OprationalController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    public function index(Request $request)
    {
        $data = DB::table('oprationals')->select('po_id')->groupBy('po_id')->orderBy('tanggal', 'desc')->get();
        $balance = Oprational::sum('debet') - Oprational::sum('credit');

        return view('oprational.index', compact('balance', 'data'));
    }
    public function show(Request $request, $po_id)
    {
        $po_id = $request->po_id;
        $data = Oprational::where('po_id', $po_id)->orderBy('tanggal', 'desc')->get();
        $balance = Oprational::where('po_id', $po_id)->sum('debet') - Oprational::where('po_id', $po_id)->sum('credit');
        // dd($po_id);
        return view('oprational.show', compact('balance', 'data', 'po_id'));
    }

    public function create()
    {
        return view('oprational.create');
    }
    public function store(Request $request)
    {

        $tanggal = $request->tanggal;
        $kas = $request->kas;
        $ket = $request->ket;
        $po_id = $request->po_id;
        $nominal = $request->nominal;
        if ($kas == 'd') {
            $data = [
                'tanggal' => $tanggal,
                'debet' => $nominal,
                'credit' => '0',
                'po_id' => $po_id,
                'ket' => $ket
            ];
            $simpan = DB::table('oprationals')->insert($data);
        }
        if ($kas == 'c') {
            $data = [
                'tanggal' => $tanggal,
                'debet' => '0',
                'credit' => $nominal,
                'po_id' => $po_id,
                'ket' => $ket
            ];
            $simpan = DB::table('oprationals')->insert($data);
        }

        if ($simpan) {
            return redirect('/oprational')->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return redirect('/oprational')->with(['success' => 'Data Gagal Disimpan']);
        }
    }
    public function edit($id)
    {
        $oprational = Oprational::find($id);

        if (!$oprational) {
            return redirect('/oprational')->with(['error' => 'Data tidak ditemukan']);
        }

        return view('oprational.edit', compact('oprational'));
    }

    public function update(Request $request, $id)
    {
        $oprational = Oprational::find($id);

        if (!$oprational) {
            return redirect('/oprational')->with(['error' => 'Data tidak ditemukan']);
        }

        $tanggal = $request->tanggal;
        $kas = $request->kas;
        $ket = $request->ket;
        $po_id = $request->po_id;
        $nominal = $request->nominal;

        // Periksa apakah data debit atau kredit yang akan diupdate
        if ($kas == 'd') {
            $updateData = [
                'tanggal' => $tanggal,
                'debet' => $nominal,
                'credit' => '0',
                'po_id' => $po_id,
                'ket' => $ket
            ];
        } elseif ($kas == 'c') {
            $updateData = [
                'tanggal' => $tanggal,
                'debet' => '0',
                'credit' => $nominal,
                'po_id' => $po_id,
                'ket' => $ket
            ];
        } else {
            return redirect('/oprational')->with(['error' => 'Kas harus berupa "d" atau "c"']);
        }

        // Lakukan update data
        $updated = DB::table('oprationals')->where('id', $id)->update($updateData);

        if ($updated) {
            return redirect('/oprational')->with(['success' => 'Data Berhasil Diupdate']);
        } else {
            return redirect('/oprational')->with(['error' => 'Data Gagal Diupdate']);
        }
    }
    public function destroy($id)
    {
        $oprational = Oprational::find($id);
        $oprational->delete();

        return redirect('/oprational')
            ->with('success', 'Oprational berhasil dihapus!');
    }
}
