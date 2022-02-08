{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'Address',
])

{{-- секция контента --}}
@section("content")
    @include('backend.address.editcontent')
@endsection