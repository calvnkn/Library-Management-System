<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type');

        $query = DB::table('book_requests')
            ->join('books', 'books.id', '=', 'book_requests.book_id')
            ->join('members', 'members.id', '=', 'book_requests.member_id')
            ->whereIn('book_requests.status', ['approved', 'returned', 'rejected', 'fulfilled', 'lost'])
            ->select('book_requests.*', 'books.title', DB::raw("CONCAT_WS(' ', members.first_name, members.middle_name, members.last_name) as member_name"));

        if ($type) {
            $query->where('book_requests.type', $type);
        }

        $logs = $query->orderByDesc('book_requests.updated_at')->get();

        return view('admin.activity-log.index', compact('logs', 'type'));
    }
}