<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="page-type" content="{{ isset($page_type) && $page_type ? $page_type : 'normal'}}">
    <link rel="apple-touch-icon" sizes="128x128" type="image/png" href="/star.png">
    <link rel="icon" sizes="128x128" type="image/png" href="/star.png">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <title>{{ isset($site_title) && $site_title ? @$site_title : config('app.name', 'starimg').' | '.config('seo.site_title')  }}</title>
    <meta name="title" content="{{ isset($site_title) && $site_title ? @$site_title : config('app.name', 'starimg').' | '.config('seo.site_title') }}">
    <meta name="keywords" content="{{isset($site_keywords) && $site_keywords ? $site_keywords : config('seo.keywords')}}">
    <meta name="description" content="{{isset($site_description) && $site_description ? @$site_description : config('seo.description')}}">
    <meta name="baidu-site-verification" content="2X6IMyYQxI" />
    @yield('seo')
    <!-- Styles -->
    @yield('css')
    {{--<link href="{{ mix('css/app.css') }}" rel="stylesheet">--}}
    @yield('tongji')
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
