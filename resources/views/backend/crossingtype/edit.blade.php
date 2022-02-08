{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'Crossingtype',
])

{{-- секция контента --}}
@section("content")
    @include('backend.crossingtype.editcontent')
@endsection