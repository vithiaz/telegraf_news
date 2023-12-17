<?php

use App\Livewire\Base\Gallery;
use App\Livewire\Base\Category;
use App\Livewire\Base\Homepage;
use App\Livewire\Base\NewsPage;
use App\Livewire\Base\NewsDetail;
use App\Livewire\Base\SearchPage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Livewire\Base\CategoryRedirect;
use App\Livewire\Admin\AddNews as AdminAddNews;
use App\Livewire\Admin\Category as AdminCategory;
use App\Livewire\Admin\EditNews as AdminEditNews;
use App\Livewire\Admin\NewsList as AdminNewsList;

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

// Logout Global
Route::get('/logout', function () {
    Auth::logout();
    return redirect()->back();
})->name('global-logout');


Route::get('/', Homepage::class)->name('homepage');
Route::get('/berita/{target}', NewsDetail::class)->name('news-detail');
Route::get('/berita', NewsPage::class)->name('news-page');
Route::get('/category', Category::class)->name('category');
Route::get('/category/{targetSlug}', CategoryRedirect::class)->name('category-redirect');
Route::get('/gallery', Gallery::class)->name('gallery');
Route::get('/search', SearchPage::class)->name('search');

// Admin Route
Route::prefix('admin')->middleware(['auth','isAdmin'])->group(function () {
    Route::get('/daftar-berita', AdminNewsList::class)->name('admin-news-list');
    Route::get('/category', AdminCategory::class)->name('admin-category');
    
    Route::prefix('/post')->group(function () {
        Route::get('/tambah', AdminAddNews::class)->name('admin-add-post');
        Route::get('/edit/{target}', AdminEditNews::class)->name('admin-edit-post');
    });
    
    // Route::prefix('/view-settings')->group(function () {
    //     Route::get('/sport', AdminSportConfig::class)->name('sport-page-config');
    //     Route::get('/environment', AdminEnvConfig::class)->name('env-page-config');
    //     Route::get('/history', AdminHistoryConfig::class)->name('history-page-config');
    // });
});
