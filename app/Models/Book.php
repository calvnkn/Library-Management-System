<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Eloquent model for the books table.
 *
 * Why this exists alongside the raw DB::table() calls:
 * The Observer pattern requires Eloquent events, so we need an Eloquent model
 * for the books table. All existing DB::table('books') queries still work fine —
 * the observer only fires when saves go through Eloquent (i.e. AdminBookController
 * after you update it per the instructions below).
 */
class Book extends Model
{
    protected $table = 'books';

    protected $fillable = [
        'title',
        'author',
        'isbn',
        'publication',
        'category',
        'total_copies',
        'available_copies',
        'replacement_price',
    ];

    /**
     * Register the observer in AppServiceProvider::boot().
     * The observer fires fulfillNextReservation() whenever
     * available_copies increases, no matter where the change comes from.
     */
    protected static function booted(): void
    {
        static::updated(function (Book $book) {
            $original = $book->getOriginal('available_copies');
            $current  = $book->available_copies;

            // Only act if copies went UP (a copy became available)
            if ($current > $original) {
                \App\Services\NotificationService::fulfillNextReservation($book->id);
            }
        });
    }
}
