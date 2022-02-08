{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'Dischargerinfo',
])

{{-- секция контента --}}
@section("content")
    @include('backend.dischargerinfo.editcontent')
@endsection