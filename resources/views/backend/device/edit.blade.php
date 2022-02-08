{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'Device',
])

{{-- секция контента --}}
@section("content")
    @include('backend.device.editcontent')
@endsection