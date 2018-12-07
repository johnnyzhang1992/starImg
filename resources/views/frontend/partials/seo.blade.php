@section('seo')
    <meta property="og:type" content="profile">
    <meta property="og:image" content="{{isset($og_image) ? $og_image : ''}}">
    <meta property="og:title" content="{{ isset($site_title) && $site_title ? @$site_title : config('app.name', 'starimg').' | '.config('seo.site_title')  }}">
    <meta property="og:description" content="{{isset($site_description) && $site_description ? @$site_description : config('seo.description')}}">
    <meta property="og:url" content="{{isset($og_url) ? $og_url : asset('/')}}">
@endsection