{{-- список --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- тайтл страницы и мета-данные --}}
@section("title")
    {{ App\Models\Crossingtype::title2 }}
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
        <div class="page-header">
            {{-- заголовок --}}
            <h2 class="page-title">{{ App\Models\Crossingtype::title2 }}</h2>

            {{-- хлебные крошки --}}
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin') }}">Главная</a></li>
                <li class="breadcrumb-item active">{{ App\Models\Crossingtype::title2 }}</li>
            </ol>
            {{-- действия на странице --}}
            <div class="page-header-actions">
                @if (
                    Auth::user()->isVendor() or
                    Auth::user()->isAdmin() or
                    Auth::user()->isManager() or
                    Auth::user()->isOperator()
                    )
                    <a href="{{ route('crossingtype.edit') }}" class="button" data-toggle="tooltip" data-original-title="Создать новую запись">
                        <span class="icon icon-add" aria-hidden="true"></span>
                        <span>
                            Создать новую
                        </span>
                    </a>
                @endif
            </div>
        </div>
        {{-- содержимое --}}
        <div class="page-content main-content">
            <div class="row row-lg">
                <div class="col-lg-12">
                    {{-- содержимое таблицы списком - vue-компонент --}}
                    <model-spisok-crossingtype-component title-one="{{ App\Models\Crossingtype::title2 }}" get-user-role='{{ Auth::user()->role[0]->name }}'>
                    </model-spisok-crossingtype-component>
                </div>
            </div>
        </div>
    @else
    {{-- сообщение Пользователю, что недостаточно прав --}}
        @include('backend.blocks_edit.no_access_mesages')
    @endif
@endsection
