{{-- список --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- тайтл страницы и мета-данные --}}
@section("title")
    {{ App\Models\Customer::title2 }}
@endsection

{{-- секция контента --}}
@section("content")
    {{-- список --}}

    <div class="page-header">
        {{-- заголовок --}}
        <h2 class="page-title">{{ App\Models\Customer::title2 }}</h2>

        {{-- хлебные крошки --}}
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">Главная</a></li>
            <li class="breadcrumb-item active">{{ App\Models\Customer::title2 }}</li>
        </ol>

        {{-- действия на странице --}}
        <div class="page-header-actions">
            <a href="{{ route('customer.edit') }}" class="btn btn-lg btn-icon btn-primary btn-round" data-toggle="tooltip"
               data-original-title="Создать новую запись">
                <i class="icon md-plus" aria-hidden="true"></i>
            </a>
        </div>

    </div>

    {{-- содержимое --}}
    <div class="page-content main-content">
        <div class="row row-lg">
            <div class="col-lg-12">

                {{-- содержимое таблицы списком - vue-компонент --}}
                <model-spisok-customer-component get-user-role='{{ Auth::user()->role[0]->name }}'>
                </model-spisok-customer-component>

            </div>
        </div>
    </div>

@endsection
