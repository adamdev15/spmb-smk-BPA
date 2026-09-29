<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\CasisController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\MasterJurusanController;
use App\Http\Controllers\Admin\ProgramKeunggulanController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\WhatsAppWebhookController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes - SPMB SMK Bhakti Praja Adiwerna
|--------------------------------------------------------------------------
*/

// Public Landing Page
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/preview-hasil-pengumuman', [LandingController::class, 'previewHasilPengumuman'])->name('preview.hasil.pengumuman');

// Region API Routes
Route::prefix('api/region')->group(function () {
    Route::get('/provinsi', [\App\Http\Controllers\Api\RegionController::class, 'getProvinsi'])->name('api.region.provinsi');
    Route::get('/kabupaten/{id_provinsi}', [\App\Http\Controllers\Api\RegionController::class, 'getKabupaten'])->name('api.region.kabupaten')->where('id_provinsi', '.*');
    Route::get('/kecamatan/{id_kabupaten}', [\App\Http\Controllers\Api\RegionController::class, 'getKecamatan'])->name('api.region.kecamatan')->where('id_kabupaten', '.*');
    Route::get('/kelurahan/{id_kecamatan}', [\App\Http\Controllers\Api\RegionController::class, 'getKelurahan'])->name('api.region.kelurahan')->where('id_kecamatan', '.*');
});

// Student Online Registration
Route::get('/pendaftaran', [RegistrationController::class, 'index'])->name('pendaftaran');
Route::post('/pendaftaran', [RegistrationController::class, 'store'])->name('pendaftaran.store');

// Midtrans Webhook Payment Notification
Route::post('/payment/notification', [PaymentController::class, 'notification'])->name('payment.notification');

// WhatsApp (Meta WABA) Webhook — harus exclude CSRF
// GET: verifikasi dari Meta saat setup webhook
Route::get('/webhook/whatsapp', [WhatsAppWebhookController::class, 'verify'])->name('webhook.whatsapp.verify');
// POST: event masuk dari Meta (status pesan, pesan masuk)
Route::post('/webhook/whatsapp', [WhatsAppWebhookController::class, 'handle'])->name('webhook.whatsapp.handle');

// Student Login & Dashboard Routes
Route::get('/login-siswa', [\App\Http\Controllers\CasisLoginController::class, 'showLoginForm'])->name('casis.login');
Route::post('/login-siswa', [\App\Http\Controllers\CasisLoginController::class, 'login'])->name('casis.login.post');

Route::middleware([\App\Http\Middleware\EnsureCasisLoggedIn::class])->group(function () {
    Route::get('/siswa/dashboard', [\App\Http\Controllers\CasisLoginController::class, 'dashboard'])->name('casis.dashboard');
    Route::post('/siswa/logout', [\App\Http\Controllers\CasisLoginController::class, 'logout'])->name('casis.logout');

    // PDF Cards & Documents
    Route::get('/siswa/print-kartu', [\App\Http\Controllers\CasisLoginController::class, 'printKartu'])->name('casis.print.kartu');
    Route::get('/siswa/print-rekap', [\App\Http\Controllers\CasisLoginController::class, 'printRekap'])->name('casis.print.rekap');
    Route::get('/siswa/print-formulir', [\App\Http\Controllers\CasisLoginController::class, 'printFormulir'])->name('casis.print.formulir');
    Route::get('/siswa/print-kwitansi', [\App\Http\Controllers\CasisLoginController::class, 'printKwitansi'])->name('casis.print.kwitansi');
    Route::get('/siswa/print-pengumuman', [\App\Http\Controllers\CasisLoginController::class, 'printPengumuman'])->name('casis.print.pengumuman');
    Route::post('/siswa/upload-berkas', [\App\Http\Controllers\CasisLoginController::class, 'uploadBerkas'])->name('casis.upload.berkas');

    // Midtrans Re-enrollment Checkout
    Route::post('/siswa/bayar', [PaymentController::class, 'createPayment'])->name('casis.pay');
});

