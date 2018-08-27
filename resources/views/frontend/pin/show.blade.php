@extends('frontend.layouts.app')

@include('frontend.partials.tongji')

@section('css')
    <link rel="stylesheet" href="{{ mix('css/react-img.css') }}">
@endsection

@section('content')

@endsection

@section('javascript')
    <script src="{{ mix('js/pin-show.js') }}"></script>
    <script>
        (function(){
            var bp = document.createElement('script');
            var curProtocol = window.location.protocol.split(':')[0];
            if (curProtocol === 'https') {
                bp.src = 'https://zz.bdstatic.com/linksubmit/push.js';
            }
            else {
                bp.src = 'http://push.zhanzhang.baidu.com/push.js';
            }
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(bp, s);
        })();
    </script>
@endsection()