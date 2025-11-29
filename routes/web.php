<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\KomentarController;
use App\Http\Controllers\PengumpulanTugasController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

// Landing Page
Route::get('/', function () {
    return view('landing');
})->name('landing');

// Contact Route
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'login'])->name('admin.login.submit');
    
    Route::middleware([AdminMiddleware::class])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/classes', [AdminController::class, 'classes'])->name('admin.classes');
        Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
        Route::delete('/classes/{class}', [AdminController::class, 'deleteClass'])->name('admin.classes.delete');
        Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');
    });
});

// Materials Routes
Route::get('/materials', [MateriController::class, 'indexAll'])->name('kelas.materials.index');
Route::get('/materials/search', [MateriController::class, 'search'])->name('kelas.materials.search');
Route::get('/materials/load-more', [MateriController::class, 'loadMore'])->name('kelas.materials.load-more');
Route::get('/materials/{materi}', [MateriController::class, 'showAll'])->name('kelas.materials.show');

// Assignments Routes
Route::get('/assignments', [TugasController::class, 'indexAll'])->name('kelas.assignments.index');
Route::get('/assignments/search', [TugasController::class, 'search'])->name('kelas.assignments.search');
Route::get('/assignments/load-more', [TugasController::class, 'loadMore'])->name('kelas.assignments.load-more');
Route::get('/assignments/{tugas}', [TugasController::class, 'showAll'])->name('kelas.assignments.show');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

// Protected Routes
Route::middleware('auth')->group(function () {

    // Notification Routes
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotifikasiController::class, 'index'])->name('notifications.index');
        Route::post('/{id}/read', [NotifikasiController::class, 'markAsRead'])->name('notifications.markAsRead');
        Route::post('/mark-all-read', [NotifikasiController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
        Route::get('/unread-count', [NotifikasiController::class, 'getUnreadCount'])->name('notifications.unreadCount');
        Route::get('/unread-list', [NotifikasiController::class, 'getUnreadNotifications'])->name('notifications.unreadList');
    });
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    
    Route::resource('kelas', KelasController::class);
    
    Route::post('/kelas/join', [KelasController::class, 'join'])->name('kelas.join');
    Route::post('/kelas/{id}/leave', [KelasController::class, 'leave'])->name('kelas.leave');
    
    Route::prefix('kelas/{kelasId}')->group(function () {
        // Summary routes
        Route::post('/materi/{materiId}/generate-summary', [MateriController::class, 'generateSummary'])
            ->name('kelas.materi.generate-summary');
        Route::get('/materi/{materiId}/download-summary', [MateriController::class, 'downloadSummary'])
            ->name('kelas.materi.download-summary');

        // Komentar Routes untuk Tugas
        Route::post('/tugas/{tugasId}/komentar', [KomentarController::class, 'store'])->name('kelas.tugas.komentar.store');
        Route::delete('/tugas/{tugasId}/komentar/{komentarId}', [KomentarController::class, 'destroy'])->name('kelas.tugas.komentar.destroy');

        // Komentar Routes untuk Materi
        Route::post('/materi/{materiId}/komentar', [KomentarController::class, 'storeMateri'])->name('kelas.materi.komentar.store');
        Route::delete('/materi/{materiId}/komentar/{komentarId}', [KomentarController::class, 'destroyMateri'])->name('kelas.materi.komentar.destroy');

        // Nilai Routes
        Route::post('/tugas/{tugasId}/pengumpulan/{pengumpulanId}/nilai', [NilaiController::class, 'store'])->name('kelas.tugas.pengumpulan.nilai');

        // Materials Routes
        Route::get('/materi/create', [MateriController::class, 'create'])->name('kelas.materi.create');
        Route::post('/materi', [MateriController::class, 'store'])->name('kelas.materi.store');
        Route::get('/materi/{materiId}', [MateriController::class, 'show'])->name('kelas.materi.show');
        Route::get('/materi/{materiId}/edit', [MateriController::class, 'edit'])->name('kelas.materi.edit');
        Route::put('/materi/{materiId}', [MateriController::class, 'update'])->name('kelas.materi.update');
        Route::delete('/materi/{materiId}', [MateriController::class, 'destroy'])->name('kelas.materi.destroy');
        
        // Assignments Routes  
        Route::get('/tugas/create', [TugasController::class, 'create'])->name('kelas.tugas.create');
        Route::post('/tugas', [TugasController::class, 'store'])->name('kelas.tugas.store');
        Route::get('/tugas/{tugasId}', [TugasController::class, 'show'])->name('kelas.tugas.show');
        Route::get('/tugas/{tugasId}/edit', [TugasController::class, 'edit'])->name('kelas.tugas.edit');
        Route::put('/tugas/{tugasId}', [TugasController::class, 'update'])->name('kelas.tugas.update');
        Route::delete('/tugas/{tugasId}', [TugasController::class, 'destroy'])->name('kelas.tugas.destroy');
        
        // Pengumpulan Tugas Routes
        Route::post('/tugas/{tugasId}/pengumpulan', [PengumpulanTugasController::class, 'store'])->name('kelas.tugas.pengumpulan.store');
        Route::put('/tugas/{tugasId}/pengumpulan/{pengumpulanId}', [PengumpulanTugasController::class, 'update'])->name('kelas.tugas.pengumpulan.update');
        Route::delete('/tugas/{tugasId}/pengumpulan/{pengumpulanId}', [PengumpulanTugasController::class, 'destroy'])->name('kelas.tugas.pengumpulan.destroy');
    });
    
    // Global Materials Routes
    Route::get('/materials', [MateriController::class, 'indexAll'])->name('kelas.materials.index');
    Route::get('/materials/{materiId}', [MateriController::class, 'showAll'])->name('kelas.materials.show');

    // Global Assignments Routes
    Route::get('/assignments', [TugasController::class, 'indexAll'])->name('kelas.assignments.index');
    Route::get('/assignments/{tugasId}', [TugasController::class, 'showAll'])->name('kelas.assignments.show');
    
    // Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// Test Route
Route::get('/test-csrf', function() {
    return response()->json([
        'csrf_token' => csrf_token(),
        'session_id' => session()->getId()
    ]);
});