{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'Building',
])

{{-- секция контента --}}
@section("content")
    @include('backend.building.editcontent')
@endsection