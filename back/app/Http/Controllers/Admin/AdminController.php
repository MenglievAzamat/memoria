<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveUserRequest;
use App\Models\Book;
use App\Models\Chapter;
use App\Models\Page;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function getBooks(): JsonResponse
    {
        $books = Book::query()
            ->with(['user', 'bookStatus', 'questionnaire'])
            ->get()
            ->map(function (Book $book) {
                return [
                    'id' => $book->id,
                    'name' => $book->user->name,
                    'phone' => $book->user->phone,
                    'status' => $book->bookStatus,
                    'questionnaire' => $book->questionnaire
                ];
            });

        return response()->json([
            'books' => $books
        ]);
    }

    public function saveUser(SaveUserRequest $request): JsonResponse
    {
        $user = new User();

        $user->name = $request->input('name');
        $user->phone = $request->input('phone');
        $user->password = Hash::make($request->input('password'));
        $user->type = $request->input('isAdmin', false) ? 'admin' : 'client';
        $user->save();

        if ($user->type !== 'admin') {
            $user->books()->create();
        }

        return response()->json([
            'message' => 'Пользователь успешно создан'
        ]);
    }

    public function saveChapter(Request $request, int $bookId): JsonResponse
    {
        $book = Book::query()->findOrFail($bookId);

        $lastChapter = $book->chapters()->latest()->first();
        $pageNumber = $lastChapter?->pages()->latest()->first()?->page_number + 1 ?? 1;

        $chapter = new Chapter();
        $chapter->book_id = $bookId;
        $chapter->title = $request->input('title');
        $chapter->question = $request->input('question');
        $chapter->save();

        $chapter->pages()->create([
            'page_number' => $pageNumber
        ]);

        return response()->json([
            'message' => 'Глава создана!'
        ]);
    }

    public function saveChapterQuestion(Request $request, int $chapterId): JsonResponse
    {
        Chapter::query()->where('id', $chapterId)->update([
            'question' => $request->input('question', ''),
            'title' => $request->input('title', '')
        ]);

        return response()->json([
            'message' => 'Изменения сохранены'
        ]);
    }
}
