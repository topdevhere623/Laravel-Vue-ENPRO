<!DOCTYPE html>
<html class="no-js css-menubar" lang="en">
<head>
    {{-- мета-данные и стили css --}}

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="www.programmist.uz">
    <meta name="robots" content="noindex, nofollow"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

    {{-- мета-данные --}}
    <title>@yield('title')</title>

    {{-- токен --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- фавикон --}}
    <link rel="shortcut icon" type="image/png" href="/public/uploads/favicon/favicon.ico"/>

    {{-- подгружаемые стили общие --}}
    {{-- Stylesheets --}}
    <link rel="stylesheet" href="/public/assets/backend/global/css/bootstrap.min.css">
    <link rel="stylesheet" href="/public/assets/backend/global/css/bootstrap-extend.min.css">
    <link rel="stylesheet" href="/public/assets/backend/css/site.min.css">

    {{-- Fonts --}}
    <link rel="stylesheet" href="/public/assets/backend/global/fonts/material-design/material-design.min.css">
    <link rel="stylesheet" href="/public/assets/backend/global/fonts/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="/public/assets/backend/global/fonts/themify/themify.min.css">
    <link rel="stylesheet" href="/public/assets/backend/global/fonts/brand-icons/brand-icons.min.css">
    <link rel="stylesheet" href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,300italic'>
    <link rel="stylesheet" href="/public/assets/backend/global/fonts/web-icons/web-icons.min.css">

</head>

<body>

<div id="app">
    {{-- область для вывода контента --}}
    @yield('content')
</div>

{{-- область для других скриптов и стилей --}}
@yield('scripts')

</body>
</html>