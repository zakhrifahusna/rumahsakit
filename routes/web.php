<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AntrianController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DaftardokterController;
use App\Http\Controllers\DatapasienController;
use App\Http\Controllers\DatauserController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\InformasiController;
use App\Http\Controllers\JadwalpoliklinikController;
use App\Http\Controllers\KepalaRsController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\PoliklinikController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RatingController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Route root untuk mengarahkan ke halaman login
Route::get('/', [LoginController::class, 'showLoginForm'])->name('root');

// Route untuk dashboard
Route::get('dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

// Route untuk login
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);

// Route untuk logout
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Route untuk register
Route::get('register', [LoginController::class, 'showRegisterForm'])->name('register');
Route::post('register', [LoginController::class, 'register']);

//Dashboard
// Route untuk admin
Route::get('/dashboard-admin', [AdminController::class, 'index'])->name('dashboard-admin');
// Route untuk petugas
Route::get('/dashboard-petugas', [PetugasController::class, 'index'])->name('dashboard-petugas');
// Route untuk kepala rumah sakit
Route::get('/dashboard-kepala_rs', [KepalaRsController::class, 'index'])->name('dashboard-kepala_rs');
// Route untuk pasien
Route::get('/dashboard-pasien', [PasienController::class, 'index'])->name('dashboard-pasien');

//Polikinik
Route::get('/poliklinik/create', [PoliklinikController::class, 'create'])->name('poliklinik.create');
Route::post('/poliklinik/add', [PoliklinikController::class, 'add'])->name('poliklinik.add');
Route::get('/poliklinik', [PoliklinikController::class, 'index'])->name('poliklinik.index');
Route::get('/poliklinik/edit/{id}', [PoliklinikController::class, 'edit'])->name('poliklinik.edit');
Route::put('/poliklinik/update/{id}', [PoliklinikController::class, 'update'])->name('poliklinik.update');
Route::delete('/poliklinik/{id}', [PoliklinikController::class, 'destroy'])->name('poliklinik.destroy');


//Dokter
Route::get('/dokter', [DokterController::class, 'index'])->name('dokter.index');
Route::get('/dokter/create', [DokterController::class, 'create'])->name('dokter.create');
Route::post('/dokter/add', [DokterController::class, 'add'])->name('dokter.add');
Route::delete('/dokter/{id}', [DokterController::class, 'destroy'])->name('dokter.destroy');
Route::get('/dokter/edit/{id}', [DokterController::class, 'edit'])->name('dokter.edit');
Route::put('/dokter/update/{id}', [DokterController::class, 'update'])->name('dokter.update');
Route::get('/dokter/{id}', [DokterController::class, 'show'])->name('dokter.show');
Route::get('/daftar-dokter', [DaftardokterController::class, 'index'])->name('daftar_dokter.index');

//Jadwal Poliklinik
Route::get('/jadwalpoliklinik', [JadwalpoliklinikController::class, 'index'])->name('jadwalpoliklinik.index');
Route::get('/jadwalpoliklinik/create', [JadwalpoliklinikController::class, 'create'])->name('jadwalpoliklinik.create');
Route::post('/jadwalpoliklinik/add', [JadwalpoliklinikController::class, 'add'])->name('jadwalpoliklinik.add');
Route::get('/jadwalpoliklinik/{id}/edit', [JadwalpoliklinikController::class, 'edit'])->name('jadwalpoliklinik.edit');
Route::put('/jadwalpoliklinik/update/{id}', [JadwalpoliklinikController::class, 'update'])->name('jadwalpoliklinik.update');
Route::delete('/jadwalpoliklinik/{id}', [JadwalpoliklinikController::class, 'destroy'])->name('jadwalpoliklinik.destroy');
Route::get('/jadwalpoliklinik/pdf', [JadwalpoliklinikController::class, 'generateLaporanJadwalPoliklinik'])->name('laporan.jadwalpoliklinik');
Route::get('/jadwalpoliklinik/excel', [JadwalpoliklinikController::class, 'generateLaporanExcel'])->name('jadwalpoliklinik.excel');
Route::get('/getDokter/{poliklinikID}', [JadwalpoliklinikController::class, 'show'])->name('getDokter');
Route::get('/getDokterByPoliklinik/{id}', [JadwalpoliklinikController::class, 'getDokterByPoliklinik']);

