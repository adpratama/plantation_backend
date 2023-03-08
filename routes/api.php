<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\ProduksiController;
use App\Http\Controllers\JenisPupukController;
use App\Http\Controllers\JenisPekerjaanController;
use App\Http\Controllers\CurahHujanController;
use App\Http\Controllers\PerawatanController;
use App\Http\Controllers\TankosController;
use App\Http\Controllers\PemupukanController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// API route for Produksi
Route::group(['prefix' => '/produksi', 'middleware' => ['auth:sanctum', 'cek_admin']], function() {
    Route::get('/', [ProduksiController::class, 'index'])->name('produksi_index');
    Route::get('/show/{id}', [ProduksiController::class, 'show'])->name('produksi_show');
    Route::post('/store', [ProduksiController::class, 'store'])->name('produksi_store');
    Route::delete('/delete/{id}', [ProduksiController::class, 'destroy'])->name('produksi_delete');
});

// API route for Jenis Pupuk
Route::group(['prefix' => '/pupuk', 'middleware' => ['auth:sanctum', 'cek_admin']], function() {
    Route::get('/', [JenisPupukController::class, 'index'])->name('pupuk_index');
    Route::get('/show/{id}', [JenisPupukController::class, 'show'])->name('pupuk_show');
    Route::post('/store', [JenisPupukController::class, 'store'])->name('pupuk_store');
    Route::delete('/delete/{id}', [JenisPupukController::class, 'destroy'])->name('pupuk_delete');
});

// API route for Jenis Pekerjaan
Route::group(['prefix' => '/pekerjaan', 'middleware' => ['auth:sanctum', 'cek_admin']], function() {
    // GET
    Route::get('/', [JenisPekerjaanController::class, 'index'])->name('pekerjaan_index');
    Route::get('/show/{id}', [JenisPekerjaanController::class, 'show'])->name('pekerjaan_show');

    // POST
    Route::post('/store', [JenisPekerjaanController::class, 'store'])->name('pekerjaan_store');

    // DELETE
    Route::delete('/delete/{id}', [JenisPekerjaanController::class, 'destroy'])->name('pekerjaan_delete');
});

// Route API Admin
Route::group(['prefix' => '/admin/user', 'middleware' => ['auth:sanctum', 'cek_admin']], function () {
    Route::get('/', [UserController::class, 'index'])->name('admin_user');
    Route::get('/cari', [UserController::class, 'cari'])->name('admin_user_cari');
    Route::get('/show/{id}', [UserController::class, 'show'])->name('admin_user_show');
    Route::post('/store', [UserController::class, 'store'])->name('admin_user_store');
    Route::post('/update', [UserController::class, 'update'])->name('admin_user_update');
    Route::post('/destroy/{id}', [UserController::class, 'destroy'])->name('admin_user_destroy');
});

// route autentikasi
Route::group(['prefix' => '/akun'], function () {
    Route::post('/masuk', [UserController::class, 'login'])->name('user_masuk');
    Route::post('/daftar', [UserController::class, 'store'])->name('user_daftar');
    // cek status auth jika nuxt hot reload
    Route::get('/status', [UserController::class, 'status'])->name('user_status');
});

Route::middleware('auth:sanctum')->post('/akun/keluar', function (Request $request) {
    $user = $request->user();
    $user->tokens()->delete();
    return response()->json([
        'info' => 'Berhasil keluar aplikasi',
        'user' => $user
    ]);
});

// Route API User
Route::group(['prefix' => '/user/user', 'middleware' => ['auth:sanctum']], function () {
    // Route::get('/index', [UserController::class, 'index'])->name('user_index');
    Route::get('/show/{id}', [UserController::class, 'show'])->name('user_show');
    Route::post('/update', [UserController::class, 'update'])->name('user_update');
    Route::delete('delete/{id}', [UserController::class, 'destroy'])->name('user_delete');
    // Route::post('/verifikasi', [UserController::class, 'verifikasi'])->name('user_verifikasi');
    // Route::get('/email-verifikasi/{user_id}', [UserController::class, 'email_verifikasi'])->name('user_email_verifikasi');
});

