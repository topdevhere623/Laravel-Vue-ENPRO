{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'Connector',
])

{{-- секция контента --}}
@section("content")
    @include('backend.connector.editcontent')
@endsection