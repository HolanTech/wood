<?php

namespace App\Http\Controllers;

use App\Models\Kas;
use App\Models\Modal;
use App\Models\KasYatim;
use App\Models\Oprational;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        // dd($nik);
        return view('dashboard.dashboard');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function dashboardadmin()
    {
        $hariini = date("Y-m-d");
        $rekappresensi = DB::table('presensis')
            ->selectRaw('COUNT(nik) as jmlhadir, SUM(IF(jam_in > "07:00",1,0)) as jmltelat')
            ->where('tgl_presensi', $hariini)
            ->first();
        $rekapizin = DB::table('izins')
            ->selectRaw('SUM(CASE WHEN status="i" THEN 1 ELSE 0 END) as jmlizin, SUM(CASE WHEN status="s" THEN 1 ELSE 0 END) as jmlsakit')
            ->where('tgl_izin', $hariini)
            ->where('approved', 1)
            ->first();

        return view('dashboard.dashboardadmin', compact('rekappresensi', 'rekapizin'));
    }
    public function home(Request $request, Modal $modalModel, KasYatim $yatimModel, Kas $kasModel, Oprational $OprationalModel)
    {
        // Mendapatkan tahun saat ini
        $currentYear = now()->year;

        // Kasmodal Data
        $monthmodals = range(1, 12);
        $monthmodalLabels = [];
        $debetmodalTotals = [];
        $creditmodalTotals = [];
        $balancemodals = [];
        $currentBalancemodal = 0;

        // Looping untuk setiap bulan
        foreach ($monthmodals as $monthmodal) {
            // Mengambil data dari model "Modal" untuk bulan dan tahun tertentu
            $monthmodalData = $modalModel
                ->select(DB::raw('SUM(debet) as total_debet'), DB::raw('SUM(credit) as total_credit'))
                ->whereMonth('created_at', $monthmodal)
                ->whereYear('created_at', $currentYear) // Memfilter berdasarkan tahun saat ini
                ->first();

            // Menghitung total debet dan kredit
            $debetmodalTotals[] = $monthmodalData->total_debet ?? 0;
            $creditmodalTotals[] = $monthmodalData->total_credit ?? 0;

            // Menghitung saldo terkini untuk "Kasmodal"
            $currentBalancemodal += $debetmodalTotals[$monthmodal - 1] - $creditmodalTotals[$monthmodal - 1];
            $balancemodals[] = $currentBalancemodal;

            // Menyimpan label bulan untuk digunakan di frontend
            $monthmodalLabels[] = Carbon::create()->month($monthmodal)->format('F');
        }

        // Kasyatim Data
        $monthyatims = range(1, 12);
        $monthyatimLabels = [];
        $debetyatimTotals = [];
        $credityatimTotals = [];
        $balanceyatims = [];
        $currentBalanceyatim = 0;

        // Looping untuk setiap bulan
        foreach ($monthyatims as $monthyatim) {
            // Mengambil data dari model "KasYatim" untuk bulan dan tahun tertentu
            $yatimModel = KasYatim::select(DB::raw('SUM(debet) as total_debet'), DB::raw('SUM(credit) as total_credit'))
                ->whereMonth('tanggal', $monthyatim)
                ->whereYear('tanggal', $currentYear) // Memfilter berdasarkan tahun saat ini
                ->get();

            // Menghitung total debet dan kredit
            $monthyatimData = $yatimModel->first();
            $debetyatimTotals[] = $monthyatimData->total_debet ?? 0;
            $credityatimTotals[] = $monthyatimData->total_credit ?? 0;

            // Menghitung saldo terkini untuk "Kasyatim"
            $currentBalanceyatim += $debetyatimTotals[$monthyatim - 1] - $credityatimTotals[$monthyatim - 1];
            $balanceyatims[] = $currentBalanceyatim;

            // Menyimpan label bulan untuk digunakan di frontend
            $monthyatimLabels[] = Carbon::create()->month($monthyatim)->format('F');
        }

        // Kaskas Data
        $monthkass = range(1, 12);
        $monthkasLabels = [];
        $debetkasTotals = [];
        $creditkasTotals = [];
        $balancekass = [];
        $currentBalancekas = 0;

        // Looping untuk setiap bulan
        foreach ($monthkass as $monthkas) {
            // Mengambil data dari model "Kas" untuk bulan dan tahun tertentu
            $kasModel = Kas::select(DB::raw('SUM(debet) as total_debet'), DB::raw('SUM(credit) as total_credit'))
                ->whereMonth('tanggal', $monthkas)
                ->whereYear('tanggal', $currentYear) // Memfilter berdasarkan tahun saat ini
                ->get();

            // Menghitung total debet dan kredit
            $monthkasData = $kasModel->first();
            $debetkasTotals[] = $monthkasData->total_debet ?? 0;
            $creditkasTotals[] = $monthkasData->total_credit ?? 0;

            // Menghitung saldo terkini untuk "Kaskas"
            $currentBalancekas += $debetkasTotals[$monthkas - 1] - $creditkasTotals[$monthkas - 1];
            $balancekass[] = $currentBalancekas;

            // Menyimpan label bulan untuk digunakan di frontend
            $monthkasLabels[] = Carbon::create()->month($monthkas)->format('F');
        }
        // Kasoprational Data
        $monthoprationals = range(1, 12);
        $monthoprationalLabels = [];
        $debetoprationalTotals = [];
        $creditoprationalTotals = [];
        $balanceoprationals = [];
        $currentBalanceoprational = 0;

        // Looping untuk setiap bulan
        foreach ($monthoprationals as $monthoprational) {
            // Mengambil data dari model "oprational" untuk bulan dan tahun tertentu
            $oprationalModel = Oprational::select(DB::raw('SUM(debet) as total_debet'), DB::raw('SUM(credit) as total_credit'))
                ->whereMonth('tanggal', $monthoprational)
                ->whereYear('tanggal', $currentYear) // Memfilter berdasarkan tahun saat ini
                ->get();

            // Menghitung total debet dan kredit
            $monthoprationalData = $oprationalModel->first();
            $debetoprationalTotals[] = $monthoprationalData->total_debet ?? 0;
            $creditoprationalTotals[] = $monthoprationalData->total_credit ?? 0;

            // Menghitung saldo terkini untuk "oprationaloprational"
            $currentBalanceoprational += $debetoprationalTotals[$monthoprational - 1] - $creditoprationalTotals[$monthoprational - 1];
            $balanceoprationals[] = $currentBalanceoprational;

            // Menyimpan label bulan untuk digunakan di frontend
            $monthoprationalLabels[] = Carbon::create()->month($monthoprational)->format('F');
        }

        // Mengembalikan view dengan data yang sudah dihitung
        return view('dashboard.home', compact(
            'monthmodalLabels',
            'debetmodalTotals',
            'creditmodalTotals',
            'balancemodals',
            'monthyatimLabels',
            'debetyatimTotals',
            'credityatimTotals',
            'balanceyatims',
            'monthkasLabels',
            'debetkasTotals',
            'creditkasTotals',
            'balancekass',
            'monthoprationalLabels',
            'debetoprationalTotals',
            'creditoprationalTotals',
            'balanceoprationals'
        ));
    }



    /**
     * Store a newly created resource in storage.
     */
}
