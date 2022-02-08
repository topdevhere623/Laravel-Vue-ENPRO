{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'TodoStatus',
])

{{-- секция контента --}}
@section("content")
    @include('backend.todostatus.editcontent')
@endsection