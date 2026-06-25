<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AdminDefaulterController extends Controller
{
    public function index()
    {
        $defaulters = DB::table('book_requests')
            ->join('books', 'books.id', '=', 'book_requests.book_id')
            ->join('members', 'members.id', '=', 'book_requests.member_id')
            ->where('book_requests.type', 'issue')
            ->where('book_requests.status', 'approved')
            ->where('book_requests.due_date', '<', now())
            ->select('book_requests.*', 'books.title', DB::raw("CONCAT_WS(' ', members.first_name, members.middle_name, members.last_name) as member_name"), 'members.email as member_email')
            ->orderBy('book_requests.due_date')
            ->get();
        foreach ($defaulters as $d) {
            $d->days_overdue = now()->diffInDays($d->due_date);
            $d->projected_fine = $d->days_overdue * 5;
        }
        return view('admin.defaulters.index', compact('defaulters'));
    }
}