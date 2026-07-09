<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\ReadingProgress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReadingProgressController extends Controller
{
    public function saveProgress(Request $request): JsonResponse
    {
        $data = $request->validate([
            'book_id' => ['required', 'exists:books,id'],
            'current_page' => ['required', 'integer', 'min:1', 'max:50000'],
            'total_pages' => ['nullable', 'integer', 'min:1', 'max:50000'],
        ]);

        $identity = $this->readerIdentity($request);
        $progress = ReadingProgress::updateOrCreate(
            ['book_id' => $data['book_id']] + $identity,
            [
                'current_page' => $data['current_page'],
                'total_pages' => $data['total_pages'] ?? null,
            ],
        );

        return response()->json([
            'saved' => true,
            'current_page' => $progress->current_page,
            'total_pages' => $progress->total_pages,
        ]);
    }

    public function getProgress(Request $request, Book $book): JsonResponse
    {
        $query = ReadingProgress::where('book_id', $book->id);
        $identity = $this->readerIdentity($request);

        foreach ($identity as $column => $value) {
            $query->where($column, $value);
        }

        $progress = $query->latest()->first();

        return response()->json([
            'current_page' => $progress?->current_page ?? 1,
            'total_pages' => $progress?->total_pages,
        ]);
    }

    private function readerIdentity(Request $request): array
    {
        if ($request->user()) {
            return ['user_id' => $request->user()->id];
        }

        return ['session_id' => $request->session()->getId()];
    }
}
