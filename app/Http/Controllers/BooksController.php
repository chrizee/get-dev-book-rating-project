<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    public function index()
    {
        $books = Book::latest()->simplePaginate(15);
        return response()->json(['success' => true, 'books' => $books, 'messsage' => 'Books fetched']);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'author' => 'required|string|max:191',
            'isbn' => 'required|numeric',
            'description' => 'required|string',
            'published' => 'nullable|date'
        ]);

        $book = $request->user()->books()->create($request->only(['name', 'author', 'isbn', 'description', 'published']));
        if($book) {
            return response()->json(['success' => true, 'book' => $book, 'message' => 'Book created successfully']);
        } else {
            return response()->json(['success' => false, 'message' => "Unable to create book"]);
        }
    }

    public function update(Request $request, $book)
    {
        $book = Book::findOrFail($book);
        if(!$request->user()->canModifyBook($book)) {
            return response()->json(['success' => false, 'message' => "You cannot update that book"], 401);
        }
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'author' => 'required|string|max:191',
            'isbn' => 'required|numeric',
            'description' => 'required|string',
            'published' => 'nullable|date'
        ]);

        $update = $book->update($request->only(['name', 'author', 'isbn', 'description', 'published']));
        if($update) {
            return response()->json(['success' => true, 'book' => $book, 'message' => 'Book updated successfully']);
        } else {
            return response()->json(['success' => false, 'message' => "Unable to update book"]);
        }
    }

    public function delete(Request $request, $book)
    {
        $book = Book::findOrFail($book);
        if(!$request->user()->canModifyBook($book)) {
            return response()->json(['success' => false, 'message' => "You cannot delete that book"], 401);
        }
        $delete = $book->delete();
        if($delete) {
            return response()->json(['success' => true, 'book' => $book, 'message' => 'Book deleted successfully']);
        } else {
            return response()->json(['success' => false, 'message' => "Unable to delete book"]);
        }
    }

    public function rate(Request $request, $book)
    {
        $book = Book::findOrFail($book);
        $this->validate($request, [
            'rating' => "required|int|min:1|max:5"
        ]);

        //create a new rating for this user/book pair or update the existing one
        $book->rating()->updateOrCreate(
            ['user_id' => $request->user()->id, 'book_id' => $book->id],
            ['rating' => $request->input("rating")]
        );

        $book->refresh();

        return response()->json(['success' => true, 'book' => $book, "message" => "Book rated successfully"]);
    }
    
}
