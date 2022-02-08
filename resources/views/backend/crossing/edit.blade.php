{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'Crossing',
])

{{-- секция контента --}}
@section("content")
    @include('backend.crossing.editcontent')
@endsection