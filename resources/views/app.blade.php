<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @stack('meta')
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description">
    <meta property="og:image">
    <meta property="og:title">
    <meta property="og:description">
    <meta name="twitter:image">
    <meta name="twitter:title">
    <meta name="twitter:description">
    <meta name="twitter:card" content="summary">

    <title>{{ config('app.name') }}</title>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Karla|Merriweather" rel="stylesheet">
    <link rel="canonical">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.15.10/styles/github.min.css">
</head>
<body>
<div id="studio">
    <router-view></router-view>
</div>

@javascript('Studio', $scripts)

<script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
