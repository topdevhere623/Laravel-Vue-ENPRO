{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'Ptendinsulatorkind',
])

{{-- секция контента --}}
@section("content")
    @include('backend.ptendinsulatorkind.editcontent')
@endsection