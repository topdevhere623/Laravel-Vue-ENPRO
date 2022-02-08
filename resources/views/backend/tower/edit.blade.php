{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'Tower',
])

{{-- секция контента --}}
@section("content")
    @include('backend.tower.editcontent')
@endsection