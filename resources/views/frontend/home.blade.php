@extends('frontend.layouts.app')

@include('frontend.partials.tongji')
@section('css')
    <link rel="stylesheet" href="{{ _star_asset(mix('css/react-img.css')) }}">
    <script>
        console.log('----性能检测----');
        var performance = window.performance ||
            window.msPerformance ||
            window.webkitPerformance;
        if (performance) {
            // 你的代码
            console.log(performance.memory);
            console.log(performance.timeOrigin);
            console.log(performance.navigation);
            console.log(performance.timing);
        }
    </script>
@endsection

@section('content')
    <div style="text-align: center;padding: 50px 0">加载中。。。</div>
@endsection

@section('javascript')
    <script src="{{ _star_asset(mix('js/react-img.js')) }}" defer></script>
@endsection()
