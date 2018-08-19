@extends('voyager::master')

@section('content')
    <div class="page-content">
    @include('voyager::alerts')
    @include('voyager::dimmers')
        @if(isset($images) && $images)
            <div class="row">
                <div class="col-lg-1"></div>
                <div class="col-lg-6">
                    <div class="input-group">
                        <span class="input-group-addon">
                            全选： <input type="checkbox" name="checkAll" aria-label="...">
                        </span>
                        <input type="text" class="form-control" name="checkIds" aria-label="...">
                    </div><!-- /input-group -->
                </div><!-- /.col-lg-6 -->
                <div class="col-lg-2">
                    <a type="button" class="delete-all btn btn-danger btn-sm">删除</a>
                </div>
            </div>
            <div class="grid">
                @foreach($images as $image)
                    <div class="grid-item" data-id="{{@$image->id}}">
                        <a href="{{@$image->origin_url}}" target="_blank">
                            @if(isset($image->cos_url) && $image->cos_url)
                                <img class="img-responsive" src="{{'https://i.starimg.cn/'.@$image->cos_url.'!small'}}" alt="{{  @html_entity_decode($image->text) }}">
                            @else
                                @if(isset($image->pic_detail) && $image->pic_detail && $image->pic_detail !='null')
                                    @if(isset(json_decode($image->pic_detail)->url) && json_decode($image->pic_detail)->url)
                                        <img class="img-responsive" src="{{str_replace('s.insstar.cn','inbmi.com',json_decode($image->pic_detail)->url)}}" alt="{{  @html_entity_decode($image->text) }}">
                                    @endif
                                    @if(isset($image->pic_detail) && $image->origin == 'instagram')
                                            <img class="img-responsive" src="{{str_replace('s.insstar.cn','inbmi.com',@json_decode($image->pic_detail)[0]->src)}}" title="{{@$image->id.'---time:'.@$image->created_at}}" alt="{{@$image->id.'---time:'.@$image->created_at}}">
                                    @endif
                                @else
                                    <img class="img-responsive" src="{{str_replace('s.insstar.cn','inbmi.com',$image->display_url)}}" alt="{{  @html_entity_decode($image->text) }}">
                                @endif
                            @endif
                        </a>
                        <p>cos_url: {{@$image->cos_url}}</p>
                        <p>{{@$star->name}} : {{@$image->take_at_timestamp}}</p>
                        <p>{!! @strip_tags($image->text) !!}</p>
                        <div class="row image-check">
                            <div class="col-xs-4">选择：</div>
                            <div class="col-xs-8">
                                <input type="checkbox" name="check" value="{{@$image->id}}">
                            </div>
                        </div>
                        <a type="button" class="delete-item btn btn-danger btn-sm" data-id="{{@$image->id}}" data-status="{{@$image->status}}">删除</a>
                    </div>
                @endforeach
            </div>
        @endif
        <div class="pagination-image clearfix" style="text-align: center">
            {{ @$images->links()}}
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://unpkg.com/sweetalert2@7.22.2/dist/sweetalert2.min.css">
    <style>
        .grid{
            margin-bottom: 25px;
        }
        .grid-sizer, .grid-item {
            width: 20%;
            padding: 10px;
            position: relative;
        }
        .grid-item a{
            display: block;
        }
        .grid-item img{
            border: 1px dashed #FF9800;
        }
        .pagination-image{
            position: absolute;
            bottom: 0;
            left: -60px;
            width: 100%;
            text-align: center;
        }
        .grid-item--width2 { width: 400px; }
    </style>
@stop
@section('javascript')
    <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
    <!-- or -->
    {{--<script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.js"></script>--}}
    <script src="https://unpkg.com/sweetalert2@7.22.2/dist/sweetalert2.min.js"></script>

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
            $('.delete-item').on('click',function () {
                var img_id = $(this).attr('data-id');
                var that = $(this);
                console.log('---img-id-'+img_id);
                Swal({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this imaginary file!',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, keep it'
                }).then(function(result){
                    if (result.value) {
                        $.ajax({
                            url: '/admin/images/'+img_id+'/delete',
                            dataType: "json",
                            type:"POST",
                            success: function(d){
                                $(that).parent().remove();
                                $.each(d.errors, function (inputName, errorMessage) {

                                    // This will work also for fields with brackets in the name, ie. name="image[]
                                    var $inputElement = $("[name='" + inputName + "']"),
                                        inputElementPosition = $inputElement.first().parent().offset().top,
                                        navbarHeight = $('nav.navbar').height();

                                    // Scroll to first error
                                    if (Object.keys(d.errors).indexOf(inputName) === 0) {
                                        $('html, body').animate({
                                            scrollTop: inputElementPosition - navbarHeight + 'px'
                                        }, 'fast');
                                    }

                                    // Hightlight and show the error message
                                    $inputElement.parent()
                                        .addClass("has-error")
                                        .append("<span class='help-block' style='color:#f96868'>" + errorMessage + "</span>")

                                });
                            }
                        })
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal(
                        'Cancelled',
                        'Your imaginary file is safe :)',
                        'error'
                    )
                }
                });
            });
            $("input[name='checkAll']").change(function() {
                var ids = '';
                if($(this).is(':checked')){
                    $("input[name = 'check']:checkbox").attr("checked", true);
                    $("input[name='check'][checked]").each(function(){
                        ids +=$(this).val()+',';
                    });
                    $("input[name='checkIds']").val(ids);
                }else{
                    $("input[name = 'check']:checkbox").attr("checked", false);
                    $("input[name='checkIds']").val('');
                }
            });
            $('input[name="check"]').change(function () {
                $(this).attr("checked", !$(this).attr("checked"));
                var ids = '';
                $("input[name='check'][checked]").each(function(){
                    ids +=$(this).val()+',';
                });
                $("input[name='checkIds']").val(ids);
            });
            $('.delete-all').on('click',function () {
                var ids = $("input[name='checkIds']").val();
                Swal({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this imaginary file!',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, keep it'
                }).then(function(result){
                    if (result.value) {
                        $.ajax({
                            url: '/admin/images/deleteSome?ids='+ids,
                            dataType: "json",
                            type:"POST",
                            success: function(d){
                              var img_ids = ids.split(',');
                              for(var i =0;i<img_ids.length;i++){
                                  if(img_ids[i] && img_ids !=''){
                                      $('.grid-item[data-id="'+img_ids[i]+'"]').remove();
                                  }
                              }
                            }
                        })
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        Swal(
                            'Cancelled',
                            'Your imaginary file is safe :)',
                            'error'
                        )
                    }
                });
            })
        });
    </script>
@stop