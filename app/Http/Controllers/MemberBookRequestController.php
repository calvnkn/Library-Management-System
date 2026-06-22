<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class MemberBookRequestController extends Controller
{
    public function index()
    {
        $memberId = session('member_id');

        $requests = DB::table('book_requests')
            ->join('books', 'books.id', '=', 'book_requests.book_id')
            ->where('book_requests.member_id', $memberId)
            ->select('book_requests.*', 'books.title', 'books.author')
            ->orderByDesc('book_requests.created_at')
            ->get();

        return view('member.my-books', compact('requests'));
    }

    public function requestIssue($bookId)
    {
        $memberId = session('member_id');
        $book = DB::table('books')->where('id', $bookId)->first();

        if (!$book) {
            abort(404);
        }

        if ($book->available_copies < 1) {
            return back()->with('error', 'No available copies right now.');
        }

        $existing = DB::table('book_requests')
            ->where('member_id', $memberId)
            ->where('book_id', $bookId)
            ->where('type', 'issue')
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existing) {
            return back()->with('error', 'You already have a pending or active issue for this book.');
        }

        DB::table('book_requests')->insert([
            'member_id' => $memberId,
            'book_id' => $bookId,
            'type' => 'issue',
            'status' => 'pending',
            'request_date' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('member.myBooks')->with('success', 'Issue request submitted. Waiting for admin approval.');
    }

    public function requestReturn($requestId)
    {
        $memberId = session('member_id');

        $issueRecord = DB::table('book_requests')
            ->where('id', $requestId)
            ->where('member_id', $memberId)
            ->where('type', 'issue')
            ->where('status', 'approved')
            ->first();

        if (!$issueRecord) {
            return back()->with('error', 'No active issued book found for this request.');
        }

        $alreadyRequested = DB::table('book_requests')
            ->where('book_id', $issueRecord->book_id)
            ->where('member_id', $memberId)
            ->where('type', 'return')
            ->where('status', 'pending')
            ->first();

        if ($alreadyRequested) {
            return back()->with('error', 'Return already requested for this book.');
        }

        DB::table('book_requests')->insert([
            'member_id' => $memberId,
            'book_id' => $issueRecord->book_id,
            'type' => 'return',
            'status' => 'pending',
            'request_date' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('member.myBooks')->with('success', 'Return request submitted.');
    }
}