{{-- вывод одной таблицы в админке --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- тайтл страницы и мета-данные --}}
@section("title")
    {{ 'Просмотр таблицы "'.Str::title($table).'" ('.$typeTsbles.')' }}
@endsection

{{-- секция контента --}}
@section("content")
    @include('backend.table.onecontent')
@endsection