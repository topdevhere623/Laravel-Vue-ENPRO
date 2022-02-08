{{-- контсруктор --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- тайтл страницы и мета-данные --}}
@section("title")
    {{ App\Models\Towerconstructionaggregate::title2 }}
@endsection

{{-- секция контента --}}
@section("content")

    <div class="page-header">
        {{-- заголовок --}}
        <h2 class="page-title">{{ App\Models\Towerconstructionaggregate::title2 }}</h2>

        {{-- хлебные крошки --}}
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">Главная</a></li>
            <li class="breadcrumb-item"><a href="{{ route('towerconstructionmaster.index') }}">Компоненты</a></li>
            <li class="breadcrumb-item active">{{ App\Models\Towerconstructionaggregate::title2 }}</li>
            <li class="breadcrumb-item"><a href="{{ route('towerinfo.index') }}">Марки опор</a></li>
        </ol>

        {{-- действия на странице --}}
        <div class="page-header-actions">
            <a href="{{ route('towerconstructionaggregate.edit') }}" class="button" data-toggle="tooltip"
               data-original-title="Создать новый">
                <span class="icon icon-add"></span>
                <span>Создать новый</span>
            </a>
        </div>

    </div>

    {{-- содержимое --}}
    <div class="page-content main-content">
        <div class="row row-lg">
            <div class="col-lg-12">

                {{-- содержимое таблицы списком - vue-компонент --}}
                <model-spisok-towerconstructionaggregate-component>
                </model-spisok-towerconstructionaggregate-component>

            </div>
        </div>
    </div>

@endsection