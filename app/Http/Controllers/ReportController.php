<?php

namespace App\Http\Controllers;

use App\Models\Jual;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ReportController extends Controller
{
    public function index()
    {
        $data = Jual::select('po_out_id', DB::raw('MAX(tanggal) as max_tanggal'))
            ->groupBy('po_out_id')
            ->orderBy('max_tanggal', 'desc')
            ->get();

        return view('report.index', compact('data'));
    }

    public function show(Request $request, $po_out_id)
    {
        try {
            $data = Jual::where('juals.po_out_id', $request->po_out_id)
                ->join('po_ins', 'juals.po_in_id', '=', 'po_ins.po_in')
                ->join('po_outs', 'juals.po_out_id', '=', 'po_outs.po_out')
                ->join('transports', 'juals.po_out_id', '=', 'transports.po_out_id')
                ->join('pengrajins', 'po_outs.pengrajin_id', '=', 'pengrajins.id')
                ->join('expedisis', 'transports.expedisi_id', '=', 'expedisis.id')
                ->join('pelanggans', 'juals.customer_id', '=', 'pelanggans.id')
                ->join('belis', 'juals.po_out_id', '=', 'belis.po_out_id')
                ->select(
                    'juals.id',
                    'juals.tanggal',
                    'juals.po_in_id',
                    'juals.order',
                    'juals.qty',
                    'juals.po_out_id',
                    'juals.harga',
                    'juals.transport',
                    'juals.oprational',
                    'juals.yatim',
                    'juals.laba',
                    'juals.hasil',
                    'juals.hargaqc',
                    'po_ins.harga as seharusnya',
                    'expedisis.nama as expedisi_nama',
                    'pengrajins.nama as pengrajin_nama',
                    'pelanggans.nama as customer_nama',
                    DB::raw('SUM(belis.harga) as harga_beli')
                )
                ->groupBy(
                    'juals.id',
                    'juals.tanggal',
                    'juals.po_in_id',
                    'juals.order',
                    'juals.qty',
                    'juals.po_out_id',
                    'juals.harga',
                    'juals.transport',
                    'juals.oprational',
                    'juals.yatim',
                    'juals.laba',
                    'juals.hasil',
                    'juals.hargaqc',
                    'po_ins.harga',
                    'expedisis.nama',
                    'pengrajins.nama',
                    'pelanggans.nama',

                )
                ->orderBy('tanggal')
                ->get();

            $karyawan = Karyawan::where('jabatan', '!=', 'Pihak 1')->count();

            return view('report.show', compact('data', 'karyawan'));
        } catch (ModelNotFoundException $e) {
            return abort(404);
        }
    }
}
