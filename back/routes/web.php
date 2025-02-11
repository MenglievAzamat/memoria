<?php

use App\Models\Book;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    $book = Book::query()->findOrFail(1);

    $payload = [
        'cover' => $book->coverType->image_link,
        'title' => $book->title,
        'subtitle' => $book->subtitle,
        'pages' => $book->pages,
    ];

    return view('pdf-generator', $payload);
});
