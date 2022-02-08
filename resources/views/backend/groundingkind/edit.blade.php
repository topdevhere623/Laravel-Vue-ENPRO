{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'Groundingkind',
])

{{-- секция контента --}}
@section("content")
    @include('backend.groundingkind.editcontent')
@endsection