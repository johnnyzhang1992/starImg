@extends('frontend.layouts.app')

@include('frontend.partials.tongji')

@section('css')
    <link rel="stylesheet" href="{{ _star_asset(mix('css/react-img.css')) }}">
    {{--<link rel="stylesheet" href="{{ mix('css/react-img.css') }}">--}}
@endsection

@section('content')
    <div style="text-align: center;padding: 50px 0">加载中。。。</div>
@endsection

@section('javascript')
    <script src="{{ _star_asset(mix('js/pin-img.js')) }}" defer></script>
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
