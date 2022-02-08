{{-- контсруктор --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- тайтл страницы и мета-данные --}}
@section("title")
    Компоненты
@endsection

{{-- секция контента --}}
@section("content")

    <div class="page-header">
        {{-- заголовок --}}
        <h2 class="page-title">Компоненты</h2>

        {{-- хлебные крошки --}}
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">Главная</a></li>
            <li class="breadcrumb-item"><a href="{{ route('towerinfo.index') }}">Марки опор</a></li>
            <li class="breadcrumb-item"><a href="{{ route('towerconstructionaggregate.index') }}">{{ App\Models\Towerconstructionaggregate::title2 }}</a></li>
            <li class="breadcrumb-item active">Компоненты</li>
        </ol>

    </div>

    {{-- содержимое --}}
    <div class="page-content main-content">
        <div class="row row-lg">
            <div class="col-lg-12">

                {{-- содержимое таблицы списком - vue-компонент --}}
                <tower-construction-master-component>
                </tower-construction-master-component>

            </div>
        </div>
    </div>

@endsection