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
                                    <img class="img-responsive" src="{{json_decode($image->pic_detail)[0]->src}}" alt="{{  @html_entity_decode($image->text) }}">
                            @endif
                        @else
                            <img class="img-responsive" src="{{$image->display_url}}" alt="{{  @html_entity_decode($image->text) }}">
                        @endif
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
        <div class="pagination-image clearfix" style="text-align: center">
            {{ @$images->links()}}
        </div>
    </div>
@endsection

@section('css')
    <style>
        .grid{
            margin-bottom: 25px;
        }
        .grid-sizer, .grid-item {
            width: 20%;
            padding: 10px;
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
@endsection
@section('javascript')
    <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
    <!-- or -->
    <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.js"></script>
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
@endsection