{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'TodoStage',
])

{{-- секция контента --}}
@section("content")
    @include('backend.todostage.editcontent')
@endsection