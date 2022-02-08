{{-- список --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- тайтл страницы и мета-данные --}}
@section("title")
    {{ App\Models\Towerpreservkind::title2 }}
@endsection

{{-- секция контента --}}
@section("content")
    @include('backend.towerpreservkind.indexcontent')
@endsection
