{{-- список --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- тайтл страницы и мета-данные --}}
@section("title")
    {{ App\Models\Disconnector::title2 }}
@endsection

{{-- секция контента --}}
@section("content")
    @include('backend.disconnector.indexcontent')
@endsection
