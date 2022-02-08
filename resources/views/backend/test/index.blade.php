{{-- список --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- тайтл страницы и мета-данные --}}
@section("title")
    ТЕСТ
@endsection

{{-- секция контента --}}
@section("content")
    @include('backend.test.indexcontent')
@endsection
