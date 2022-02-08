{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'Discharger',
])

{{-- секция контента --}}
@section("content")
    @include('backend.discharger.editcontent')
@endsection