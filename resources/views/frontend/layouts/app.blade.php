<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('baidutongji')
    @yield('css')
</head>
<body>
<div id="app">
    @yield('nav')
    <main class="py-4">
        @yield('content')
    </main>
</div>
<!-- Scripts -->
<script type="text/javascript" src="https://admin.starimg.cn/vendor/tcg/voyager/assets/js/app.js"></script>
{{--<script src="{{ asset('js/app.js') }}" defer></script>--}}
@yield('javascript')
</body>
</html>
