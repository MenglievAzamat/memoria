<?php

namespace App\Http\Controllers\Chapter;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Chapter;
use App\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    public function addImage(Request $request, $chapterId): JsonResponse
    {
        $image = $request->file('image');

        $url = Storage::disk('public')->put('chapters', $image);
        $url = env('APP_URL') . '/storage/' . $url;

        return response()->json([
            'url' => $url
        ]);
    }
}
