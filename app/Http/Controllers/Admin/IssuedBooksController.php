<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class IssuedBooksController extends Controller
{
    public function index()
    {
        $books = DB::table('books')->orderBy('title')->get();

        foreach ($books as $book) {
            $book->issued_count = DB::table('book_requests')
                ->where('book_id', $book->id)
                ->where('type', 'issue')
                ->where('status', 'approved')
                ->count();

            $book->queue_count = DB::table('book_requests')
                ->where('book_id', $book->id)
                ->where('type', 'reserve')
                ->where('status', 'approved')
                ->count();

            if ($book->available_copies < 1 && $book->queue_count > 0) {
                $book->reservation_status = 'Reserved (queue active)';
            } elseif ($book->available_copies < 1) {
                $book->reservation_status = 'Fully Issued';
            } else {
                $book->reservation_status = 'Available';
            }
        }

        return view('admin.issued-books.index', compact('books'));
    }

    public function show($id)
    {
        $book = DB::table('books')->where('id', $id)->first();

        if (!$book) {
            abort(404);
        }

        $holders = DB::table('book_requests')
            ->join('members', 'members.id', '=', 'book_requests.member_id')
            ->where('book_requests.book_id', $id)
            ->where('book_requests.type', 'issue')
            ->where('book_requests.status', 'approved')
            ->select('book_requests.*',
                DB::raw("CONCAT(members.first_name, ' ', members.last_name) as member_name"),
                'members.email as member_email')
            ->orderBy('book_requests.due_date')
            ->get();

        foreach ($holders as $h) {
            $h->is_overdue = $h->due_date && now()->gt($h->due_date);
        }

        // Also show lost records for this book
        $lostRecords = DB::table('book_requests')
            ->join('members', 'members.id', '=', 'book_requests.member_id')
            ->where('book_requests.book_id', $id)
            ->where('book_requests.type', 'issue')
            ->where('book_requests.status', 'lost')
            ->select('book_requests.*',
                DB::raw("CONCAT(members.first_name, ' ', members.last_name) as member_name"),
                'members.email as member_email')
            ->orderByDesc('book_requests.updated_at')
            ->get();

        $queue = DB::table('book_requests')
            ->join('members', 'members.id', '=', 'book_requests.member_id')
            ->where('book_requests.book_id', $id)
            ->where('book_requests.type', 'reserve')
            ->where('book_requests.status', 'approved')
            ->select('book_requests.*',
                DB::raw("CONCAT(members.first_name, ' ', members.last_name) as member_name"))
            ->orderBy('book_requests.request_date')
            ->get();

        return view('admin.issued-books.show', compact('book', 'holders', 'queue', 'lostRecords'));
    }
}
