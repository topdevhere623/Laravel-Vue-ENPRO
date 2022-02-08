{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'Disconnector',
])

{{-- секция контента --}}
@section("content")
    @include('backend.disconnector.editcontent')
@endsection