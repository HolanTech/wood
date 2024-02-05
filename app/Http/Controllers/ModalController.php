<?php

namespace App\Http\Controllers;

use App\Models\Modal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModalController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    public function index(Request $request)
    {
        $data = DB::table('modals')->select('po_id')->groupBy('po_id')->orderBy('tanggal', 'desc')->get();
        $balance = Modal::sum('debet') - Modal::sum('credit');

        return view('modal.index', compact('balance', 'data'));
    }
    public function show(Request $request, $po_id)
    {
        $po_id = $request->po_id;
        $data = Modal::where('po_id', $po_id)->orderBy('tanggal', 'desc')->get();
        $balance = Modal::where('po_id', $po_id)->sum('debet') - Modal::where('po_id', $po_id)->sum('credit');
        // dd($po_id);
        return view('modal.show', compact('balance', 'data', 'po_id'));
    }

    public function create()
    {
        return view('modal.create');
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
            $simpan = DB::table('modals')->insert($data);
        }
        if ($kas == 'c') {
            $data = [
                'tanggal' => $tanggal,
                'debet' => '0',
                'credit' => $nominal,
                'po_id' => $po_id,
                'ket' => $ket
            ];
            $simpan = DB::table('modals')->insert($data);
        }

        if ($simpan) {
            return redirect('/modal')->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return redirect('/modal')->with(['success' => 'Data Gagal Disimpan']);
        }
    }
    public function edit($id)
    {
        $modal = Modal::find($id);

        if (!$modal) {
            return redirect('/modal')->with(['error' => 'Data tidak ditemukan']);
        }

        return view('modal.edit', compact('modal'));
    }

    public function update(Request $request, $id)
    {
        $modal = Modal::find($id);

        if (!$modal) {
            return redirect('/modal')->with(['error' => 'Data tidak ditemukan']);
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
            return redirect('/modal')->with(['error' => 'Kas harus berupa "d" atau "c"']);
        }

        // Lakukan update data
        $updated = DB::table('modals')->where('id', $id)->update($updateData);

        if ($updated) {
            return redirect('/modal')->with(['success' => 'Data Berhasil Diupdate']);
        } else {
            return redirect('/modal')->with(['error' => 'Data Gagal Diupdate']);
        }
    }
    public function destroy($id)
    {
        $modal = Modal::find($id);
        $modal->delete();

        return redirect('/modal')
            ->with('success', 'Modal berhasil dihapus!');
    }
}
