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
            return response()->json(['bookmarked' => true, 'message' => 'کتاب آپ کی محفوظ فہرست میں شامل کر دی گئی۔']);
        }

        return back()->with('success', 'کتاب آپ کی محفوظ فہرست میں شامل کر دی گئی۔');
    }

    public function destroy(Request $request, Book $book): JsonResponse|RedirectResponse
    {
        $request->user()->bookmarks()->where('book_id', $book->id)->delete();

        if ($request->expectsJson()) {
            return response()->json(['bookmarked' => false, 'message' => 'کتاب محفوظ فہرست سے نکال دی گئی۔']);
        }

        return back()->with('success', 'کتاب محفوظ فہرست سے نکال دی گئی۔');
    }
}
