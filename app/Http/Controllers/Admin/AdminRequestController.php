<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;

class AdminRequestController extends Controller
{
    public function index()
    {
        $requests = DB::table('book_requests')
            ->join('books', 'books.id', '=', 'book_requests.book_id')
            ->join('members', 'members.id', '=', 'book_requests.member_id')
            ->where('book_requests.status', 'pending')
            ->select('book_requests.*', 'books.title', 'books.available_copies',
                DB::raw("CONCAT_WS(' ', members.first_name, members.middle_name, members.last_name) as member_name"))
            ->orderBy('book_requests.created_at')
            ->get();

        return view('admin.requests.index', compact('requests'));
    }

    public function approve(int $id)
    {
        $bookRequest = DB::table('book_requests')->where('id', $id)->first();

        if (!$bookRequest || $bookRequest->status !== 'pending') {
            return back()->with('error', 'Request not found or already processed.');
        }

        $book = DB::table('books')->where('id', $bookRequest->book_id)->first();

        if ($bookRequest->type === 'issue') {
            if (!$book || $book->available_copies < 1) {
                return back()->with('error', 'No available copies left for this book.');
            }

            DB::table('books')->where('id', $bookRequest->book_id)->decrement('available_copies');

            DB::table('book_requests')->where('id', $id)->update([
                'status'     => 'approved',
                'issue_date' => now(),
                'due_date'   => now()->addDays(30),
                'updated_at' => now(),
            ]);

            NotificationService::notify(
                $bookRequest->member_id,
                'issue_approved',
                'Borrow request approved',
                "Your request to borrow \"{$book->title}\" has been approved. It is due back in 30 days."
            );

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
                $fine = $daysLate * 5;
            }

            DB::table('books')->where('id', $bookRequest->book_id)->increment('available_copies');

            DB::table('book_requests')->where('id', $id)->update([
                'status'      => 'approved',
                'return_date' => now(),
                'fine_amount' => $fine,
                'fine_status' => $fine > 0 ? 'unpaid' : 'resolved',
                'updated_at'  => now(),
            ]);

            if ($issueRecord) {
                DB::table('book_requests')->where('id', $issueRecord->id)->update([
                    'status'     => 'returned',
                    'updated_at' => now(),
                ]);
            }

            $fineMsg = $fine > 0
                ? " A late fee of ₱{$fine} has been applied."
                : ' No late fees.';

            NotificationService::notify(
                $bookRequest->member_id,
                'return_approved',
                'Return approved',
                "Your return of \"{$book->title}\" has been processed.{$fineMsg}"
            );

            NotificationService::fulfillNextReservation($bookRequest->book_id);

        } elseif ($bookRequest->type === 'reserve') {
            DB::table('book_requests')->where('id', $id)->update([
                'status'     => 'approved',
                'updated_at' => now(),
            ]);

            NotificationService::notify(
                $bookRequest->member_id,
                'reservation_approved',
                'Reservation confirmed',
                "Your reservation for \"{$book->title}\" is confirmed. You are in the queue and will be notified when a copy becomes available."
            );
        }

        return back()->with('success', 'Request approved.');
    }

    public function reject(int $id)
    {
        $bookRequest = DB::table('book_requests')->where('id', $id)->first();

        DB::table('book_requests')->where('id', $id)->update([
            'status'     => 'rejected',
            'updated_at' => now(),
        ]);

        if ($bookRequest) {
            $book = DB::table('books')->where('id', $bookRequest->book_id)->first();
            $bookTitle = $book ? $book->title : 'a book';
            $typeLabel = match ($bookRequest->type) {
                'issue'   => 'borrow request',
                'return'  => 'return request',
                'reserve' => 'reservation',
                default   => 'request',
            };

            NotificationService::notify(
                $bookRequest->member_id,
                $bookRequest->type . '_rejected',
                ucfirst($typeLabel) . ' rejected',
                "Your {$typeLabel} for \"{$bookTitle}\" was not approved. Please contact the library for more information."
            );
        }

        return back()->with('success', 'Request rejected.');
    }

    public function markLost(int $id)
    {
        $issueRecord = DB::table('book_requests')
            ->where('id', $id)
            ->where('type', 'issue')
            ->where('status', 'approved')
            ->first();

        if (!$issueRecord) {
            return back()->with('error', 'Active issue record not found.');
        }

        $book = DB::table('books')->where('id', $issueRecord->book_id)->first();

        $lostFine = $book->replacement_price ?? 0;

        DB::table('book_requests')->where('id', $id)->update([
            'status'           => 'lost',
            'is_lost'          => true,
            'lost_fine_amount' => $lostFine,
            'fine_status'      => 'unpaid',
            'updated_at'       => now(),
        ]);

        DB::table('books')->where('id', $issueRecord->book_id)->decrement('total_copies');

        $fineMsg = $lostFine > 0
            ? "A replacement fine of ₱{$lostFine} has been applied."
            : "No replacement price was set for this book — please update it manually.";

        NotificationService::notify(
            $issueRecord->member_id,
            'lost_fine',
            'Book reported lost — fine applied',
            "The book \"{$book->title}\" has been reported as lost under your account. {$fineMsg} Please settle this at the library."
        );

        return back()->with('success', "Book marked as lost." . ($lostFine > 0 ? " ₱{$lostFine} fine applied." : " No price set — update the book record."));
    }
}