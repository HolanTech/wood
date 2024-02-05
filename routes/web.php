<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QCController;
use App\Http\Controllers\KasController;
use App\Http\Controllers\PksController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BeliController;
use App\Http\Controllers\JualController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\PoInController;
use App\Http\Controllers\ModalController;
use App\Http\Controllers\PoOutController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ExpedisiController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KasYatimController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PengrajinController;
use App\Http\Controllers\TransportController;
use App\Http\Controllers\OprationalController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::middleware(['guest:karyawan'])->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    })->name('login');
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
});
Route::post('/proseslogin', [AuthController::class, 'proseslogin']);
Route::middleware(['auth:karyawan'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/home', [DashboardController::class, 'home']);
    Route::get('/proseslogout', [AuthController::class, 'proseslogout']);

    Route::get('/modal', [ModalController::class, 'index']);
    Route::get('/modal.create', [ModalController::class, 'create']);
    Route::post('/modal.store', [ModalController::class, 'store']);
    Route::get('/modal.show{po_id}', [ModalController::class, 'show']);
    Route::get('/modal.{id}.edit', [ModalController::class, 'edit']);
    Route::put('/modal.{id}.update', [ModalController::class, 'update']);
    Route::delete('/modal.{nik}.delete', [ModalController::class, 'destroy']);

    Route::get('/oprational', [OprationalController::class, 'index']);
    Route::get('/oprational.create', [OprationalController::class, 'create']);
    Route::post('/oprational.store', [OprationalController::class, 'store']);
    Route::get('/oprational.show{po_id}', [OprationalController::class, 'show']);
    Route::get('/oprational.{id}.edit', [OprationalController::class, 'edit']);
    Route::put('/oprational.{id}.update', [OprationalController::class, 'update']);
    Route::delete('/oprational.{nik}.delete', [OprationalController::class, 'destroy']);


    Route::get('/kas', [KasController::class, 'index']);
    Route::get('/kas.create', [KasController::class, 'create']);
    Route::post('/kas.store', [KasController::class, 'store']);
    Route::get('/kas.show{id}', [KasController::class, 'show']);
    Route::get('/kas.{po_id}.edit', [KasController::class, 'edit']);
    Route::put('/kas.{id}.update', [KasController::class, 'update']);
    Route::delete('/kas.{nik}.delete', [KasController::class, 'destroy']);

    Route::get('/kasyatim', [KasYatimController::class, 'index']);
    Route::get('/kasyatim.create', [KasYatimController::class, 'create']);
    Route::post('/kasyatim.store', [KasYatimController::class, 'store']);
    Route::get('/kasyatim.{id}.edit', [KasYatimController::class, 'edit']);
    Route::put('/kasyatim.{id}.update', [KasYatimController::class, 'update']);
    Route::delete('/kasyatim.{nik}.delete', [KasYatimController::class, 'destroy']);

    Route::get('/karyawan', [KaryawanController::class, 'index']);
    Route::get('/karyawan.create', [KaryawanController::class, 'create']);
    Route::post('/karyawan.store', [KaryawanController::class, 'store']);
    Route::get('/karyawan.{nik}.edit', [KaryawanController::class, 'edit']);
    Route::post('/karyawan.{nik}.update', [KaryawanController::class, 'update']);
    Route::delete('/karyawan.{nik}.delete', [KaryawanController::class, 'destroy']);
    //edit profil
    Route::get('/editprofile', [KaryawanController::class, 'editprofile']);
    Route::post('/karyawan/{nik}/updateprofile', [KaryawanController::class, 'updateprofile']);


    Route::get('/pengrajin', [PengrajinController::class, 'index']);
    Route::get('/pengrajin.create', [PengrajinController::class, 'create']);
    Route::post('/pengrajin.store', [PengrajinController::class, 'store']);
    Route::get('/pengrajin.{id}.edit', [PengrajinController::class, 'edit']);
    Route::post('/pengrajin.{id}.update', [PengrajinController::class, 'update']);
    Route::delete('/pengrajin.{id}.delete', [PengrajinController::class, 'destroy']);

    Route::get('/pelanggan', [PelangganController::class, 'index']);
    Route::get('/pelanggan.create', [PelangganController::class, 'create']);
    Route::post('/pelanggan.store', [PelangganController::class, 'store']);
    Route::get('/pelanggan.{id}.edit', [PelangganController::class, 'edit']);
    Route::post('/pelanggan.{id}.update', [PelangganController::class, 'update']);
    Route::delete('/pelanggan.{id}.delete', [PelangganController::class, 'destroy']);

    Route::get('/expedisi', [ExpedisiController::class, 'index']);
    Route::get('/expedisi.create', [ExpedisiController::class, 'create']);
    Route::post('/expedisi.store', [ExpedisiController::class, 'store']);
    Route::get('/expedisi.{id}.edit', [ExpedisiController::class, 'edit']);
    Route::post('/expedisi.{id}.update', [ExpedisiController::class, 'update']);
    Route::delete('/expedisi.{id}.delete', [ExpedisiController::class, 'destroy']);

    Route::get('/beli', [BeliController::class, 'index']);
    Route::get('/beli.create', [BeliController::class, 'create']);
    Route::post('/beli.store', [BeliController::class, 'store']);
    Route::get('/beli.{id}.edit', [BeliController::class, 'edit']);
    Route::post('/beli.{id}.update', [BeliController::class, 'update']);
    Route::delete('/beli.{id}.delete', [BeliController::class, 'destroy']);

    Route::get('/jual', [JualController::class, 'index']);
    Route::get('/jual.create', [JualController::class, 'create']);
    Route::post('/jual.store', [JualController::class, 'store']);
    Route::get('/jual.{id}.edit', [JualController::class, 'edit']);
    Route::post('/jual.{id}.update', [JualController::class, 'update']);
    Route::delete('/jual.{id}.delete', [JualController::class, 'destroy']);


    Route::get('/transport', [TransportController::class, 'index']);
    Route::get('/transport.create', [TransportController::class, 'create']);
    Route::post('/transport.store', [TransportController::class, 'store']);
    Route::get('/transport.{id}.edit', [TransportController::class, 'edit']);
    Route::post('/transport.{id}.update', [TransportController::class, 'update']);
    Route::delete('/transport.{id}.delete', [TransportController::class, 'destroy']);

    Route::get('/qc', [QCController::class, 'index']);
    Route::get('/qc.create', [QCController::class, 'create']);
    Route::post('/qc.store', [QCController::class, 'store']);
    Route::get('/qc.{id}.edit', [QCController::class, 'edit']);
    Route::post('/qc.{id}.update', [QCController::class, 'update']);
    Route::delete('/qc.{id}.delete', [QCController::class, 'destroy']);

    Route::get('/po_in', [PoInController::class, 'index']);
    Route::get('/po_in.create', [PoInController::class, 'create']);
    Route::post('/po_in.store', [PoInController::class, 'store']);
    Route::get('/po_in.{po_in}.edit', [PoInController::class, 'edit']);
    Route::post('/po_in.{po_in}.update', [PoInController::class, 'update']);
    Route::delete('/po_in.{po_in}.delete', [PoInController::class, 'destroy']);

    Route::get('/po_out', [PoOutController::class, 'index']);
    Route::get('/po_out.create', [PoOutController::class, 'create']);
    Route::post('/po_out.store', [PoOutController::class, 'store']);
    Route::get('/po_out.{po_out}.edit', [PoOutController::class, 'edit']);
    Route::post('/po_out.{po_out}.update', [PoOutController::class, 'update']);
    Route::delete('/po_out.{po_out}.delete', [PoOutController::class, 'destroy']);

    Route::get('/project', [ProjectController::class, 'index']);
    Route::get('/project.create', [ProjectController::class, 'create']);
    Route::post('/project.store', [ProjectController::class, 'store']);
    Route::get('/project.{id}.edit', [ProjectController::class, 'edit']);
    Route::post('/project.{id}.update', [ProjectController::class, 'update']);
    Route::delete('/project.{id}.delete', [ProjectController::class, 'destroy']);

    Route::get('/pks', [PksController::class, 'index']);
    Route::get('/pks.create', [PksController::class, 'create']);
    Route::post('/pks.store', [PksController::class, 'store']);
    Route::get('/pks.{id}.show', [PksController::class, 'show']);
    Route::get('/pks.{id}.edit', [PksController::class, 'edit']);
    Route::post('/pks.{id}.update', [PksController::class, 'update']);
    Route::delete('/pks.{id}.delete', [PksController::class, 'destroy']);

    Route::get('/note', [NoteController::class, 'index']);
    Route::get('/note.create', [NoteController::class, 'create']);
    Route::post('/note.store', [NoteController::class, 'store']);
    // Route::get('/note.{id}.show', [NoteController::class, 'show']);
    Route::get('/note.{id}.edit', [NoteController::class, 'edit']);
    Route::post('/note.{id}.update', [NoteController::class, 'update']);
    Route::delete('/note.{id}.delete', [NoteController::class, 'destroy']);

    Route::get('/report', [ReportController::class, 'index']);
    Route::get('/report.show.{po_out_id}', [ReportController::class, 'show']);
    //presensi
    // Route::get('/presensi', [PresensiController::class, 'create']);
    // Route::post('/presensi/store', [PresensiController::class, 'store']);
    // //edit profil
    // Route::get('/editprofile', [PresensiController::class, 'editprofile']);
    // Route::post('/presensi/{nik}/updateprofile', [PresensiController::class, 'updateprofile']);
    // //histori
    // Route::get('/histori', [PresensiController::class, 'histori']);
    // Route::post('/gethistori', [PresensiController::class, 'gethistori']);
    // //izin
    // Route::get('/izin', [PresensiController::class, 'izin']);
    // Route::get('/buatizin', [PresensiController::class, 'buatizin']);
    // Route::post('/storeizin', [PresensiController::class, 'storeizin']);
    // Route::post('/presensi/cekpengajuanizin', [PresensiController::class, 'cekpengajuanizin']);
    // //report
    // Route::get('/report', [ReportController::class, 'index']);
    // Route::get('/createreport', [ReportController::class, 'create']);
    // Route::post('/storereport', [ReportController::class, 'store']);
});
