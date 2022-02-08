{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'BaseVoltage',
])

{{-- секция контента --}}
@section("content")
    @include('backend.basevoltage.editcontent')
@endsection
