{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'Towerinsulatormountingkind',
])

{{-- секция контента --}}
@section("content")
    @include('backend.towerinsulatormountingkind.editcontent')
@endsection