{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'Towerconstructionaggregate',
])

{{-- секция контента --}}
@section("content")

    {{-- редактор сборных агрегатов --}}
    <model-edit-towerconstructionaggregate-component :get-model-id='{{ isset($content->id) ? $content->id : 0 }}'>
    </model-edit-towerconstructionaggregate-component>

@endsection