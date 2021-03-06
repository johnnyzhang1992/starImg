@extends('frontend.layouts.app')

{{--@include('frontend.partials.tongji')--}}

@section('css')
    <link rel="stylesheet" href="{{ _star_asset(mix('css/react-img.css')) }}">
    <style>
        .f-brand {
            font-size: 18px;
        }
    </style>
@endsection

@section('content')
    <div style="text-align: center;padding: 50px 0">加载中。。。</div>
@endsection

@section('javascript')
    <script src="{{ _star_asset(mix('js/new_star_list.js')) }}" defer></script>
    <script>
        // (function(){
        //     var bp = document.createElement('script');
        //     var curProtocol = window.location.protocol.split(':')[0];
        //     if (curProtocol === 'https') {
        //         bp.src = 'https://zz.bdstatic.com/linksubmit/push.js';
        //     }
        //     else {
        //         bp.src = 'http://push.zhanzhang.baidu.com/push.js';
        //     }
        //     var s = document.getElementsByTagName("script")[0];
        //     s.parentNode.insertBefore(bp, s);
        // })();
    </script>
@endsection()
