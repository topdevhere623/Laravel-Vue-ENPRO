{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'Substationinfo',
])

{{-- секция контента --}}
@section("content")
    @include('backend.substationinfo.editcontent')
@endsection