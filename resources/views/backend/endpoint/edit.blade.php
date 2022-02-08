{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'Endpoint',
])

{{-- секция контента --}}
@section("content")
    @include('backend.endpoint.editcontent')
@endsection