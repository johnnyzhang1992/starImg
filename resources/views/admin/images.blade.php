@extends('voyager::master')

@section('content')
    <div class="page-content">
    @include('voyager::alerts')
    @include('voyager::dimmers')
        @if(isset($images) && $images)
            @foreach($images as $image)
                {{ print_r($image) }}<br>
            @endforeach
        @endif
    </div>
@endsection