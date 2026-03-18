<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

// Home — redirect to articles feed
Route::get('/', [ArticleController::class, 'index'])->name('home');

// Dashboard (auth)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Search
Route::get('/search', [SearchController::class, 'index'])->name('search');

// Articles — auth-protected (must be before public show to avoid slug collision)
Route::resource('articles', ArticleController::class)
    ->parameters(['articles' => 'article:slug'])
    ->only(['create', 'store', 'edit', 'update', 'destroy'])
    ->middleware('auth');

// Articles — public
Route::resource('articles', ArticleController::class)
    ->parameters(['articles' => 'article:slug'])
    ->only(['index', 'show']);

// Categories & Tags (public)
Route::get('/categories/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/tags/{tag:slug}', [TagController::class, 'show'])->name('tags.show');

// Communities — auth-protected (must be before public show)
Route::resource('communities', CommunityController::class)
    ->parameters(['communities' => 'community:slug'])
    ->only(['create', 'store', 'edit', 'update', 'destroy'])
    ->middleware('auth');

// Communities — public
Route::resource('communities', CommunityController::class)
    ->parameters(['communities' => 'community:slug'])
    ->only(['index', 'show']);

// Profile (public show)
Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');

// Auth-protected routes
Route::middleware('auth')->group(function () {
    // Profile edit
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Like & Favorite (AJAX)
    Route::post('/articles/{article}/like', [ArticleController::class, 'like'])->name('articles.like');
    Route::post('/articles/{article}/favorite', [ArticleController::class, 'favorite'])->name('articles.favorite');
    Route::post('/articles/{article:slug}/publish', [ArticleController::class, 'publish'])->name('articles.publish');

    // Community subscribe (AJAX)
    Route::post('/communities/{community}/subscribe', [CommunityController::class, 'subscribe'])->name('communities.subscribe');

    // Comments
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
});

require __DIR__.'/auth.php';
