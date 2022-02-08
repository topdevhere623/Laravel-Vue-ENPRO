{{-- список --}}

<div class="page-header">
    {{-- заголовок --}}
    <h2 class="page-title">{{ App\Models\Substation::title2 }}</h2>

    {{-- хлебные крошки --}}
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Главная</a></li>
        <li class="breadcrumb-item active">{{ App\Models\Substation::title2 }}</li>
    </ol>

    {{-- действия на странице --}}
    <div class="page-header-actions">
        <a href="{{ route('yandex_map.index',["model" => "substation"]) }}" class="button" data-toggle="tooltip"
           data-original-title="На карте">
            Показать на карте
        </a>
        <a href="{{ route('substation.edit') }}" class="button" data-toggle="tooltip"
           data-original-title="Создать новую запись">
            <span class="icon icon-add"></span>
            Создать новую
        </a>
    </div>
</div>

{{-- содержимое --}}
<div class="page-content main-content">
    <div class="row row-lg">
        <div class="col-lg-12">

            {{-- содержимое таблицы списком - vue-компонент --}}
            <model-spisok-substation-component>
            </model-spisok-substation-component>

        </div>
    </div>
</div>
