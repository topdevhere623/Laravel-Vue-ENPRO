{{-- главная страница - админка --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- тайтл страницы и мета-данные --}}
@section("title")
    Главная
@endsection

{{-- секция контента --}}
@section("content")
    @include('backend.home.indexcontent')
@endsection