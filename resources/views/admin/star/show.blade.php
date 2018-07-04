@extends('voyager::master')

@section('page_title', @$page_title)

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="voyager-people"></i>{{@$page_title}}
        </h1>
        <a href="{{ url('/admin/stars/new')  }}" class="btn btn-success btn-add-new">
            <i class="voyager-plus"></i> <span>添加</span>
        </a>
        <a href="{{ url('/admin/stars') }}" class="btn btn-warning">
            <span class="glyphicon glyphicon-list"></span>&nbsp;
            返回列表
        </a>
    </div>
@stop

@section('content')
    <div class="page-content browse container-fluid">
        @include('voyager::alerts')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-heading" style="border-bottom:0;">
                        <h3 class="panel-title">图片统计</h3>
                    </div>
                    <div class="panel-body" style="padding-top:0;">
                        <p>微博 {{@$wb_img_count}} <a href="{{url('/admin/images/'.$star->id.'/wb')}}" target="_blank">立即查看</a></p>
                        <p>Ins {{@$ins_img_count}} <a href="{{url('/admin/images/'.$star->id.'/ins')}}" target="_blank">立即查看</a></p>
                    </div>
                    <hr style="margin:0;">
                </div>
                <div class="panel panel-bordered">
                    <div class="panel-heading" style="border-bottom:0;">
                        <h3 class="panel-title">姓名 <small>({{ $star->gender == 'f' ? '女' : '男' }})</small></h3>
                    </div>
                    <div class="panel-body" style="padding-top:0;">
                        <p>{{@$star->name}} | {{@$star->en_name}} | {{@$star->country}}</p>
                    </div>
                    <hr style="margin:0;">
                    <div class="panel-heading" style="border-bottom:0;">
                        <h3 class="panel-title">生日</h3>
                    </div>
                    <div class="panel-body" style="padding-top:0;">
                        <p>{{@$star->birthday}}</p>
                    </div>
                    <hr style="margin:0;">
                    <div class="panel-heading" style="border-bottom:0;">
                        <h3 class="panel-title">职业</h3>
                    </div>
                    <div class="panel-body" style="padding-top:0;">
                        <p>{{@$star->profession}}</p>
                    </div>
                    <hr style="margin:0;">
                    <div class="panel-heading" style="border-bottom:0;">
                        <h3 class="panel-title">介绍</h3>
                    </div>
                    <div class="panel-body" style="padding-top:0;">
                        <p>{{@$star->description}}</p>
                    </div>
                    <hr style="margin:0;">
                    <div class="panel-heading" style="border-bottom:0;">
                        <h3 class="panel-title">头像</h3>
                    </div>
                    <div class="panel-body" style="padding-top:0;">
                        <img src="{{ asset(@$star->avatar) }}" alt="" class="img-responsive" style="max-width: 150px">
                    </div>
                    <hr style="margin:0;">
                    <div class="panel-heading" style="border-bottom:0;">
                        <h3 class="panel-title">创建时间</h3>
                    </div>
                    <div class="panel-body" style="padding-top:0;">
                        <p>{{@$star->created_at}} | {{ @$star->updated_at }}</p>
                    </div>
                    <hr style="margin:0;">
                </div>
                @if(isset($star_wb) && $star_wb)
                    <div class="panel panel-bordered">
                        <div class="panel-heading" style="border-bottom:0;">
                            <h3 class="panel-title">微博</h3>
                        </div>
                        <div class="panel-body" style="padding-top:0;">
                            <img src="{{ asset(@$star_wb->avatar) }}" alt="" class="img-responsive" style="max-width: 150px">
                        </div>
                        <div class="panel-body" style="padding-top:0;">
                            <p><a href="{{ url('https://weibo.com/u/'.@$star_wb->wb_id) }}">{{@$star_wb->screen_name}}</a> 粉丝： {{@$star_wb->followers_count}}</p>
                            <p> 认证：{{ $star_wb->verified ? '是' : '否' }} {{@$star_wb->verified_reason}}</p>
                            <p>介绍：{{@$star_wb->description}}</p>
                        </div>
                        <hr style="margin:0;">
                    </div>
                @endif

                @if(isset($star_ins) && $star_ins)
                    <div class="panel panel-bordered">
                        <div class="panel-heading" style="border-bottom:0;">
                            <h3 class="panel-title">Ins</h3>
                        </div>
                        <div class="panel-body" style="padding-top:0;">
                            <img src="{{ url(@$star_ins->avatar) }}" alt="" class="img-responsive" style="max-width: 150px">
                        </div>
                        <div class="panel-body" style="padding-top:0;">
                            <p><a href="{{ url('http://insstar.cn/'.@$star_ins->name) }}">{{@$star_ins->name}}</a> 粉丝： {{@$star_ins->followers_count}}</p>
                            <p> 认证：{{ $star_ins->verified ? '是' : '否' }}</p>
                            <p>介绍：{{@$star_ins->description}}</p>
                        </div>
                        <hr style="margin:0;">
                    </div>
                @endif

            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script>

    </script>
@stop