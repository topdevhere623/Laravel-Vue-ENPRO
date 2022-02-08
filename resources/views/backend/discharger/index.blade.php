{{-- список --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- тайтл страницы и мета-данные --}}
@section("title")
    {{ App\Models\Discharger::title2 }}
@endsection

{{-- секция контента --}}
@section("content")
    @include('backend.discharger.indexcontent')
@endsection
