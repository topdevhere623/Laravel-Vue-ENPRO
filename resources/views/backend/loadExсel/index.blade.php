{{-- список --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

@section('title')
    Загрузка из ексел
@endsection
   
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
        {{-- секция контента --}}
  
        {{-- содержимое таблицы списком - vue-компонент --}}
        <excel-load-file-component title-one='@json($content)' get-models='@json($models)'  get-user-role='{{ Auth::user()->role[0]->name }}'>
        </model-spisok-wireinfo-component>
    
    @else
    {{-- сообщение Пользователю, что недостаточно прав --}}
        @include('backend.blocks_edit.no_access_mesages')
    @endif
@endsection

