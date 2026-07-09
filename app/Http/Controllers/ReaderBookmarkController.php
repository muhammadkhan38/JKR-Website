<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\ReaderBookmark;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReaderBookmarkController extends Controller
{
    public function index(Request $request, Book $book): JsonResponse
    {
        $query = ReaderBookmark::where('book_id', $book->id);

        foreach ($this->readerIdentity($request) as $column => $value) {
            $query->where($column, $value);
        }

        return response()->json([
            'bookmarks' => $query->orderBy('page_number')->get(['id', 'page_number', 'note']),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'book_id' => ['required', 'exists:books,id'],
            'page_number' => ['required', 'integer', 'min:1', 'max:50000'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $bookmark = ReaderBookmark::firstOrCreate(
            [
                'book_id' => $data['book_id'],
                'page_number' => $data['page_number'],
            ] + $this->readerIdentity($request),
            ['note' => $data['note'] ?? null],
        );

        return response()->json([
            'saved' => true,
            'bookmark' => $bookmark->only(['id', 'page_number', 'note']),
            'message' => __('messages.reader.bookmark_saved'),
        ]);
    }

    public function destroy(Request $request, ReaderBookmark $readerBookmark): JsonResponse
    {
        $query = ReaderBookmark::whereKey($readerBookmark->id);

        foreach ($this->readerIdentity($request) as $column => $value) {
            $query->where($column, $value);
        }

        abort_unless($query->exists(), 403);
        $readerBookmark->delete();

        return response()->json(['deleted' => true]);
    }

    private function readerIdentity(Request $request): array
    {
        if ($request->user()) {
            return ['user_id' => $request->user()->id];
        }

        return ['session_id' => $request->session()->getId()];
    }
}
