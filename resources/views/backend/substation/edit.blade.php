{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'Substation',
])

{{-- секция контента --}}
@section("content")
    @include('backend.substation.editcontent')
@endsection
