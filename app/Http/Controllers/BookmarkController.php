<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookmarkController extends Controller
{
    public function index(Request $request): View
    {
        $books = $request->user()
            ->bookmarkedBooks()
            ->active()
            ->with(['author', 'category'])
            ->latest('bookmarks.created_at')
            ->paginate(12);

        return view('user.bookmarks', compact('books'));
    }

    public function store(Request $request, Book $book): JsonResponse|RedirectResponse
    {
        $request->user()->bookmarks()->firstOrCreate(['book_id' => $book->id]);

        if ($request->expectsJson()) {
            return response()->json(['bookmarked' => true, 'message' => 'Book saved to your bookmarks.']);
        }

        return back()->with('success', 'Book saved to your bookmarks.');
    }

    public function destroy(Request $request, Book $book): JsonResponse|RedirectResponse
    {
        $request->user()->bookmarks()->where('book_id', $book->id)->delete();

        if ($request->expectsJson()) {
            return response()->json(['bookmarked' => false, 'message' => 'Book removed from your bookmarks.']);
        }

        return back()->with('success', 'Book removed from your bookmarks.');
    }
}
