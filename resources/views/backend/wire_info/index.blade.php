{{-- список --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- тайтл страницы и мета-данные --}}
@include('backend.lib.title', [
    'modelName'=> 'WireInfo',
])
   
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
    <div class="page-header">
        
        {{-- заголовок --}}
        <h2 class="page-title"></h2>

        {{-- хлебные крошки --}}
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">Главная</a></li>
            <li class="breadcrumb-item active">{{ App\Models\WireInfo::title2 }}</li>
        </ol>
        
         <div class="page-header-actions">
        <a
        
          href="{{ route('wire_info.edit') }}"
          class="button"
          data-toggle="tooltip"
          data-original-title="Создать новую запись"
        >
          <span class="icon icon-add" aria-hidden="true"></span>
          <span> Создать </span>
        </a>
      </div>

    </div>
        {{-- содержимое таблицы списком - vue-компонент --}}
        <div class="page-content">
                <div class="row row-lg">
                    <div class="col-lg-12">
                        {{-- содержимое таблицы списком - vue-компонент --}}
                        <model-spisok-wireinfo-component title-one="{{ App\Models\WireInfo::title2 }}" get-model-name="wireInfo" get-user-role='{{ Auth::user()->role[0]->name }}'>
                        </model-spisok-wireinfo-component>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    {{-- сообщение Пользователю, что недостаточно прав --}}
        @include('backend.blocks_edit.no_access_mesages')
    @endif
@endsection

