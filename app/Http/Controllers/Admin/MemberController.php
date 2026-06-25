<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $query = DB::table('members');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }
        $members = $query->orderBy('first_name')->get();
        return view('admin.users.index', compact('members', 'search'));
    }

    public function show($id)
    {
        $member = DB::table('members')->where('id', $id)->first();
        if (!$member) {
            abort(404);
        }
        $requests = DB::table('book_requests')
            ->join('books', 'books.id', '=', 'book_requests.book_id')
            ->where('book_requests.member_id', $id)
            ->select('book_requests.*', 'books.title')
            ->orderByDesc('book_requests.created_at')
            ->get();
        $unpaidFines = DB::table('book_requests')
            ->where('member_id', $id)
            ->where('fine_amount', '>', 0)
            ->where('fine_status', 'unpaid')
            ->sum('fine_amount');
        return view('admin.users.show', compact('member', 'requests', 'unpaidFines'));
    }
}