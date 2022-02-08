{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'Customer',
])

{{-- секция контента --}}
@section("content")
    @include('backend.customer.editcontent')
@endsection