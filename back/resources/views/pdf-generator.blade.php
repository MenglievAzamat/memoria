<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        @page {
            margin: 0;
            padding: 0;
            size: A5;
        }

        @font-face {
            font-family: Montserrat;
            src: url('data:application/font-ttf;charset=utf-8;base64,{{ base64_encode(file_get_contents(storage_path('/app/public/Montserrat.ttf'))) }}');
        }

        @font-face {
            font-family: Helvetica;
            src: url('data:application/font-ttf;charset=utf-8;base64,{{ base64_encode(file_get_contents(storage_path('/app/public/Helvetica.ttf'))) }}');
        }

        @font-face {
            font-family: Leotaro;
            src: url('data:application/font-ttf;charset=utf-8;base64,{{ base64_encode(file_get_contents(storage_path('/app/public/Leotaro.ttf'))) }}');
        }

        * {
            padding: 0;
            margin: 0;
        }

        .cover {
            width: 100%;
            height: 100dvh;
        }

        .cover img {
            width: 100%;
            height: 100%;
        }

        .title-page {
            width: 100%;
            height: 100dvh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            font-family: Leotaro, sans-serif;
        }

        .title-page h1 {
            font-size: 20pt;
        }

        .title-page h2 {
            font-size: 12pt;
        }

        .page {
            padding: 64px;
            height: 100dvh;
            display: flex;
            flex-direction: column;
            box-sizing: border-box;
        }

        .page .heading-left {
            display: flex;
            justify-content: flex-start;
            font-family: Leotaro, sans-serif;
        }

        .page .heading-right {
            display: flex;
            justify-content: flex-end;
            font-family: Leotaro, sans-serif;
        }

        .page .heading-left,
        .page .heading-right {
            height: 5%;
        }

        .page .number {
            height: 5%;
        }

        .page .heading-left h1,
        .page .heading-right h1 {
            font-size: 17pt;
        }

        .page .body {
            height: 90%;
            display: flex;
        }

        .page .body p {
            font-family: Helvetica, sans-serif;
            word-break: break-word;
            font-size: 17pt;
        }

        .page .number {
            display: flex;
            justify-content: center;
            font-family: Leotaro, sans-serif;
        }

        .page .number h2 {
            font-size: 17pt;
        }
    </style>
</head>
<body>
<div class="cover">
    <img src="{{ $cover }}" alt="">
</div>

@pageBreak

<div class="title-page">
    <h1>{{ $title }}</h1>
    <h2>{{ $subtitle }}</h2>
</div>

@pageBreak

@foreach($pages as $page)
    <div class="page">
        @if($page->page_number % 2 !== 0)
            <div class="heading-left">
                <h1>{{ $title  }}</h1>
            </div>

        @else
            <div class="heading-right">
                <h1>{{ $subtitle  }}</h1>
            </div>
        @endif

        <div class="body">
            @if($page->text)
                <p>{{ $page->text }}</p>
            @else
                <img style="width: 100%; height: 100%" src="{{ $page->image }}" alt="">
            @endif
        </div>

        <div class="number">
            <h2>{{ $page->page_number }}</h2>
        </div>
    </div>

    @pageBreak
@endforeach
</body>
</html>
