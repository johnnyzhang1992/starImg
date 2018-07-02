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
        <a href="{{ url('/admin/stars') }}" class="btn btn-warning">
            <span class="glyphicon glyphicon-list"></span>&nbsp;
            返回列表
        </a>
    </div>
@stop

@section('content')
    <div class="page-content browse container-fluid">
        @include('voyager::alerts')
        <form action="{{url('/admin/stars/store')}}" method="POST" enctype="multipart/form-data" autocomplete="off" class="form-edit-add">
            <input type="hidden" name="star_id" value="{{@$star->id}}">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-bordered">
                        <div class="panel-heading" style="border-bottom:0;">
                            <h3 class="panel-title"></h3>
                        </div>
                        <div class="panel-body" style="padding-top:0;">
                            <div class="form-group">
                                <label for="domain">个性域名</label>
                                <input type="text" class="form-control" id="domain" name="star[domain]" placeholder="个性域名" value="{{@$star->domain}}">
                            </div>
                            <div class="form-group">
                                <label for="name">命名</label>
                                <input type="text" class="form-control" id="name" name="star[name]" placeholder="命名" value="{{@$star->name }}">
                            </div>
                            <div class="form-group">
                                <label for="description">介绍(少于120字)</label>
                                <input type="text" class="form-control" id="description" name="star[description]" placeholder="介绍" value="{{@$star->description}}">
                            </div>
                            <div class="form-group">
                                <label for="avatar">头像</label>
                                <input type="text" class="form-control" id="avatar" name="star[avatar]" placeholder="avatar" value="{{@$star->avatar}}">
                            </div>
                            <div class="form-group">
                                <label for="gender">性别</label>
                                <select name="star[gender]" id="gender" class="form-control select2">
                                    <option value="f" @if(isset($star) && isset($star->gender) && $star->gender =='f') selected @endif> 女性</option>
                                    <option value="m" @if(isset($star) && isset($star->gender) && $star->gender =='m') selected @endif>男性</option>
                                    <option value="x" @if(isset($star) && isset($star->gender) && $star->gender =='x') selected @endif>其它</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="follow">粉丝</label>
                                <input type="text" disabled="" class="form-control" id="follow" name="star[follow_count]" placeholder="0" value="{{@$star->follow_count}}">
                            </div>
                            <div class="form-group">
                                <label for="country">国籍</label>
                                <input type="text" class="form-control" id="country" name="star[country]" placeholder="country" value="{{@$star->country}}">
                            </div>
                            <div class="form-group">
                                <label for="profession">职业</label>
                                <input type="text" class="form-control" id="profession" name="star[profession]" placeholder="" value="{{@$star->profession}}">
                            </div>
                            <div class="form-group">
                                <label for="baike">百度百科</label>
                                <input type="text" class="form-control" id="baike" name="star[baike]" placeholder="百度百科地址" value="{{@$star->baike}}">
                            </div>
                            <div class="form-group">
                                <label for="en_name">英文名</label>
                                <input type="text" class="form-control" id="en_name" name="star[en_name]" placeholder="英文名" value="{{@$star->en_name}}">
                            </div>
                            <div class="form-group">
                                <label for="birthday">生日</label>
                                <input type="text" class="form-control" id="birthday" name="star[birthday]" placeholder="1992-02-14" value="{{@$star->birthday}}">
                            </div>
                            <div class="form-group">
                                <label for="status">状态</label>
                                <select name="star[status]" id="status" class="form-control select2">
                                    <option value="status" @if(isset($star) && isset($star->status) && $star->status =='active') selected @endif> 正常</option>
                                    <option value="freeze" @if(isset($star) && isset($star->atatus) && $star->status =='freeze') selected @endif>冻结</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="wb_id">微博id</label>
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon3">https://weibo.com/u/</span>
                                    <input type="text" class="form-control" id="wb_id" name="star[wb_id]" placeholder="wb_id" value="{{@$star->wb_id}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="wb_domain">微博 domain</label>
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon3">https://weibo.com/</span>
                                    <input type="text" class="form-control" id="wb_domain" name="star[wb_domain]" placeholder="wb_domain" value="{{@$star->wb_domain}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="ins_id">IG id</label>
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon3">https://instagram/</span>
                                    <input type="text" class="form-control" id="ins_id" name="star[ins_id]" placeholder="ins_id" value="{{@$star->ins_id}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="ins_domain">IG domain</label>
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon3">https://instagram.com/</span>
                                    <input type="text" class="form-control" id="ins_domain" name="star[ins_name]" placeholder="ins_name" value="{{@$star->ins_name}}">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <button type="submit" class="btn btn-primary pull-right save">保存</button>
        </form>

    </div>
@stop

@section('javascript')
    <script>

    </script>
@stop