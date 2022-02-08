{{-- список --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- тайтл страницы и мета-данные --}}
@section("title")
    {{ App\Models\Towerkind::title2 }}
@endsection

{{-- секция контента --}}
@section("content")

    <div class="page-header">
        {{-- заголовок --}}
        <h2 class="page-title">{{ App\Models\Towerkind::title2 }}</h2>

        {{-- хлебные крошки --}}
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">Главная</a></li>
            <li class="breadcrumb-item active">{{ App\Models\Towerkind::title2 }}</li>
        </ol>

    </div>

    {{-- содержимое --}}
    <div class="page-content">
        <div class="panel">
            <div class="panel-body container-fluid">
                <div class="row row-lg">
                    <div class="col-lg-12">

                        {{-- содержимое таблицы списком - vue-компонент --}}
                        <model-spisok-towerkind-component>
                        </model-spisok-towerkind-component>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
