{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'Bbsecinsulatorinfo',
])

{{-- секция контента --}}
@section("content")
    @include('backend.bbsecinsulatorinfo.editcontent')
@endsection