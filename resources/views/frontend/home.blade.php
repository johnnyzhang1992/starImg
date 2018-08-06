@extends('frontend.layouts.app')

@include('frontend.partials.tongji')
@section('css')
    <link rel="stylesheet" href="{{ mix('css/react-img.css') }}">
    <style>
        html,body{
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
    </style>
@endsection

@section('content')

@endsection

@section('javascript')
    <script src="{{ mix('js/react-img.js') }}"></script>
@endsection()
