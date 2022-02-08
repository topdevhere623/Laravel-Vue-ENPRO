{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'Todo',
])

{{-- секция контента --}}
@section("content")
    @include('backend.todo.editcontent')
@endsection