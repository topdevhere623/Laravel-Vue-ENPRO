{{-- инструкция по работе с API - админка --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- тайтл страницы и мета-данные --}}
@section("title")
    Инструкция по работе с API
@endsection

{{-- секция контента --}}
@section("content")
    @include('backend.pages.api_instruction_content')
@endsection