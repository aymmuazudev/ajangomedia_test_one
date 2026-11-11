<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'AJANGO MEDIA | News. Information. Impact.')</title>
    <meta name="description" content="@yield('description', 'AJANGO MEDIA LTD is a digital news and publishing platform covering Nigeria, Africa and the world.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@700&family=Open+Sans:wght@400;600&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
</head>
<body>
@include('public.partials.top-bar')
@include('public.partials.header')
@include('public.partials.navigation')

<div id="content">
    @yield('content')
</div>

<x-ad-slot slotName="home_bottom" />

@include('public.partials.footer')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
