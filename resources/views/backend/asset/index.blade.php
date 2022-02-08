{{-- список --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- тайтл страницы и мета-данные --}}
@section("title")
    {{ App\Models\Asset::title2 }}
@endsection

{{-- секция контента --}}
@section("content")
    @include('backend.asset.indexcontent')
@endsection
