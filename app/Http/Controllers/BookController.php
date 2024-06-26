<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        return Book::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'author' => 'required|max:100',
            'description' => 'nullable',
            'image_url' => 'nullable|url|max:255',
            'category' => 'required|max:50',
            'available' => 'boolean',
        ]);

        $book = Book::create($request->all());

        return response()->json($book, 201);
    }

    public function show($id)
    {
        return Book::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'sometimes|required|max:255',
            'author' => 'sometimes|required|max:100',
            'description' => 'nullable',
            'image_url' => 'nullable|url|max:255',
            'category' => 'sometimes|required|max:50',
            'available' => 'boolean',
        ]);

        $book = Book::findOrFail($id);
        $book->update($request->all());

        return response()->json($book, 200);
    }

    public function destroy($id)
    {
        Book::findOrFail($id)->delete();

        return response()->json(null, 204);
    }
}
