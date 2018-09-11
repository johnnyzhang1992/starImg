@extends('frontend.layouts.app')

@include('frontend.partials.tongji')
@section('css')
    <link rel="stylesheet" href="{{ _star_asset(mix('css/react-img.css')) }}">
@endsection

@section('content')

@endsection

@section('javascript')
    <script src="{{ _star_asset(mix('js/react-img.js')) }}"></script>
@endsection()
