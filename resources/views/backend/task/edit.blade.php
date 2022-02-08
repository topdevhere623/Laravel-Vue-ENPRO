{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'Task',
])

{{-- секция контента --}}
@section("content")
    @include('backend.task.editcontent')
@endsection