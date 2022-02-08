{{-- список --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- тайтл страницы и мета-данные --}}
@section("title")
    {{ App\Models\Classname::title2 }}
@endsection

{{-- секция контента --}}
@section("content")
    @include('backend.classname.indexcontent')
@endsection
