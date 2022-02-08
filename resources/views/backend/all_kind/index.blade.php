
{{-- список --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- тайтл страницы и мета-данные --}}

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
        <div>
            <model-spisok-allkind-wrapper :kinds="{{ json_encode($kinds) }}" user_role="{{ Auth::user()->role[0]->name }}"></model-spisok-allkind-wrapper>
        </div>
    @else
        {{-- сообщение Пользователю, что недостаточно прав --}}
        @include('backend.blocks_edit.no_access_mesages')
    @endif
    </script>
@endsection
