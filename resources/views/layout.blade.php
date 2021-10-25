<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{mix('css/app.css')}}">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <meta name="robots" content="noindex, nofollow">
</head>
<body>
<div class="container mt-5" id="app">
    <div class="row">
        <div class="col-md-4">
            @include('chunk.menu')
        </div>
        <main class="col-md-8">
            <h1>@yield('caption')</h1>
            @yield('content')
        </main>
    </div>
</div>
<script src="{{mix('js/manifest.js')}}"></script>
<script src="{{mix('js/vendor.js')}}"></script>
<script src="{{mix('js/app.js')}}"></script>
</body>
</html>
