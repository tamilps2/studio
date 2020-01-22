<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Sometimes creating a blog is easier said than done. With Canvas, it's just easier.">
    <meta property="og:image" content="{{ asset('img/opengraph.png') }}">
    <meta property="og:title" content="Studio ― A Laravel publishing platform">
    <meta property="og:description" content="Sometimes creating a blog is easier said than done. With Canvas, it's just easier.">
    <meta name="twitter:image" content="{{ asset('img/opengraph.png') }}">
    <meta name="twitter:title" content="Studio ― A Laravel publishing platform">
    <meta name="twitter:description" content="Sometimes creating a blog is easier said than done. With Canvas, it's just easier.">
    <meta name="twitter:card" content="summary">

    <title>{{ config('app.name') }}</title>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Karla|Merriweather:300,300i,400,400i,700,700i&display=swap" rel="stylesheet">
    <link rel="canonical">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.15.10/styles/github.min.css">

    <link rel="icon" type="image/png" href="{{ asset('img/favicon.ico') }}">
</head>
<body>
<div id="studio">
    <router-view></router-view>
</div>

@javascript('Studio', $scripts)

<script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
