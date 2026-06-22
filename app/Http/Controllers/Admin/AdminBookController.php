<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|max:50',
            'publication' => 'nullable|string|max:255',
            'category' => 'required|string|max:100',
            'total_copies' => 'required|integer|min:1',
        ]);

        DB::table('books')->insert([
            'title' => $validated['title'],
            'author' => $validated['author'],
            'isbn' => $validated['isbn'],
            'publication' => $validated['publication'] ?? null,
            'category' => $validated['category'],
            'total_copies' => $validated['total_copies'],
            'available_copies' => $validated['total_copies'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.books.index')->with('success', 'Book added.');
    }

    public function edit($id)
    {
        $book = DB::table('books')->where('id', $id)->first();

        if (!$book) {
            abort(404);
        }

        return view('admin.books.edit', compact('book'));
    }

    public function update(Request $request, $id)
    {
        $book = DB::table('books')->where('id', $id)->first();

        if (!$book) {
            abort(404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|max:50',
            'publication' => 'nullable|string|max:255',
            'category' => 'required|string|max:100',
            'total_copies' => 'required|integer|min:1',
        ]);

        // Keep available_copies consistent if total_copies changes
        $issuedCount = $book->total_copies - $book->available_copies;
        $newAvailable = max(0, $validated['total_copies'] - $issuedCount);

        DB::table('books')->where('id', $id)->update([
            'title' => $validated['title'],
            'author' => $validated['author'],
            'isbn' => $validated['isbn'],
            'publication' => $validated['publication'] ?? null,
            'category' => $validated['category'],
            'total_copies' => $validated['total_copies'],
            'available_copies' => $newAvailable,
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.books.index')->with('success', 'Book updated.');
    }

    public function destroy($id)
    {
        DB::table('books')->where('id', $id)->delete();

        return redirect()->route('admin.books.index')->with('success', 'Book removed.');
    }
}