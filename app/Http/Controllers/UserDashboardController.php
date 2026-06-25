<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserDashboardController extends Controller
{
    public function index()
    {
        $memberId = session('member_id');

        // Books currently with the member (issued, not yet returned)
        $issuedCount = DB::table('book_requests')
            ->where('member_id', $memberId)
            ->where('type', 'issue')
            ->where('status', '!=', 'returned')
            ->where('status', '!=', 'pending')
            ->count();

        // Pending issue requests (awaiting admin approval)
        $pendingRequests = DB::table('book_requests')
            ->join('books', 'book_requests.book_id', '=', 'books.id')
            ->where('book_requests.member_id', $memberId)
            
            ->where('book_requests.status', 'pending')
            ->select(
                'book_requests.id',
                'book_requests.book_id',
                'book_requests.request_date',
                'book_requests.status',
                'books.title'
            )
            ->get();

        // Outstanding fines (only sum if fine exists)
        $totalFines = DB::table('book_requests')
            ->where('member_id', $memberId)
            ->where('fine_amount', '>', 0)
            ->sum('fine_amount');

        return view('member.User-Dashboard', compact('issuedCount', 'pendingRequests', 'totalFines'));
    }
}