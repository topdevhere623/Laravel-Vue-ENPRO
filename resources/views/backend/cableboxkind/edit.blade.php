{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'Cableboxkind',
])

{{-- секция контента --}}
@section("content")
    @include('backend.cableboxkind.editcontent')
@endsection