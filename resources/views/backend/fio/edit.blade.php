{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'Fio',
])

{{-- секция контента --}}
@section("content")
    @include('backend.fio.editcontent')
@endsection