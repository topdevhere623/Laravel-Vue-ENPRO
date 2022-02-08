{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'Groundingmaterialkind',
])

{{-- секция контента --}}
@section("content")
    @include('backend.groundingmaterialkind.editcontent')
@endsection