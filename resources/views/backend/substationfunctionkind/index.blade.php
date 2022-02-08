{{-- список --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- тайтл страницы и мета-данные --}}
@section("title")
    {{ App\Models\Substationfunctionkind::title2 }}
@endsection

{{-- секция контента --}}
@section("content")
    @include('backend.substationfunctionkind.indexcontent')
@endsection
