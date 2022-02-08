<!DOCTYPE html>
<html class="no-js css-menubar" lang="en">
<head>
    {{--подключаем мета-данные и стили css --}}
    @include('backend.lib.meta')
</head>

<body class="animsition">

{{-- подключаем верхний хеадер --}}
@include('backend.lib.header')
<div class="page m-0 p-0">
    {{-- область для вывода контента --}}
    @yield('content')
</div>

{{-- подключаем срипты --}}
@include('backend.lib.scripts')
{{-- область для других скриптов и стилей --}}
@yield('scripts')

</body>
</html>
