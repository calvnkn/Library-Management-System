<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\MemberAuthController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MemberBookRequestController;
use App\Http\Controllers\Admin\AdminBookController;
use App\Http\Controllers\Admin\AdminRequestController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Member auth
Route::get('/register', [MemberAuthController::class, 'showRegister'])->name('register');
Route::post('/register', [MemberAuthController::class, 'register']);
Route::get('/login', [MemberAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [MemberAuthController::class, 'login']);
Route::post('/logout', [MemberAuthController::class, 'logout'])->name('logout');

// Member-only
Route::middleware('member.auth')->group(function () {
    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/books/{id}', [BookController::class, 'show'])->name('books.show');

    Route::post('/books/{id}/issue', [MemberBookRequestController::class, 'requestIssue'])->name('books.issue');
    Route::get('/my-books', [MemberBookRequestController::class, 'index'])->name('member.myBooks');
    Route::post('/my-books/{id}/return', [MemberBookRequestController::class, 'requestReturn'])->name('member.requestReturn');
});

// Admin auth
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Admin-only
Route::prefix('admin')->middleware('admin.auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/books', [AdminBookController::class, 'index'])->name('admin.books.index');
    Route::get('/books/create', [AdminBookController::class, 'create'])->name('admin.books.create');
    Route::post('/books', [AdminBookController::class, 'store'])->name('admin.books.store');
    Route::get('/books/{id}/edit', [AdminBookController::class, 'edit'])->name('admin.books.edit');
    Route::put('/books/{id}', [AdminBookController::class, 'update'])->name('admin.books.update');
    Route::delete('/books/{id}', [AdminBookController::class, 'destroy'])->name('admin.books.destroy');

    Route::get('/requests', [AdminRequestController::class, 'index'])->name('admin.requests.index');
    Route::post('/requests/{id}/approve', [AdminRequestController::class, 'approve'])->name('admin.requests.approve');
    Route::post('/requests/{id}/reject', [AdminRequestController::class, 'reject'])->name('admin.requests.reject');
});