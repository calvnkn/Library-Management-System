<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\MemberNotificationMail;

class NotificationService
{
    /**
     * Notify a member of a status change.
     * Creates an in-app notification row and queues an email.
     */
    public static function notify(int $memberId, string $type, string $title, string $message): void
    {
        DB::table('member_notifications')->insert([
            'member_id' => $memberId,
            'type'      => $type,
            'title'     => $title,
            'message'   => $message,
            'read'      => false,
            'email_sent' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $notifId = DB::getPdo()->lastInsertId();

        // Send email if the member has one
        $member = DB::table('members')->where('id', $memberId)->first();
        if ($member && $member->email) {
            try {
                Mail::to($member->email)->send(
                    new MemberNotificationMail($title, $message, $member->first_name . ' ' . $member->last_name)
                );
                DB::table('member_notifications')->where('id', $notifId)->update(['email_sent' => true]);
            } catch (\Throwable $e) {
                // Log but don't crash the main flow
                \Illuminate\Support\Facades\Log::error('Notification email failed: ' . $e->getMessage());
            }
        }
    }

    /**
     * Fire the FIFO reservation auto-fulfil for a given book.
     * Call this whenever available_copies goes up for any reason
     * (return approval, admin book edit, direct DB fix via Observer).
     */
    public static function fulfillNextReservation(int $bookId): void
    {
        $book = DB::table('books')->where('id', $bookId)->first();
        if (!$book || $book->available_copies < 1) {
            return;
        }

        $queued = DB::table('book_requests')
            ->where('book_id', $bookId)
            ->where('type', 'reserve')
            ->where('status', 'approved')
            ->orderBy('request_date')
            ->first();

        if (!$queued) {
            return;
        }

        DB::table('books')->where('id', $bookId)->decrement('available_copies');

        DB::table('book_requests')->where('id', $queued->id)->update([
            'status'     => 'fulfilled',
            'updated_at' => now(),
        ]);

        DB::table('book_requests')->insert([
            'member_id'    => $queued->member_id,
            'book_id'      => $bookId,
            'type'         => 'issue',
            'status'       => 'approved',
            'request_date' => now(),
            'issue_date'   => now(),
            'due_date'     => now()->addDays(30),
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        $bookTitle = $book->title;
        self::notify(
            $queued->member_id,
            'reservation_fulfilled',
            'Your reservation is ready!',
            "Great news! Your reservation for \"{$bookTitle}\" has been fulfilled. The book has been issued to you and is due in 30 days. Please visit the library to collect it."
        );
    }
}
