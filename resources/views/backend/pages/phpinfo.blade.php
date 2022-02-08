{{-- phpinfo - админка --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- тайтл страницы и мета-данные --}}
@section("title")
    Информация о версии PHP на хостинге
@endsection

{{-- секция контента --}}
@section("content")
    @include('backend.pages.phpinfo_content')
@endsection