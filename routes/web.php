<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CoaController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\JurnalController;
use App\Http\Controllers\BukuBesarController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\StokMasukController;
use App\Http\Controllers\StokKeluarController;
use App\Http\Controllers\KartuStokController;
use App\Http\Controllers\BantuanController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\OfferingController;
use App\Http\Controllers\SuratJalanController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;

// Route untuk favicon (harus di luar middleware auth)
Route::get('/favicon.ico', function () {
    $company = \App\Models\CompanyInfo::getInfo();
    if ($company->logo && \Illuminate\Support\Facades\Storage::disk('public')->exists($company->logo)) {
        $file = \Illuminate\Support\Facades\Storage::disk('public')->get($company->logo);
        $mimeType = \Illuminate\Support\Facades\Storage::disk('public')->mimeType($company->logo);
        return response($file, 200)->header('Content-Type', $mimeType);
    }
    // Fallback: return 204 No Content jika tidak ada logo
    return response('', 204);
})->name('favicon');

// Welcome page (public access)
Route::get('/', function () {
    return view('welcome');
});

// Semua routes aplikasi memerlukan authentication
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Routes untuk COA (Chart of Accounts)
    Route::resource('coa', CoaController::class);

    // Routes untuk Periode
    Route::resource('periode', PeriodeController::class);
    Route::post('periode/{periode}/close', [PeriodeController::class, 'close'])->name('periode.close');
    Route::post('periode/{periode}/reopen', [PeriodeController::class, 'reopen'])->name('periode.reopen');

    // Routes untuk Jurnal
    // Route khusus harus didefinisikan SEBELUM resource route agar tidak tertangkap oleh {jurnal}
    Route::get('jurnal/upload-csv', [JurnalController::class, 'showUploadForm'])->name('jurnal.upload-csv');
    Route::post('jurnal/import-csv', [JurnalController::class, 'importCsv'])->name('jurnal.import-csv');
    Route::post('jurnal/{jurnal}/post', [JurnalController::class, 'post'])->name('jurnal.post');
    Route::post('jurnal/{jurnal}/duplicate', [JurnalController::class, 'duplicate'])->name('jurnal.duplicate');
    Route::put('jurnal/bulk-update', [JurnalController::class, 'bulkUpdate'])->name('jurnal.bulk-update');
    Route::resource('jurnal', JurnalController::class);

    // Routes untuk Buku Besar
    Route::get('buku-besar', [BukuBesarController::class, 'index'])->name('buku-besar.index');
    Route::get('buku-besar/show', [BukuBesarController::class, 'show'])->name('buku-besar.show');
    Route::get('buku-besar/export-pdf', [BukuBesarController::class, 'exportPdf'])->name('buku-besar.export-pdf');

    // Routes untuk Laporan
    Route::get('laporan/laba-rugi', [LaporanController::class, 'labaRugi'])->name('laporan.laba-rugi');
    Route::get('laporan/laba-rugi/export-pdf', [LaporanController::class, 'exportLabaRugiPdf'])->name('laporan.laba-rugi.export-pdf');
    Route::get('laporan/neraca', [LaporanController::class, 'neraca'])->name('laporan.neraca');
    Route::get('laporan/neraca/export-pdf', [LaporanController::class, 'exportNeracaPdf'])->name('laporan.neraca.export-pdf');
    Route::get('laporan/arus-kas', [LaporanController::class, 'arusKas'])->name('laporan.arus-kas');

    // Routes untuk Inventori
    Route::resource('barang', BarangController::class);
    Route::resource('stok-masuk', StokMasukController::class);
    Route::resource('stok-keluar', StokKeluarController::class);
    Route::get('kartu-stok', [KartuStokController::class, 'index'])->name('kartu-stok.index');
    Route::get('kartu-stok/{barang}', [KartuStokController::class, 'show'])->name('kartu-stok.show');

    // Routes untuk Bantuan
    Route::get('bantuan', [BantuanController::class, 'index'])->name('bantuan.index');
    Route::get('bantuan/{slug}', [BantuanController::class, 'show'])->name('bantuan.show');

    // Routes untuk About (Informasi Perusahaan)
    Route::get('about', [AboutController::class, 'index'])->name('about.index');
    Route::put('about', [AboutController::class, 'update'])->name('about.update');

    // Routes untuk Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Routes untuk Customer dan Supplier
    Route::resource('customer', CustomerController::class);
    Route::resource('supplier', SupplierController::class);

    // Routes untuk Invoice, Offering, Surat Jalan
    Route::resource('invoice', InvoiceController::class);
    Route::get('invoice/{invoice}/v2', [InvoiceController::class, 'showV2'])->name('invoice.show-v2');
    Route::post('invoice/{invoice}/duplicate', [InvoiceController::class, 'duplicate'])->name('invoice.duplicate');
    Route::get('invoice/{invoice}/export-pdf', [InvoiceController::class, 'exportPdf'])->name('invoice.export-pdf');
    Route::get('invoice/{invoice}/export-excel', [InvoiceController::class, 'exportExcel'])->name('invoice.export-excel');
    Route::get('invoice/{invoice}/export-pdf-v2', [InvoiceController::class, 'exportPdfV2'])->name('invoice.export-pdf-v2');
    Route::get('invoice/{invoice}/export-excel-v2', [InvoiceController::class, 'exportExcelV2'])->name('invoice.export-excel-v2');
    Route::resource('offering', OfferingController::class);
    Route::resource('surat-jalan', SuratJalanController::class);
});
