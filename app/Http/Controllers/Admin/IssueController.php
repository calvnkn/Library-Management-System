<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IssueController extends Controller
{
    public function create(Request $request)
    {
        $memberSearch = $request->query('member_search');
        $bookSearch = $request->query('book_search');

        $members = DB::table('members')
            ->when($memberSearch, function ($q) use ($memberSearch) {
                $q->where('name', 'like', "%{$memberSearch}%")
                  ->orWhere('email', 'like', "%{$memberSearch}%");
            })
            ->orderBy('name')
            ->get();

        $books = DB::table('books')
            ->where('available_copies', '>', 0)
            ->when($bookSearch, function ($q) use ($bookSearch) {
                $q->where('title', 'like', "%{$bookSearch}%");
            })
            ->orderBy('title')
            ->get();

        return view('admin.issue.create', compact('members', 'books', 'memberSearch', 'bookSearch'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|integer',
            'book_id' => 'required|integer',
        ]);

        $member = DB::table('members')->where('id', $validated['member_id'])->first();
        $book = DB::table('books')->where('id', $validated['book_id'])->first();

        if (!$member || !$book) {
            return back()->with('error', 'Member or book not found.');
        }

        if ($book->available_copies < 1) {
            return back()->with('error', 'No available copies left for this book.');
        }

        $existing = DB::table('book_requests')
            ->where('member_id', $member->id)
            ->where('book_id', $book->id)
            ->where('type', 'issue')
            ->where('status', 'approved')
            ->first();

        if ($existing) {
            return back()->with('error', 'This member already has this book issued.');
        }

        DB::table('books')->where('id', $book->id)->decrement('available_copies');

        DB::table('book_requests')->insert([
            'member_id' => $member->id,
            'book_id' => $book->id,
            'type' => 'issue',
            'status' => 'approved',
            'request_date' => now(),
            'issue_date' => now(),
            'due_date' => now()->addDays(30),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.issue.create')->with('success', "Book '{$book->title}' issued directly to {$member->name}.");
    }
}