// Route API /curah_hujan
Route::group(['prefix' => '/curah-hujan', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', [CurahHujanController::class, 'index'])->name('hujan_index');
    Route::get('/show/{id}', [CurahHujanController::class, 'show'])->name('hujan_show');
    Route::post('/store', [CurahHujanController::class, 'store'])->name('hujan_store');
    Route::delete('/delete/{id}', [CurahHujanController::class, 'destroy'])->name('hujan_delete');
});

// Route API /perawatan
Route::group(['prefix' => '/perawatan', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', [PerawatanController::class, 'index'])->name('perawatan_index');
    Route::get('/show/{id}', [PerawatanController::class, 'show'])->name('perawatan_show');
    Route::post('/store', [PerawatanController::class, 'store'])->name('perawatan_store');
    Route::delete('/delete/{id}', [PerawatanController::class, 'destroy'])->name('perawatan_delete');
});

// Route API /tankos
Route::group(['prefix' => '/tankos', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', [TankosController::class, 'index'])->name('tankos_index');
    Route::get('/show/{id}', [TankosController::class, 'show'])->name('tankos_show');
    Route::post('/store', [TankosController::class, 'store'])->name('tankos_store');
    Route::delete('/delete/{id}', [TankosController::class, 'destroy'])->name('tankos_delete');
});

// Route API /pemupukan
Route::group(['prefix' => '/pemupukan', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', [PemupukanController::class, 'index'])->name('pemupukan_index');
    Route::get('/show/{id}', [PemupukanController::class, 'show'])->name('pemupukan_show');
    Route::post('/store', [PemupukanController::class, 'store'])->name('pemupukan_store');
    Route::delete('/delete/{id}', [PemupukanController::class, 'destroy'])->name('pemupukan_delete');
});

// route laravel file manager (lfm)
Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['auth:sanctum']], function () {
     \UniSharp\LaravelFilemanager\Lfm::routes();
});

// proses email verifikasi
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/verify-success');
})->middleware(['auth', 'signed'])->name('verification.verify');


// form kirim ulang email verifikasi
Route::get('/email/verify', function () { return view('verify-email'); })->middleware('auth')->name('verification.notice');

// post kirim ulang email verifikasi
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return response()->json('Berhasil mengirim email verifikasi');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// halaman sukses kirim ulang email verifikasi
Route::get('/email/kirim-verifikasi', function () {
    $user = auth()->user();
    new Registered($user);
    return 'email terkirim';
});

// tampilan sukses verifikasi akun
Route::get('/verify-success', function () {
    return view('verify-success');
});

// post request lupa password link & token
Route::post('/forget-password', [UserController::class, 'forget_password'])->middleware('guest')->name('password.email');

// laman form reset password dengan token nya
// Route::get('/reset-password/{token}', function ($token) {
//     return view('reset-password', ['token' => $token]);
// })->middleware('guest')->name('password.reset');

// post password baru
// Route::post('/reset-password', [UserController::class, 'reset_password'])->middleware('guest')->name('password.update');

// route landing web utama
Route::group(['prefix' => '/landing'], function () {
    Route::get('/maintenance', [LandingController::class, 'maintenance'])->name('maintenance');
});


// start route user
// Route::group(['prefix' => '/user/user', 'middleware' => ['auth:sanctum']], function () {
//     Route::get('/show/{id}', [UserController::class, 'show'])->name('user_show');
//     Route::post('/verifikasi', [UserController::class, 'verifikasi'])->name('user_verifikasi');
//     Route::post('/update', [UserController::class, 'update'])->name('user_update');
//     Route::get('/email-verifikasi/{user_id}', [UserController::class, 'email_verifikasi'])->name('user_email_verifikasi');
// });


Route::group(['prefix' => '/user/dashboard','middleware' => ['auth:sanctum'/* , 'verified' */]], function () {
    Route::get('/', [UserController::class, 'user_dashboard'])->name('user_dashboard');
});

Route::group(['prefix' => '/user/email', 'middleware' => ['auth:sanctum']], function () {
    // Route::get('/pengajuan-email/{id}', [EmailController::class, 'pengajuan_email'])->name('pengajuan_email');
});


// route admin untuk crud user
// Route::group(['prefix' => '/admin/user', 'middleware' => ['auth:sanctum', 'cek_superadmin']], function ()
