{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'Substationtpinfo',
])

{{-- секция контента --}}
@section("content")
    @include('backend.substationtpinfo.editcontent')
@endsection