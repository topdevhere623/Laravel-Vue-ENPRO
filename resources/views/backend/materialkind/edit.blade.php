{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'Materialkind',
])

{{-- секция контента --}}
@section("content")
    @include('backend.materialkind.editcontent')
@endsection