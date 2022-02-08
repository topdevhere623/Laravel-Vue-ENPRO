{{-- импорт таблиц в админке --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- тайтл страницы и мета-данные --}}
@section("title")
    Импорт таблиц
@endsection

{{-- секция контента --}}
@section("content")
    @include('backend.table.importcontent')
@endsection