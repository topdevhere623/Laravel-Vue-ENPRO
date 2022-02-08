{{-- отладка запросов по API - админка --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- тайтл страницы и мета-данные --}}
@section("title")
    Отладка запросов по API
@endsection

{{-- секция контента --}}
@section("content")
    @include('backend.pages.api_queries_content')
@endsection