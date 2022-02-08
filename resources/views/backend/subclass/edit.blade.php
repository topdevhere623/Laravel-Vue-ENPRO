{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'Subclass',
])

{{-- секция контента --}}
@section("content")
    @include('backend.subclass.editcontent')
@endsection