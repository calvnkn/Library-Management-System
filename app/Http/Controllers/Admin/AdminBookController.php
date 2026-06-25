<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminBookController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = DB::table('books');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        $books = $query->orderBy('title')->get();

        return view('admin.books.index', compact('books', 'search'));
    }

    public function create()
    {
        return view('admin.books.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'author'       => 'required|string|max:255',
            'isbn'         => 'required|string|max:50|unique:books,isbn',
            'publication'  => 'nullable|string|max:255',
            'category'     => 'required|string|max:100',
            'total_copies' => 'required|integer|min:1',
            'replacement_price' => 'nullable|numeric|min:0',
        ]);

        Book::create([
            'title'            => $validated['title'],
            'author'           => $validated['author'],
            'isbn'             => $validated['isbn'],
            'publication'      => $validated['publication'] ?? null,
            'category'         => $validated['category'],
            'total_copies'     => $validated['total_copies'],
            'available_copies' => $validated['total_copies'],
            'replacement_price' => $validated['replacement_price'] ?? null,
        ]);

        return redirect()->route('admin.books.index')->with('success', 'Book added.');
    }

    public function edit(int $id)
    {
        $book = DB::table('books')->where('id', $id)->first();

        if (!$book) {
            abort(404);
        }

        return view('admin.books.edit', compact('book'));
    }

    public function update(Request $request, int $id)
    {
        $book = Book::findOrFail($id);

        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'author'       => 'required|string|max:255',
            'isbn'         => 'required|string|max:50|unique:books,isbn,' . $id,
            'publication'  => 'nullable|string|max:255',
            'category'     => 'required|string|max:100',
            'total_copies' => 'required|integer|min:0',
            'replacement_price' => 'nullable|numeric|min:0',
        ]);

        // Recalculate available copies the same way as before
        $issuedCount  = $book->total_copies - $book->available_copies;
        $newAvailable = max(0, $validated['total_copies'] - $issuedCount);

        // Using fill() + save() so Eloquent fires the updated event (and thus the observer)
        $book->fill([
            'title'            => $validated['title'],
            'author'           => $validated['author'],
            'isbn'             => $validated['isbn'],
            'publication'      => $validated['publication'] ?? null,
            'category'         => $validated['category'],
            'total_copies'     => $validated['total_copies'],
            'available_copies' => $newAvailable,
            'replacement_price' => $validated['replacement_price'] ?? null,
        ])->save();

        return redirect()->route('admin.books.index')->with('success', 'Book updated.');
    }

    public function destroy(int $id)
    {
        DB::table('books')->where('id', $id)->delete();

        return redirect()->route('admin.books.index')->with('success', 'Book removed.');
    }
}
