<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $searchBy = $request->query('search_by', 'title');

        $query = DB::table('books');

        if ($search) {
            if ($searchBy === 'id') {
                $query->where('id', $search);
            } elseif ($searchBy === 'author') {
                $query->where('author', 'like', "%{$search}%");
            } else {
                $query->where('title', 'like', "%{$search}%");
            }
        }

        $books = $query->orderBy('title')->get();

        return view('books.index', compact('books', 'search', 'searchBy'));
    }

    public function show($id)
    {
        $book = DB::table('books')->where('id', $id)->first();

        if (!$book) {
            abort(404);
        }

        return view('books.show', compact('book'));
    }
}