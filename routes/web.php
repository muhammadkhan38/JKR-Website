<?php

use App\Http\Controllers\Admin\AudioController as AdminAudioController;
use App\Http\Controllers\Admin\AuthorController as AdminAuthorController;
use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\IslamicEventController as AdminIslamicEventController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AudioController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IslamicEventController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{book:slug}', [BookController::class, 'show'])->name('books.show');
Route::get('/books/{book:slug}/read', [BookController::class, 'reader'])->name('books.reader');
Route::get('/books/{book:slug}/download', [BookController::class, 'download'])->name('books.download');
Route::get('/categories/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/events/{event:slug}', [IslamicEventController::class, 'show'])->name('events.show');
Route::get('/audios', [AudioController::class, 'index'])->name('audios.index');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function (): void {
    Route::get('/my-bookmarks', [BookmarkController::class, 'index'])->name('bookmarks.index');
    Route::post('/books/{book}/bookmark', [BookmarkController::class, 'store'])->name('books.bookmark.store');
    Route::delete('/books/{book}/bookmark', [BookmarkController::class, 'destroy'])->name('books.bookmark.destroy');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function (): void {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('books', AdminBookController::class);
    Route::resource('categories', AdminCategoryController::class);
    Route::resource('authors', AdminAuthorController::class);
    Route::resource('islamic-events', AdminIslamicEventController::class)->parameters(['islamic-events' => 'islamicEvent']);
    Route::resource('audios', AdminAudioController::class);
    Route::resource('users', AdminUserController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
    Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
});
