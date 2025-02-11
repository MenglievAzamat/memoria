<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Book\BookController;
use App\Http\Controllers\Chapter\ChapterController;
use App\Http\Controllers\Page\PageController;
use App\Http\Middleware\AdminRoutes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::prefix('auth')
    ->controller(AuthController::class)
    ->group(function () {
        Route::post('login', 'login');

        Route::middleware('auth:sanctum')
            ->group(function () {
                Route::delete('logout', 'logout');
                Route::get('user', 'user');
            });
    });

Route::get('/image', function (Request $request) {
    return Storage::disk('public')->get($request->input('filename'));
});

Route::prefix('book')
    ->controller(BookController::class)
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::prefix('{id}')
            ->whereNumber('id')
            ->group(function () {
                Route::get('/', 'show');

                Route::post('questionnaire', 'upsertQuestionnaire');

                Route::post('cover', 'saveCoverType');

                Route::get('chapters', 'getBookChapters');

                Route::post('title', 'saveTitle');

                Route::post('close', 'closeBook');
                Route::post('toggle', 'toggleBook');
            });

        Route::get('user/{id}', 'showByUserId');
        Route::get('covers', 'getCovers');
    });

Route::prefix('page')
    ->controller(PageController::class)
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::post('{id}', 'savePage')->whereNumber('id');
        Route::get('{id}', 'getPage')->whereNumber('id');
    });

Route::prefix('chapter')
    ->controller(ChapterController::class)
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::prefix('{id}')
            ->whereNumber('id')
            ->group(function () {
                Route::get('/', 'getChapter');
                Route::post('/page', 'addPage');
            });
    });

Route::middleware([
    'auth:sanctum',
    AdminRoutes::class,
])
    ->controller(AdminController::class)
    ->prefix('admin')
    ->group(function () {
        Route::get('books', 'getBooks');
        Route::post('user', 'saveUser');
        Route::post('/book/{id}/chapter', 'saveChapter')->whereNumber('id');
        Route::put('/chapter/{id}/question', 'saveChapterQuestion')->whereNumber('id');
    });
