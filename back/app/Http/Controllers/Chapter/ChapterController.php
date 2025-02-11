<?php

namespace App\Http\Controllers\Chapter;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChapterController extends Controller
{
    public function getChapter(int $chapterId): JsonResponse
    {
        return response()->json([
            'chapter' => Chapter::query()->with('pages')->findOrFail($chapterId)
        ]);
    }

    public function addPage(int $chapterId): JsonResponse
    {

        $chapter = Chapter::query()->findOrFail($chapterId);
        $lastPage = $chapter->pages()->latest()->first();
        $pageNumber = $lastPage->page_number + 1 ?? 1;
        $book = $chapter->book;


        if ($book->pagesCount() < 100) {
            $page = new Page();
            $page->chapter_id = $chapterId;
            $page->page_number = $pageNumber;
            $page->save();

            $page->chapter->book->rearrangePageNumbers();

            return response()->json([
                'message' => 'Страница добавлена',
                'page' => $page
            ]);
        } else {
            return response()->json([
                'message' => 'Достигнут лимит в 100 страниц! Невозможно создать новую',
            ], 422);
        }
    }
}
