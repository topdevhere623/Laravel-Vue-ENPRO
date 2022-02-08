{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'Asset',
])

{{-- секция контента --}}
@section("content")
    @include('backend.asset.editcontent')
@endsection