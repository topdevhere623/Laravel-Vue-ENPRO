{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'Picturekind',
])

{{-- секция контента --}}
@section("content")
    @include('backend.picturekind.editcontent')
@endsection