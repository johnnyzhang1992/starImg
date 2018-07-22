@extends('frontend.layouts.app')

@section('css')
    <link rel="stylesheet" href="https://admin.starimg.cn/vendor/tcg/voyager/assets/css/app.css">
    <link rel="stylesheet" href="https://unpkg.com/sweetalert2@7.22.2/dist/sweetalert2.min.css">
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }
        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
            position: relative;
        }

        .title {
            font-size: 84px;
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

        .m-b-md {
            margin-bottom: 30px;
        }
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
        .grid-item p{
            text-align: left;
            margin-bottom: 0;
            text-indent: 2em;
        }
        .pagination-image{
            /*position: absolute;*/
            /*bottom: 0;*/
            /*left: -60px;*/
            /*width: 100%;*/
            padding: 0 10px;
            text-align: center;
        }
        .grid-item--width2 { width: 400px; }
        @media(max-width: 768px){
            .grid-sizer, .grid-item {
                width: 50%;
                padding: 10px;
                position: relative;
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
                                <img class="img-responsive" src="{{'https://i.starimg.cn/'.@$image->cos_url.'!small'}}" alt="{{  @html_entity_decode($image->text) }}">
                            @else
                                @if(isset($image->pic_detail) && $image->pic_detail && $image->pic_detail !='null')
                                    @if(isset(json_decode($image->pic_detail)->url) && json_decode($image->pic_detail)->url)
                                        <img class="img-responsive" src="{{json_decode($image->pic_detail)->url}}" alt="{{  @html_entity_decode($image->text) }}">
                                    @endif
                                    @if(isset($image->pic_detail) && $image->origin == 'instagram')

                                        <img class="img-responsive" src="{{@json_decode($image->pic_detail)[0]->src}}" title="{{@$image->id.'---time:'.@$image->created_at}}" alt="{{@$image->id.'---time:'.@$image->created_at}}">
                                    @endif
                                @else
                                    <img class="img-responsive" src="{{$image->display_url}}" alt="{{  @html_entity_decode($image->text) }}">
                                @endif
                            @endif

                        </a>
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

        });
    </script>
@endsection()
