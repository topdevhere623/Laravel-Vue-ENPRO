{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'Layingconditionkind',
])

{{-- секция контента --}}
@section("content")
    @include('backend.layingconditionkind.editcontent')
@endsection