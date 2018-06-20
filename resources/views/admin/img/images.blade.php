@extends('voyager::master')

@section('content')
    <div class="page-content">
    @include('voyager::alerts')
    @include('voyager::dimmers')
        @if(isset($images) && $images)
            <div class="grid">
                @foreach($images as $image)
                    <div class="grid-item" data-id="{{@$image->id}}">
                        <a href="{{@$image->origin_url}}" target="_blank">
                        @if(isset($image->pic_detail) && $image->pic_detail)
                            @if(isset(json_decode($image->pic_detail)->url) && json_decode($image->pic_detail)->url)
                                <img class="img-responsive" src="{{json_decode($image->pic_detail)->url}}" alt="{{  @html_entity_decode($image->text) }}">
                            @endif
                            @if(isset($image->pic_detail) && $image->origin == 'instagram')
                                    <img class="img-responsive" src="{{@json_decode($image->pic_detail)[0]->src}}" title="{{@$image->id.'---time:'.@$image->created_at}}" alt="{{@$image->id.'---time:'.@$image->created_at}}">
                            @endif
                        @else
                            <img class="img-responsive" src="{{$image->display_url}}" alt="{{  @html_entity_decode($image->text) }}">
                        @endif
                        </a>
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
    <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.js"></script>
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
                            success: function(d){
                                // console.log(d);
                                // Swal(
                                //     'Deleted!',
                                //     'Your imaginary file has been deleted.',
                                //     'success'
                                // );
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
@stop