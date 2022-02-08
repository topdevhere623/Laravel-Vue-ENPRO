{{-- список --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- тайтл страницы и мета-данные --}}
@section("title")
    {{ App\Models\TodoStatus::title2 }}
@endsection

{{-- секция контента --}}
@section("content")
    @include('backend.todostatus.indexcontent')
@endsection
