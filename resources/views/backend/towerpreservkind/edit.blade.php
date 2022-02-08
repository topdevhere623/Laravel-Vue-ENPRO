{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'Towerpreservkind',
])

{{-- секция контента --}}
@section("content")
    @include('backend.towerpreservkind.editcontent')
@endsection