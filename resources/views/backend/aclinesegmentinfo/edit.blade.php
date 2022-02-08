{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'Aclinesegmentinfo',
])

{{-- секция контента --}}
@section("content")
    @include('backend.aclinesegmentinfo.editcontent')
@endsection