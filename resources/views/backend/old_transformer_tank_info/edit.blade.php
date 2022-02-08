{{-- список --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- тайтл страницы и мета-данные --}}
@include('backend.lib.title', [
    'modelName'=> 'OldTransformerTankInfo',
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
        <model-edit-oldtransformertankinfo-component 
            :get-model-id='{{ isset($id) ? $id : 0 }}' 
            :from-id='{{ isset($fromId) ? $fromId : 0 }}'
            title-one="{{ App\Models\OldTransformerTankInfo::title2 }}"
            >
            
        </model-edit-oldtransformertankinfo-component>

    @else
        {{-- сообщение Пользователю, что недостаточно прав --}}
        @include('backend.blocks_edit.no_access_mesages')
    @endif

@endsection
