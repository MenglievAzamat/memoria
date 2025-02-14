<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookStatus;
use App\Models\CoverType;
use App\Models\Page;
use App\Models\Questionnaire;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function show(string $id): JsonResponse
    {
        return response()->json([
            'book' => Book::query()
                ->with(['questionnaire', 'coverType'])
                ->findOrFail($id)
        ]);
    }

    public function showByUserId(string $userId): JsonResponse
    {
        return response()->json([
            'book' => Book::query()
                ->with('questionnaire', 'coverType')
                ->where('user_id', $userId)
                ->latest()
                ->first()
        ]);
    }

    public function upsertQuestionnaire(Request $request, int $bookId): JsonResponse
    {
        $questionnaire = Questionnaire::query()->where('book_id', $bookId)->first();

        if (!$questionnaire) {
            $questionnaire = new Questionnaire();
        }

        $questionnaire->book_id = $bookId;
        $questionnaire->for_who = $request->input('for_who');
        $questionnaire->self_info = $request->input('self_info');
        $questionnaire->summary = $request->input('summary');
        $questionnaire->save();

        return response()->json([
            'book' => $questionnaire->book,
        ]);
    }

    public function getCovers(): JsonResponse
    {
        return response()->json([
            'covers' => CoverType::query()->get()
        ]);
    }

    public function saveCoverType(Request $request, int $bookId): JsonResponse
    {
        $coverTypeId = $request->input('cover_type_id');
        $book = Book::query()->findOrFail($bookId);

        $book->cover_type_id = $coverTypeId;
        $book->save();

        return response()->json([
            'book' => $book,
        ]);
    }

    public function getBookChapters(int $bookId): JsonResponse
    {
        $book = Book::query()->findOrFail($bookId);

        $chapters = $book->chapters()->get();

        return response()->json([
            'chapters' => $chapters
        ]);
    }

    public function saveTitle(Request $request, int $bookId): JsonResponse
    {
        $book = Book::query()->findOrFail($bookId);
        $book->title = $request->input('title');
        $book->subtitle = $request->input('subtitle');
        $book->save();

        return response()->json([
            'message' => 'Заголовок сохранен',
            'book' => $book,
        ]);
    }

    public function closeBook($bookId): JsonResponse
    {
        $book = Book::query()->findOrFail($bookId);

        $book->open = false;
        $book->book_status_id = BookStatus::query()->where('name', 'filled')->first()->id;
        $book->save();

        return response()->json([
            'message' => 'Книга завершена!',
        ]);
    }

    public function toggleBook($bookId)
    {
        $book = Book::query()->findOrFail($bookId);

        $book->open = !$book->open;
        $book->save();

        return response()->json([
            'message' => 'Книга ' . ($book->open ? 'открыта' : 'закрыта'),
        ]);
    }
}
