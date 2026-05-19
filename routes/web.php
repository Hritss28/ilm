<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Public;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\IncrementViewCount;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [Public\HomeController::class, 'index'])->name('home');

// News
Route::get('/berita/{slug}', [Public\NewsController::class, 'show'])
    ->name('news.show')
    ->middleware(IncrementViewCount::class);
Route::get('/kategori/{slug}', [Public\NewsController::class, 'category'])->name('news.category');
Route::get('/cari', [Public\NewsController::class, 'search'])->name('news.search');

// Video
Route::get('/video', [Public\VideoController::class, 'index'])->name('video.index');
Route::get('/video/{video}', [Public\VideoController::class, 'show'])->name('video.show');

// Gallery / Potret
Route::get('/potret', [Public\GalleryController::class, 'index'])->name('gallery.index');
Route::get('/potret/{slug}', [Public\GalleryController::class, 'show'])->name('gallery.show');

// Static Pages
Route::get('/tentang-kami', [Public\StaticPageController::class, 'about'])->name('about');
Route::get('/redaksi', [Public\StaticPageController::class, 'redaksi'])->name('redaksi');

// Info Lalin
Route::get('/infolalin', [Public\NewsController::class, 'infoLalin'])->name('infolalin');

// Sitemap
Route::get('/sitemap.xml', [Public\SitemapController::class, 'index'])->name('sitemap');

// Dashboard redirect
Route::get('/dashboard', function () {
    return redirect('/admin/dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin,redaktur'])->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // News management (all authenticated roles)
    Route::resource('news', Admin\NewsController::class);
    Route::patch('news/{news}/breaking', [Admin\NewsController::class, 'toggleBreakingNews'])->name('news.breaking');
    Route::patch('news/{news}/featured', [Admin\NewsController::class, 'toggleFeatured'])->name('news.featured');

    // Admin-only routes
    Route::middleware('role:admin')->group(function () {
        Route::resource('categories', Admin\CategoryController::class);
        Route::resource('advertisements', Admin\AdvertisementController::class);
        Route::resource('pages', Admin\StaticPageController::class);

        // Modul Web
        Route::get('/web/logo', function () {
            return view('admin.web.logo');
        })->name('web.logo');
        Route::get('/web/popup', function () {
            return view('admin.web.popup');
        })->name('web.popup');
        Route::get('/web/tema', function () {
            return view('admin.web.tema');
        })->name('web.tema');
        Route::get('/web/identitas', function () {
            return view('admin.web.identitas');
        })->name('web.identitas');

        // Kata Jorok (word filter)
        Route::get('/kata-jorok', function () {
            return view('admin.kata-jorok');
        })->name('kata-jorok');
    });

    // Admin + Redaktur routes
    Route::middleware('role:admin,redaktur')->group(function () {
        Route::resource('videos', Admin\VideoController::class);
        Route::resource('galleries', Admin\GalleryController::class);
        Route::resource('users', Admin\UserController::class);

        // Info Lalin Admin
        Route::get('/info-lalin', function () {
            $articles = \App\Models\News::with(['category', 'author'])
                ->whereHas('category', fn($q) => $q->where('slug', 'lalu-lintas'))
                ->orderByDesc('created_at')
                ->paginate(15);
            return view('admin.info-lalin.index', compact('articles'));
        })->name('info-lalin.index');
        Route::get('/info-lalin/create', function () {
            return view('admin.info-lalin.create');
        })->name('info-lalin.create');
        Route::get('/info-lalin/{news}/edit', function (\App\Models\News $news) {
            return view('admin.info-lalin.edit', compact('news'));
        })->name('info-lalin.edit');
    });
});

require __DIR__.'/auth.php';
