<?php

namespace App\Http\Controllers\Chapter;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Chapter;
use App\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChapterController extends Controller
{
    public function getChapter(int $chapterId): JsonResponse
    {
        $chapter = Chapter::query()->findOrFail($chapterId);

        return response()->json([
            'chapter' => $chapter,
            'pc_last_page' => $chapter->last_page ?? null
        ]);
    }

    public function saveChapterText(Request $request, int $chapterId): JsonResponse
    {
        $chapter = Chapter::query()->findOrFail($chapterId);
        $previousChapter = Chapter::query()->find($chapter->previousChapterId());

        $chapter->text = $request->input('text');
        $chapter->last_page = $previousChapter ? $previousChapter->last_page + $previousChapter->countPages() : null;
        $chapter->save();

        return response()->json([
            'message' => 'Сохранено'
        ]);
    }
}
