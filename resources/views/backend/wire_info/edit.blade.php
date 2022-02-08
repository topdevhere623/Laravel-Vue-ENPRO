{{-- список --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- тайтл страницы и мета-данные --}}
@include('backend.lib.title', [
    'modelName'=> 'WireInfo',
])

{{-- секция контента --}}
@section("content")

    {{-- проверка прав доступа к странице --}}
    @if (
        Auth::user()->isVendor() or
        Auth::user()->isAdmin() or
        Auth::user()->isManager() or
        Auth::user()->isDispatcher() or
        Auth::user()->isOperator() or
        Auth::user()->isMaster() or
        Auth::user()->isWorking()
        )
        {{-- права есть --}}

        {{-- карточка линии --}}
        <model-edit-wireinfo-component get-model-name="wireInfo"  :get-kind-models='@json($kindModels)'  title-one="{{ App\Models\WireInfo::title2 }}" :get-model-id='{{ isset($id) ? $id : 0 }}' :from-id="{{ isset($fromId) ? $fromId : 0 }}">
        </model-edit-wireinfo-component>

    @else
        {{-- сообщение Пользователю, что недостаточно прав --}}
        @include('backend.blocks_edit.no_access_mesages')
    @endif

@endsection
