{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'Tasktype',
])

{{-- секция контента --}}
@section("content")
    @include('backend.tasktype.editcontent')
@endsection