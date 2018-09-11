@extends('frontend.layouts.app')

@include('frontend.partials.tongji')

@section('css')
    <link rel="stylesheet" href="{{ _star_asset(mix('css/react-img.css')) }}">
@endsection

@section('content')

@endsection

@section('javascript')
    <script src="{{ _star_asset(mix('js/star.js')) }}"></script>
    <!--百度自动提交-->
    <script>
        (function(){
            let bp = document.createElement('script');
            let curProtocol = window.location.protocol.split(':')[0];
            if (curProtocol === 'https') {
                bp.src = 'https://zz.bdstatic.com/linksubmit/push.js';
            }
            else {
                bp.src = 'http://push.zhanzhang.baidu.com/push.js';
            }
            let s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(bp, s);
        })();
    </script>
@endsection()
