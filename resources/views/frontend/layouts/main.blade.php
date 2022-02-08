<!DOCTYPE html>
<html class="no-js css-menubar" lang="en">
<head>
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
    {{--<link rel="shortcut icon" type="image/png" href="/public/uploads/favicon/favicon1.png"/>--}}

    {{-- подгружаемые стили --}}
    <link rel="stylesheet" href='/public/assets/common/front_zaglushka/style.css'>

    {{-- подгружаемые стили мои --}}
    <link rel="stylesheet" href='/public/assets/frontend/css/mystyle.css'>
    <link rel="stylesheet" href="/public/assets/common/libs/toastr/toastr.min.css">

</head>

<body>

{{-- подключаем верхний хеадер --}}
@include('frontend.lib.header')

<div class="page">
    {{-- область для вывода контента --}}
    @yield('content')
</div>

{{-- подключаем нижний футер --}}
@include('frontend.lib.footer')

{{-- подгружаемые скрипты общие --}}
<script type="text/javascript" src="/public/assets/common/libs/toastr/toastr.min.js"></script>
{{-- подгружаемые скрипты для бекенда --}}
{{--<script type="text/javascript" src="/public/assets/backend/js/myscript.js"></script>--}}

{{-- область для других скриптов и стилей --}}
@yield('scripts')

</body>
</html>