//Pendaftaran
Route::get('/pendaftaran', [PendaftaranController::class, 'index'])->name('pendaftaran.index');
Route::post('/pendaftaran/store', [PendaftaranController::class, 'store'])->name('pendaftaran.store');

//Antrian
Route::get('/antrianpendaftaran', [AntrianController::class, 'index'])->name('antrian.index');
Route::get('/antrianpendaftaran/pasien', [AntrianController::class, 'index2'])->name('antrian.index2');
Route::get('/generate-antrian/{id}', [AntrianController::class, 'generateAntrian'])->name('generate.antrian');
Route::get('/generate-laporan-antrian', [AntrianController::class, 'generateLaporanAntrian'])->name('laporan.antrian');
Route::get('/excel/generate-laporan-antrian', [AntrianController::class, 'generateLaporanExcel'])->name('antrian.excel');
Route::get('/generate-laporan-pasien', [AntrianController::class, 'generateLaporanPasien'])->name('riwayat.pasien');


//User
Route::get('/user', [DatauserController::class, 'index'])->name('user.index');
Route::get('/user/create', [DatauserController::class, 'create'])->name('user.create');
Route::post('/user/add', [DatauserController::class, 'add'])->name('user.add');
Route::get('/user/{id}/edit', [DatauserController::class, 'edit'])->name('user.edit');
Route::put('/user/{id}', [DatauserController::class, 'update'])->name('user.update');
Route::delete('/user/{id}', [DatauserController::class, 'destroy'])->name('user.destroy');

//Pasien
// Route::get('/datapribadi/{id}', [DatapasienController::class, 'show'])->name('pasien.show');
Route::middleware('auth')->group(function () {
    Route::get('/datapribadi/{id}', [DatapasienController::class, 'show'])->name('pasien.show');
    Route::get('/datapribadi/{id}/edit', [DatapasienController::class, 'edit'])->name('pasien.edit');
    Route::put('/datapribadi/{id}', [DatapasienController::class, 'update'])->name('pasien.update');
});
Route::get('/datapasien', [DatapasienController::class, 'index'])->name('pasien.index');
Route::delete('/datapasien/{id}', [DatapasienController::class, 'destroy'])->name('pasien.destroy');
Route::get('/datapasien/pdf', [DatapasienController::class, 'generateLaporandatapasien'])->name('laporan.pasien');
Route::get('/datapasien/excel', [DatapasienController::class, 'generateLaporanExcel'])->name('pasien.excel');


//Profile
// Rute untuk menampilkan halaman profil pengguna yang sedang login
Route::get('/profile', [ProfileController::class, 'index'])->name('user.profile');
// Rute untuk memperbarui profil pengguna
Route::put('/profile/update/{id}', [ProfileController::class, 'update'])->name('profile.update');

Route::middleware(['redirect.if.authenticated'])->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    // Tambahkan rute lain yang ingin Anda lindungi
});

//Informasi
Route::get('/informasi', [InformasiController::class, 'index'])->name('informasi.index');
Route::post('/informasi/store', [InformasiController::class, 'store'])->name('informasi.store');
Route::get('/informasi/create', [InformasiController::class, 'create'])->name('informasi.create');
Route::get('/informasi/{id}/edit', [InformasiController::class, 'edit'])->name('informasi.edit');
Route::put('/informasi/{id}', [InformasiController::class, 'update'])->name('informasi.update');
Route::delete('/informasi/{id}', [InformasiController::class, 'destroy'])->name('informasi.destroy');
Route::get('/informasi/{id}', [InformasiController::class, 'show'])->name('informasi.show');

//Rating
Route::post('/beri-nilai', [RatingController::class, 'store'])->name('rating.store');
