<!DOCTYPE html>
<html class="no-js css-menubar" lang="en">
<head>
    {{--подключаем мета-данные и стили css --}}
    @include('backend.lib.meta')
</head>

<body class="animsition page-aside-fixed page-aside-left">

{{-- подключаем верхний хеадер --}}
@include('backend.lib.header')
{{-- область для вывода левого меню --}}
@include('backend.lib.leftside')

<div class="page" id="app">
    {{-- область для вывода контента --}}
    @yield('content')
</div>

{{-- подключаем нижний футер --}}
@include('backend.lib.footer')

{{-- подключаем срипты --}}
@include('backend.lib.scripts')

{{-- область для других скриптов и стилей --}}
@yield('scripts')

</body>
</html>