// User Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin & Officer Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::middleware(['role:admin,petugas'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/chart-data', [DashboardController::class, 'chartData'])->name('admin.dashboard.chartData');
        Route::post('/admin/notifications/read-all', function () {
            Auth::user()->unreadNotifications->markAsRead();
            return back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
        })->name('admin.notifications.readAll');
        Route::post('/admin/verify/{id}', [DashboardController::class, 'verify'])->name('admin.verify');

        Route::middleware(['role:admin'])->group(function () {
            Route::post('/admin/verify-bulk', [DashboardController::class, 'bulkVerify'])->name('admin.verify-bulk');
            Route::post('/admin/unverify/{id}', [DashboardController::class, 'unverify'])->name('admin.unverify');
        });

        Route::get('/admin/casis', [CasisController::class, 'index'])->name('admin.casis.index');
        Route::get('/admin/casis/export', [CasisController::class, 'export'])->name('admin.casis.export');
        Route::get('/admin/casis/search/api', [CasisController::class, 'apiSearch'])->name('admin.casis.api_search');

        Route::get('/admin/pembayaran', [PembayaranController::class, 'index'])->name('admin.pembayaran.index');
        Route::get('/admin/pembayaran/export', [PembayaranController::class, 'export'])->name('admin.pembayaran.export');
        Route::get('/admin/pembayaran/{id}', [PembayaranController::class, 'show'])->name('admin.pembayaran.show');
        Route::get('/admin/pembayaran/{id}/print-kwitansi', [PembayaranController::class, 'printKwitansi'])->name('admin.pembayaran.print_kwitansi');
        Route::post('/admin/pembayaran/{id}/reminder', [PembayaranController::class, 'reminder'])->name('admin.pembayaran.reminder');
        Route::post('/admin/pembayaran/{id}/process', [PembayaranController::class, 'processPayment'])->name('admin.pembayaran.process');

        // FIXED ROUTE ORDER: Specific routes before wildcard parameter {casis}
        Route::middleware(['role:admin'])->group(function () {
            Route::get('/admin/casis/create', [CasisController::class, 'create'])->name('admin.casis.create');
            Route::post('/admin/casis', [CasisController::class, 'store'])->name('admin.casis.store');
        });

        Route::get('/admin/casis/{casis}', [CasisController::class, 'show'])->name('admin.casis.show');
        Route::post('/admin/casis/{id}/reminder', [CasisController::class, 'sendReminder'])->name('admin.casis.reminder');

        Route::middleware(['role:admin'])->group(function () {
            Route::get('/admin/casis/{casis}/edit', [CasisController::class, 'edit'])->name('admin.casis.edit');
            Route::put('/admin/casis/{casis}', [CasisController::class, 'update'])->name('admin.casis.update');
            Route::delete('/admin/casis/{casis}', [CasisController::class, 'destroy'])->name('admin.casis.destroy');
            Route::resource('admin/users', \App\Http\Controllers\Admin\UserController::class)->names('admin.users');
            Route::get('/admin/settings', [SettingController::class , 'index'])->name('admin.settings');
            Route::post('/admin/settings', [SettingController::class , 'update'])->name('admin.settings.update');
            Route::post('/admin/settings/test-whatsapp', [SettingController::class , 'testWhatsapp'])->name('admin.settings.test-whatsapp');

            Route::get('/admin/nilai', [\App\Http\Controllers\Admin\NilaiController::class , 'index'])->name('admin.nilai.index');
            Route::get('/admin/nilai/download', [\App\Http\Controllers\Admin\NilaiController::class , 'download'])->name('admin.nilai.download');
            
            // Selection & Re-enrollment Status Updates
            Route::post('/admin/casis/{id}/selection', [CasisController::class, 'updateSelection'])->name('admin.casis.selection');
            Route::post('/admin/casis/{id}/update-kelulusan', [CasisController::class, 'updateKelulusan'])->name('admin.casis.updateKelulusan');
            Route::post('/admin/casis/bulk-update-kelulusan', [CasisController::class, 'bulkUpdateKelulusan'])->name('admin.casis.bulkUpdateKelulusan');
            Route::post('/admin/casis/{id}/daftar-ulang', [CasisController::class, 'updateDaftarUlang'])->name('admin.casis.daftar-ulang');

            // Admin PDF Print Routes
            Route::get('/admin/casis/{id}/print-kartu', [CasisController::class, 'printKartu'])->name('admin.casis.print.kartu');
            Route::get('/admin/casis/{id}/print-formulir', [CasisController::class, 'printFormulir'])->name('admin.casis.print.formulir');
            Route::get('/admin/casis/{id}/print-pengumuman', [CasisController::class, 'printPengumuman'])->name('admin.casis.print.pengumuman');

            // Master Data CRUD Routes
            Route::resource('admin/jurusans', MasterJurusanController::class)->names('admin.jurusans');
            Route::resource('admin/program-keunggulan', ProgramKeunggulanController::class)->names('admin.program-keunggulan');
            Route::resource('admin/jadwals', JadwalController::class)->names('admin.jadwals');
            Route::post('admin/tahun-ajarans', [JadwalController::class, 'storeTahunAjaran'])->name('admin.tahun-ajarans.store');
            
            // Admin Biaya
            Route::post('admin/biaya/jenis', [\App\Http\Controllers\Admin\BiayaController::class, 'storeJenis'])->name('admin.biaya.store_jenis');
            Route::resource('admin/biaya', \App\Http\Controllers\Admin\BiayaController::class)->names('admin.biaya')->except(['create', 'show', 'edit']);
        });
    });
});
require __DIR__ . '/auth.php';
