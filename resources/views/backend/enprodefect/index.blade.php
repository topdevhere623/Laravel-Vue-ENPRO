{{-- список --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- тайтл страницы и мета-данные --}}
@section("title")
    {{ App\Models\EnproDefect::title2 }}
@endsection

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
        {{-- содержимое --}}
 
        {{-- содержимое таблицы списком - vue-компонент --}}
        <model-spisok-enprodefect-component title-one="{{ App\Models\EnproDefect::title2 }}" get-user-role='{{ Auth::user()->role[0]->name }}'>
        </model-spisok-enprodefect-component>

    @else
    {{-- сообщение Пользователю, что недостаточно прав --}}
        @include('backend.blocks_edit.no_access_mesages')
    @endif
@endsection
