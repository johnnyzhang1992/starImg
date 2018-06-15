@extends('voyager::master')

@section('content')
    <div class="page-content">
    @include('voyager::alerts')
    @include('voyager::dimmers')
        <div class="row">
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>{{@$star_count}}</h3>
                        <p>明星数量</p>
                    </div>
                    <div class="icon">
                        <i class="voyager-people"></i>
                    </div>
                    <a href="/admin/stars" class="small-box-footer">管理明星 <i class="voyager-angle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>{{@$user_count}}</h3>
                        <p>用户数量</p>
                    </div>
                    <div class="icon">
                        <i class="voyager-person"></i>
                    </div>
                    <a href="/admin/users" class="small-box-footer">管理用户 <i class="voyager-angle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{{@$img_count}}</h3>
                        <p>图片数量</p>
                    </div>
                    <div class="icon">
                        <i class="voyager-images"></i>
                    </div>
                    <a href="/admin/images" class="small-box-footer">管理图片 <i class="voyager-angle-right"></i></a>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .small-box {
            border-radius: 2px;
            position: relative;
            display: block;
            color: #fff;
            margin-bottom: 20px;
            box-shadow: 0 1px 1px rgba(0,0,0,0.1);
        }
        .small-box>.inner {
            padding: 10px;
        }
        .small-box h3, .small-box p {
            z-index: 5;
        }
        .small-box p {
            font-size: 15px;
        }
        .small-box h3 {
            font-size: 38px;
            font-weight: bold;
            margin: 0 0 10px 0;
            white-space: nowrap;
            padding: 0;
        }
        .small-box .icon {
            -webkit-transition: all .3s linear;
            -o-transition: all .3s linear;
            transition: all .3s linear;
            position: absolute;
            top: -10px;
            right: 10px;
            z-index: 0;
            font-size: 90px;
            color: rgba(0,0,0,0.15);
        }
        .small-box>.small-box-footer {
            position: relative;
            text-align: center;
            padding: 3px 0;
            color: #fff;
            color: rgba(255,255,255,0.8);
            display: block;
            z-index: 10;
            background: rgba(0,0,0,0.1);
            text-decoration: none;
        }
        .bg-yellow{
            background-color: #f39c12 !important;
        }
        .bg-green{
            background-color: #00a65a !important;
        }
        .bg-aqua{
            background-color: #00c0ef !important;
        }
        .bg-red{
            background-color: #dd4b39 !important;
        }
        .bg-blue {
            background-color: #0073b7 !important;
        }
    </style>
@stop
@section('javascript')

@stop