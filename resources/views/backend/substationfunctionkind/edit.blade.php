{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'Substationfunctionkind',
])

{{-- секция контента --}}
@section("content")
    @include('backend.substationfunctionkind.editcontent')
@endsection