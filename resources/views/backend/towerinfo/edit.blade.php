{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'Towerinfo',
])

{{-- секция контента --}}
@section("content")

    {{-- редактор марок опор --}}
    <model-edit-towerinfo-component :get-model-id='{{ isset($content->id) ? $content->id : 0 }}'>
    </model-edit-towerinfo-component>

@endsection