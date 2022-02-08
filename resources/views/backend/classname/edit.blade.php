{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'Classname',
])

{{-- секция контента --}}
@section("content")
    @include('backend.classname.editcontent')
@endsection