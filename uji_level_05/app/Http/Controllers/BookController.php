<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book; // Assuming you have a Book model

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Book::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $book = new Book();
        $book->name = $request->name;
        $book->description = $request->description;
        $book->author = $request->author;
        $book->save();

        return "Data Berhasil Masuk";
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $book = Book::find($id);
        if ($book) {
            return $book;
        } else {
            return response()->json(['message' => 'Buku Tidak Ditemukan'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $name = $request->name;
        $description = $request->description;
        $author = $request->author;

        $book = Book::find($id);
        $book->name = $request->name;
        $book->description = $request->description;
        $book->author = $request->author;
        $book->save();

        return "Data Berhasil di Update";
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = Book::find($id);
        $book->delete();

        return "Data Berhasil Di Hapus";
    }
}
