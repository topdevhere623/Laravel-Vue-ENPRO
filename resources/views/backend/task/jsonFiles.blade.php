{{-- список json файлов --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- тайтл страницы и мета-данные --}}
@section("title")
    Файлы к задачам с мобильного приложения
@endsection

{{-- секция контента --}}
@section("content")
    @include('backend.task.jsonFilescontent')
@endsection
