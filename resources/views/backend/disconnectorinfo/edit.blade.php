{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'DisconnectorInfo',
])

{{-- секция контента --}}
@section("content")
    @include('backend.disconnectorinfo.editcontent')
@endsection
