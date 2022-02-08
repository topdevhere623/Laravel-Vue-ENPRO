{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'Company',
])

{{-- секция контента --}}
@section("content")
    @include('backend.company.editcontent')
@endsection