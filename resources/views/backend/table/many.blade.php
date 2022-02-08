{{-- вывод нескольких таблиц в админке --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- тайтл страницы и мета-данные --}}
@section("title")
    {{ 'Список всех таблиц '.$typeTsbles }}
@endsection

{{-- секция контента --}}
@section("content")
    @include('backend.table.manycontent')
@endsection