{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'Picturetype',
])

{{-- секция контента --}}
@section("content")
    @include('backend.picturetype.editcontent')
@endsection