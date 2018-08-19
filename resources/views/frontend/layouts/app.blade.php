<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="apple-touch-icon" sizes="128x128" type="image/png" href="/star.png">
    <link rel="icon" sizes="128x128" type="image/png" href="/star.png">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <title>{{ isset($site_title) && $site_title ? @$site_title : config('app.name', 'starimg').' | 快来pick你喜欢的爱豆' }}</title>
    <meta name="keywords" content="微博明星,微博图片,明星图片,街拍图片,微博精选图片">
    <meta name="description" content="{{isset($site_description) && $site_description ? @$site_description : 'starImg | 搜罗你喜欢的爱豆的微博、instagram、twitter、facebook各种来源的图片。'}}">
    <meta name="baidu-site-verification" content="2X6IMyYQxI" />
    <!-- Styles -->
    {{--<link href="{{ mix('css/app.css') }}" rel="stylesheet">--}}
    @yield('tongji')
    @yield('css')
</head>
<body id="body">
<div id="app">
    @yield('content')
</div>
<!-- Scripts -->
{{--<script type="text/javascript" src="https://admin.starimg.cn/vendor/tcg/voyager/assets/js/app.js"></script>--}}
{{--<script src="{{ mix('js/app.js') }}"></script>--}}
@yield('javascript')
</body>
</html>
