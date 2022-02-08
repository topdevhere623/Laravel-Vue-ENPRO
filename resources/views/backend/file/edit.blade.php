{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'File',
])

{{-- секция контента --}}
@section("content")
    @include('backend.file.editcontent')
@endsection