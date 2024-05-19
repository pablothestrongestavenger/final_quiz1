<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/posts/create', function() {
    return view('posts.create');
})->name('posts.create');

Route::get('/posts', [PostController::class, 'index'])->name('home')->middleware('auth.post');
Route::post('create', [PostController::class, 'store'])->name('create')->middleware('auth.post');
Route::get('show/{id}', [PostController::class, 'show'])->name('show')->middleware('auth.post');
Route::get('edit/{id}', [PostController::class, 'edit'])->name('edit')->middleware('auth.post');
Route::put('update/{id}', [PostController::class, 'update'])->name('update')->middleware('auth.post');
Route::delete('delete/{id}', [PostController::class, 'destroy'])->name('delete')->middleware('auth.post');

// Registration route
Route::get('/register', [PostController::class, 'register'])->name('register');
Route::post('/register', [PostController::class, 'registerUser'])->name('register');

// Login routes
Route::get('/login', [PostController::class, 'showLoginForm'])->name('login'); // Display login form
Route::post('/login', [PostController::class, 'authenticate'])->name('login.authenticate');
