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

        foreach ($requests as $r) {
            if ($r->type === 'reserve' && $r->status === 'approved') {
                $position = DB::table('book_requests')
                    ->where('book_id', $r->book_id)
                    ->where('type', 'reserve')
                    ->where('status', 'approved')
                    ->where('request_date', '<', $r->request_date)
                    ->count();
                $r->queue_position = $position + 1;
            } else {
                $r->queue_position = null;
            }

            $r->can_renew = $r->type === 'issue'
                && $r->status === 'approved'
                && !$r->renewed
                && $r->due_date
                && now()->isSameDay($r->due_date);
        }

        // Load unread notifications and mark them read on page visit
        $notifications = DB::table('member_notifications')
            ->where('member_id', $memberId)
            ->orderByDesc('created_at')
            ->limit(20)
            ->get();

        DB::table('member_notifications')
            ->where('member_id', $memberId)
            ->where('read', false)
            ->update(['read' => true, 'updated_at' => now()]);

        return view('member.my-books', compact('requests', 'notifications'));
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
            'member_id'    => $memberId,
            'book_id'      => $bookId,
            'type'         => 'issue',
            'status'       => 'pending',
            'request_date' => now(),
            'created_at'   => now(),
            'updated_at'   => now(),
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
            'member_id'    => $memberId,
            'book_id'      => $issueRecord->book_id,
            'type'         => 'return',
            'status'       => 'pending',
            'request_date' => now(),
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return redirect()->route('member.myBooks')->with('success', 'Return request submitted.');
    }

    public function renew($id)
    {
        $memberId = session('member_id');

        $bookRequest = DB::table('book_requests')
            ->where('id', $id)
            ->where('member_id', $memberId)
            ->where('type', 'issue')
            ->where('status', 'approved')
            ->first();

        if (!$bookRequest) {
            return back()->with('error', 'No active issued book found.');
        }

        if (!$bookRequest->due_date || !now()->isSameDay($bookRequest->due_date)) {
            return back()->with('error', 'You can only renew on the due date itself.');
        }

        if ($bookRequest->renewed) {
            return back()->with('error', 'This book has already been renewed once.');
        }

        DB::table('book_requests')->where('id', $id)->update([
            'due_date'   => now()->addDays(30),
            'renewed'    => 1,
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Book renewed. New due date pushed 30 days.');
    }

    public function requestReserve($bookId)
    {
        $memberId = session('member_id');
        $book = DB::table('books')->where('id', $bookId)->first();

        if (!$book) {
            abort(404);
        }

        if ($book->available_copies > 0) {
            return back()->with('error', 'Book is available — use Borrow instead of Reserve.');
        }

        $existing = DB::table('book_requests')
            ->where('member_id', $memberId)
            ->where('book_id', $bookId)
            ->where('type', 'reserve')
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existing) {
            return back()->with('error', 'You already have a pending or active reservation for this book.');
        }

        DB::table('book_requests')->insert([
            'member_id'    => $memberId,
            'book_id'      => $bookId,
            'type'         => 'reserve',
            'status'       => 'pending',
            'request_date' => now(),
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return redirect()->route('member.myBooks')->with('success', 'Reservation request submitted. Waiting for admin approval.');
    }
}
