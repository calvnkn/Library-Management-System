<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\MemberAuthController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MemberBookRequestController;
use App\Http\Controllers\Admin\AdminBookController;
use App\Http\Controllers\Admin\AdminRequestController;
use App\Http\Controllers\Admin\AdminDefaulterController;
use App\Http\Controllers\Admin\AdminFineController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\IssueController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\IssuedBooksController;

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
    Route::post('/books/{id}/reserve', [MemberBookRequestController::class, 'requestReserve'])->name('books.reserve');
    Route::get('/my-books', [MemberBookRequestController::class, 'index'])->name('member.myBooks');
    Route::post('/my-books/{id}/return', [MemberBookRequestController::class, 'requestReturn'])->name('member.requestReturn');
    Route::post('/my-books/{id}/renew', [MemberBookRequestController::class, 'renew'])->name('member.renew');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::get('/notifications/unread-count', function () {
        $count = \Illuminate\Support\Facades\DB::table('member_notifications')
            ->where('member_id', session('member_id'))
            ->where('read', false)
            ->count();
        return response()->json(['count' => $count]);
    })->name('member.notifications.unreadCount');
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

    Route::get('/issued-books', [IssuedBooksController::class, 'index'])->name('admin.issuedBooks.index');
    Route::get('/issued-books/{id}', [IssuedBooksController::class, 'show'])->name('admin.issuedBooks.show');
    Route::post('/issued-books/{id}/mark-lost', [AdminRequestController::class, 'markLost'])->name('admin.issuedBooks.markLost');

    Route::get('/profile', [AdminProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::put('/profile', [AdminProfileController::class, 'update'])->name('admin.profile.update');
    Route::put('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('admin.profile.password');

    Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('admin.activityLog.index');

    Route::get('/users', [MemberController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{id}', [MemberController::class, 'show'])->name('admin.users.show');

    Route::get('/issue', [IssueController::class, 'create'])->name('admin.issue.create');
    Route::post('/issue', [IssueController::class, 'store'])->name('admin.issue.store');

    Route::get('/defaulters', [AdminDefaulterController::class, 'index'])->name('admin.defaulters.index');

    Route::get('/fines', [AdminFineController::class, 'index'])->name('admin.fines.index');
    Route::post('/fines/{id}/resolve', [AdminFineController::class, 'resolve'])->name('admin.fines.resolve');
});