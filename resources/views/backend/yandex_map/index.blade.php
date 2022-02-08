{{-- карта yandex --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- тайтл страницы и мета-данные --}}
@section("title")
    [{{ $mapTitle }}] на карте
@endsection

{{-- секция контента --}}
@section("content")
    @include('backend.yandex_map.indexcontent')
@endsection
