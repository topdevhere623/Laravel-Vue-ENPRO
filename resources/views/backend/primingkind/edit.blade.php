{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'Primingkind',
])

{{-- секция контента --}}
@section("content")
    @include('backend.primingkind.editcontent')
@endsection