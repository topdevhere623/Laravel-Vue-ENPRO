{{-- список --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- тайтл страницы и мета-данные --}}
@section("title")
    {{ App\Models\Tasktype::title2 }}
@endsection

{{-- секция контента --}}
@section("content")
    @include('backend.tasktype.indexcontent')
@endsection
