<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AdminFineController extends Controller
{
    public function index()
    {
        // Late return fines (existing)
        $fines = DB::table('book_requests')
            ->join('books', 'books.id', '=', 'book_requests.book_id')
            ->join('members', 'members.id', '=', 'book_requests.member_id')
            ->where('book_requests.type', 'return')
            ->where('book_requests.fine_amount', '>', 0)
            ->select('book_requests.*', 'books.title', DB::raw("CONCAT_WS(' ', members.first_name, members.middle_name, members.last_name) as member_name"))
            ->orderByDesc('book_requests.return_date')
            ->get();
         
        // Lost-book fines (new)
        $lostFines = DB::table('book_requests')
            ->join('books', 'books.id', '=', 'book_requests.book_id')
            ->join('members', 'members.id', '=', 'book_requests.member_id')
            ->where('book_requests.type', 'issue')
            ->where('book_requests.status', 'lost')
            ->where('book_requests.lost_fine_amount', '>', 0)
            ->select('book_requests.*', 'books.title', DB::raw("CONCAT_WS(' ', members.first_name, members.middle_name, members.last_name) as member_name"))
            ->orderByDesc('book_requests.updated_at')
            ->get();

        return view('admin.fines.index', compact('fines', 'lostFines'));
    }

    public function resolve(int $id)
    {
        DB::table('book_requests')->where('id', $id)->update([
            'fine_status' => 'resolved',
            'updated_at'  => now(),
        ]);
        return back()->with('success', 'Fine marked as resolved.');
    }
}