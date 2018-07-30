@extends('frontend.layouts.app')

@section('css')
    {{--<link rel="stylesheet" href="https://admin.starimg.cn/vendor/tcg/voyager/assets/css/app.css">--}}
    {{--<link rel="stylesheet" href="https://unpkg.com/sweetalert2@7.22.2/dist/sweetalert2.min.css">--}}
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }
        .content {
            text-align: center;
            position: relative;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }
        .grid{
            margin-bottom: 25px;
        }
        .grid-item {
            width: 20%;
            padding: 10px;
            display: inline-block;
            position: relative;
        }
        .grid-item a{
            display: block;
        }
        .grid-item img{
            border-radius: 6px;
        }
        .grid-item p{
            text-align: left;
            margin-bottom: 0;
            text-indent: 2em;
        }
        .pagination-image{
            padding: 0 10px;
            text-align: center;
        }
        .pagination{
            overflow: auto;
        }
        .grid-item{
            /*margin: -8px;*/
            padding: 8px;
            border-radius: 6px;
        }
        .grid-item::before {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 8px;
            content: " " !important;
            height: 100%;
            left: 0;
            opacity: 0;
            pointer-events: none;
            position: absolute;
            top: 0;
            -webkit-transform: scale(0.96);
            transform: scale(0.96);
            width: 100%;
            z-index: 3;
        }
        .grid-item:hover::before {
            -webkit-animation: tapAnimation 0.25s cubic-bezier(0.31, 1, 0.34, 1) forwards;
            animation: tapAnimation 0.25s cubic-bezier(0.31, 1, 0.34, 1) forwards;
        }
        /* origin part */
        .img-origin{
            display: none;
            padding: 10px 15px;
        }
        .domainNameLink{
            /*color: #fff;*/
            display: block;
            font-size: 11px;
            font-weight: 500;
            line-height: 18px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            text-align: left;
        }
        .domainNameLink svg{
            fill: currentColor;
            stroke-width: 0;
            vertical-align: middle;
        }
        /* author part*/
        .origin-author{
            padding: 10px 0;
            display: flex;
        }
        .origin-author .left,.origin-author .right{
            display: inline-block;
            padding: 0;
        }
        .origin-author .left{
            width: 50px;
            max-width: 50px;
        }
        .origin-author .author-avatar{
            width: 35px;
            border-radius: 50%;
        }
        .origin-author .author_description{
            font-size: 14px;
            line-height: 20px;
            white-space: nowrap;
            overflow: hidden;
            text-align: left;
            text-overflow: ellipsis;
            width: 100%;
        }
        .origin-author .author_name{
            font-size: 12px;
            line-height: 18px;
            white-space: nowrap;
            overflow: hidden;
            text-align: left;
            text-overflow: ellipsis;
            width: 100%;
            padding-top: 5px;
        }
        .origin-author a{
            color: #333;
        }
        .origin-author a:hover{
            text-decoration: underline;
        }
        @keyframes tapAnimation {
            0% {
                opacity: 1;
                -webkit-transform: scale(0.96);
                transform: scale(0.96);
            }
            100% {
                opacity: 1;
                -webkit-transform: scale(1);
                transform: scale(1);
            }
        }
        @media(max-width: 768px){
            .grid-sizer, .grid-item {
                width: 50%;
                padding: 10px;
                position: relative;
                display: inline-block;
                float: left;
            }
            .origin-author .left{
                width: 30px;
                line-height: 43px;
            }
            .origin-author .author-avatar{
                width: 25px;
            }
        }
    </style>
@endsection

@include('frontend.partials.tongji')
@include('frontend.partials.nav')

@section('content')
    <div class="content">
        @if(isset($images) && $images)
            <div class="grid">
                @foreach($images as $image)
                    <div class="grid-item" data-id="{{@$image->id}}">
                        <a href="{{@$image->origin_url}}" target="_blank">
                            @if(isset($image->cos_url) && $image->cos_url)
                                <img class="img-fluid" src="{{'https://i.starimg.cn/'.@$image->cos_url.'!small'}}" alt="{{  @html_entity_decode($image->text) }}">
                            @else
                                @if(isset($image->pic_detail) && $image->pic_detail && $image->pic_detail !='null')
                                    @if(isset(json_decode($image->pic_detail)->url) && json_decode($image->pic_detail)->url)
                                        <img class="img-fluid" src="{{json_decode($image->pic_detail)->url}}" alt="{{  @html_entity_decode($image->text) }}">
                                    @endif
                                    @if(isset($image->pic_detail) && $image->origin == 'instagram')

                                        <img class="img-fluid" src="{{@json_decode($image->pic_detail)[0]->src}}" title="{{@$image->id.'---time:'.@$image->created_at}}" alt="{{@$image->id.'---time:'.@$image->created_at}}">
                                    @endif
                                @else
                                    <img class="img-fluid" src="{{$image->display_url}}" alt="{{  @html_entity_decode($image->text) }}">
                                @endif
                            @endif
                        </a>
                        <div class="img-origin">
                            <a href="https://weibo.com" class="domainNameLink">
                                <svg class="_s1 _5s _s2 _29" height="14" width="14" viewBox="0 0 24 24" aria-label="link" role="img"><title>link</title><path d="M4.9283,1 C3.6273,1 2.5713,2.054 2.5713,3.357 C2.5713,4.66 3.6273,5.714 4.9283,5.714 L14.9523,5.714 L1.6893,18.976 C0.7703,19.896 0.7703,21.389 1.6893,22.31 C2.1503,22.771 2.7533,23 3.3573,23 C3.9603,23 4.5633,22.771 5.0243,22.31 L18.2853,9.047 L18.2853,19.071 C18.2853,20.374 19.3413,21.429 20.6433,21.429 C21.9443,21.429 23.0003,20.374 23.0003,19.071 L23.0003,1 L4.9283,1 Z"></path></svg>
                                <div>
                                    weibo.com
                                </div>
                            </a>
                        </div>
                        <div class="origin-author col-12 clearfix">
                            <div class="left col-3">
                                <a href="{{'https://weibo.com/u/'.@$image->wb_id}}" target="_blank">
                                    <img src="{{@$image->avatar}}" alt="{{@$image->screen_name}}" class="author-avatar">
                                </a>
                            </div>
                            <div class="right col-9">
                                <div class="author_description">{{@$image->description}}</div>
                                <div class="author_name"><a href="{{'https://weibo.com/u/'.@$image->wb_id}}" target="_blank">{{@$image->screen_name}}</a></div>
                            </div>
                        </div>
                        {{--<p>{!! @strip_tags($image->text) !!}</p>--}}
                    </div>
                @endforeach
            </div>
        @endif
        <div class="pagination-image clearfix" style="text-align: center">
            {{ @$images->links()}}
        </div>
    </div>
@endsection

@section('javascript')
    <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
    {{--<script src="https://unpkg.com/sweetalert2@7.22.2/dist/sweetalert2.min.js"></script>--}}
    <!-- Include a polyfill for ES6 Promises (optional) for IE11, UC Browser and Android browser support -->
    <script src="https://unpkg.com/promise-polyfill"></script>
    <script>
        $(document).ready(function () {
            $('.grid').masonry({
                // options
                itemSelector: '.grid-item',
                columnWidth: '.grid-item',
                percentPosition: true
            });
        });
    </script>
@endsection()
