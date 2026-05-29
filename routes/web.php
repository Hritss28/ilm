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

// Comments (requires auth)
Route::post('/berita/{news}/komentar', [Public\CommentController::class, 'store'])
    ->name('comments.store')
    ->middleware('auth');

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

// Weather API (public, cached)
Route::get('/api/weather', [Public\WeatherController::class, 'index'])->name('api.weather');

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
    Route::patch('news/{news}/headline', [Admin\NewsController::class, 'toggleHeadline'])->name('news.headline');

    // Admin Profile
    Route::get('/profile', [Admin\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [Admin\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [Admin\ProfileController::class, 'updatePassword'])->name('profile.password');

    // Admin-only routes
    Route::middleware('role:admin')->group(function () {
        Route::resource('categories', Admin\CategoryController::class);
        Route::resource('advertisements', Admin\AdvertisementController::class)->except(['create', 'store', 'destroy']);
        Route::resource('pages', Admin\StaticPageController::class);

        // Modul Web
        Route::get('/web/logo',      [Admin\WebSettingController::class, 'logo'])->name('web.logo');
        Route::post('/web/logo',     [Admin\WebSettingController::class, 'saveLogo'])->name('web.logo.save');
        Route::get('/web/popup',     [Admin\WebSettingController::class, 'popup'])->name('web.popup');
        Route::post('/web/popup',    [Admin\WebSettingController::class, 'savePopup'])->name('web.popup.save');
        Route::get('/web/tema',      [Admin\WebSettingController::class, 'tema'])->name('web.tema');
        Route::post('/web/tema',     [Admin\WebSettingController::class, 'saveTema'])->name('web.tema.save');
        Route::get('/web/identitas', [Admin\WebSettingController::class, 'identitas'])->name('web.identitas');
        Route::post('/web/identitas',[Admin\WebSettingController::class, 'saveIdentitas'])->name('web.identitas.save');

        // Kata Jorok (word filter) - now dynamic
        Route::get('/kata-jorok', [Admin\KataJorokController::class, 'index'])->name('kata-jorok');
        Route::post('/kata-jorok', [Admin\KataJorokController::class, 'store'])->name('kata-jorok.store');
        Route::delete('/kata-jorok/{badWord}', [Admin\KataJorokController::class, 'destroy'])->name('kata-jorok.destroy');
    });

    // Admin + Redaktur routes
    Route::middleware('role:admin,redaktur')->group(function () {
        Route::resource('videos', Admin\VideoController::class);
        Route::resource('galleries', Admin\GalleryController::class);
        Route::resource('users', Admin\UserController::class);
        Route::resource('editorial-teams', Admin\EditorialTeamController::class)->except(['show']);

        // Info Lalin Admin
        Route::resource('info-lalin', Admin\InfoLalinController::class)->except(['show']);
    });
});

require __DIR__.'/auth.php';
