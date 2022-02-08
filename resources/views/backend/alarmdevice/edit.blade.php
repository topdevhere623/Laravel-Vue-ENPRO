{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'Alarmdevice',
])

{{-- секция контента --}}
@section("content")
    @include('backend.alarmdevice.editcontent')
@endsection