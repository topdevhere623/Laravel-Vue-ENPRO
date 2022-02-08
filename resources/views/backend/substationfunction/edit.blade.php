{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'Substationfunction',
])

{{-- секция контента --}}
@section("content")
    @include('backend.substationfunction.editcontent')
@endsection