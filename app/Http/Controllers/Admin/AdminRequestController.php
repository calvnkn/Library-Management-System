<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AdminRequestController extends Controller
{
    public function index()
    {
        $requests = DB::table('book_requests')
            ->join('books', 'books.id', '=', 'book_requests.book_id')
            ->join('members', 'members.id', '=', 'book_requests.member_id')
            ->where('book_requests.status', 'pending')
            ->select('book_requests.*', 'books.title', 'books.available_copies', 'members.name as member_name')
            ->orderBy('book_requests.created_at')
            ->get();

        return view('admin.requests.index', compact('requests'));
    }

    public function approve($id)
    {
        $bookRequest = DB::table('book_requests')->where('id', $id)->first();

        if (!$bookRequest || $bookRequest->status !== 'pending') {
            return back()->with('error', 'Request not found or already processed.');
        }

        if ($bookRequest->type === 'issue') {
            $book = DB::table('books')->where('id', $bookRequest->book_id)->first();

            if (!$book || $book->available_copies < 1) {
                return back()->with('error', 'No available copies left for this book.');
            }

            DB::table('books')->where('id', $bookRequest->book_id)->decrement('available_copies');

            DB::table('book_requests')->where('id', $id)->update([
                'status' => 'approved',
                'issue_date' => now(),
                'due_date' => now()->addDays(30),
                'updated_at' => now(),
            ]);
        } elseif ($bookRequest->type === 'return') {
            $fine = 0;

            $issueRecord = DB::table('book_requests')
                ->where('member_id', $bookRequest->member_id)
                ->where('book_id', $bookRequest->book_id)
                ->where('type', 'issue')
                ->where('status', 'approved')
                ->first();

            if ($issueRecord && $issueRecord->due_date && now()->gt($issueRecord->due_date)) {
                $daysLate = now()->diffInDays($issueRecord->due_date);
                $fine = $daysLate * 5; // placeholder rate
            }

            DB::table('books')->where('id', $bookRequest->book_id)->increment('available_copies');

            DB::table('book_requests')->where('id', $id)->update([
                'status' => 'approved',
                'return_date' => now(),
                'fine_amount' => $fine,
                'updated_at' => now(),
            ]);

            if ($issueRecord) {
                DB::table('book_requests')->where('id', $issueRecord->id)->update([
                    'status' => 'returned',
                    'updated_at' => now(),
                ]);
            }

            // FIFO: if someone is queued for this book, auto-claim the freed copy for them
            $queuedReservation = DB::table('book_requests')
                ->where('book_id', $bookRequest->book_id)
                ->where('type', 'reserve')
                ->where('status', 'approved')
                ->orderBy('request_date')
                ->first();

            if ($queuedReservation) {
                DB::table('books')->where('id', $bookRequest->book_id)->decrement('available_copies');

                DB::table('book_requests')->where('id', $queuedReservation->id)->update([
                    'status' => 'fulfilled',
                    'updated_at' => now(),
                ]);

                DB::table('book_requests')->insert([
                    'member_id' => $queuedReservation->member_id,
                    'book_id' => $bookRequest->book_id,
                    'type' => 'issue',
                    'status' => 'approved',
                    'request_date' => now(),
                    'issue_date' => now(),
                    'due_date' => now()->addDays(30),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($bookRequest->type === 'reserve') {
            DB::table('book_requests')->where('id', $id)->update([
                'status' => 'approved',
                'updated_at' => now(),
            ]);
        }

        return back()->with('success', 'Request approved.');
    }

    public function reject($id)
    {
        DB::table('book_requests')->where('id', $id)->update([
            'status' => 'rejected',
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Request rejected.');
    }
}
