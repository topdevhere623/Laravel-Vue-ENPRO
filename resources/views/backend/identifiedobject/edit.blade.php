{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'Identifiedobject',
])

{{-- секция контента --}}
@section("content")
    @include('backend.identifiedobject.editcontent')
@endsection