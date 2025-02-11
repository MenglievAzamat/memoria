<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookStatus;
use App\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{
    public function getPage(int $id): JsonResponse
    {
        return response()->json([
            'page' => Page::query()->findOrFail($id)
        ]);
    }

    public function savePage(Request $request, int $pageId): JsonResponse
    {
        $page = Page::query()->findOrFail($pageId);

        if ($file = $request->file('image')) {
            $path = Storage::disk('public')->put('pages/' . $pageId, $file);

            $page->image = env('APP_URL') . '/storage/' . $path;
        } else {
            $page->text = $request->input('text');
        }

        $page->save();

        /** @var Book $book */
        $book = $page->chapter->book;
        $chapters = $book->chapters;
        $pagesCount = $chapters->reduce(function ($carry, $chapter) {
            return $carry + $chapter->pages->count();
        }, 0);

        if ($pagesCount === $page->page_number && $book->book_status_id !== 2) {
            $book->book_status_id = 2;
            $book->save();
        }

        return response()->json([
            'message' => 'Сохранено',
            'page' => $page,
        ]);
    }
}
