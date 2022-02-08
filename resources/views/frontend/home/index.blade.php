{{-- главная страница на фронте --}}

{{-- лайоут --}}
@extends("frontend.layouts.main")

{{-- тайтл страницы и мета-данные --}}
@section("title")
    Главная
@endsection

{{-- секция контента --}}
@section("content")
    @include('frontend.home.indexcontent')
@endsection