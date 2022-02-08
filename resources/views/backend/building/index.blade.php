{{-- список --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- тайтл страницы и мета-данные --}}
@section("title")
    {{ App\Models\Building::title2 }}
@endsection

{{-- секция контента --}}
@section("content")
    @include('backend.building.indexcontent')
@endsection
