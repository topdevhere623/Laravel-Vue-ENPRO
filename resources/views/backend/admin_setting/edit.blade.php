{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'AdminSetting',
])

{{-- секция контента --}}
@section("content")

    {{-- проверка прав доступа к странице --}}
    @if (
        Auth::user()->isVendor()
        )
        {{-- права есть --}}

        <form class="form-horizontal" name="form_admin_setting" action="{{ route('admin_setting.update', ['id'=>$content->id]) }}" method="POST" enctype="multipart/form-data">
            {!! method_field('POST') !!}
            {{ @csrf_field() }}

            <div class="page-header">
                {{-- заголовок --}}
                <h2 class="page-title">
                    {{ App\AdminModels\AdminSetting::title1 }}
                </h2>

                {{-- хлебные крошки --}}
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Главная</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin_setting.index') }}">{{ App\AdminModels\AdminSetting::title2 }}</a></li>
                    <li class="breadcrumb-item active">Редактирование</li>
                </ol>

                {{-- действия на странице --}}
                <div class="page-header-actions">
                    {{-- кнопка сохранить --}}
                    @include('backend.blocks_edit.b_save')
                </div>

            </div>

            {{-- содержимое --}}
            <div class="page-content">

                <input type="hidden" name="id" value="{{ $content->id }}">

                <div class="row">
                    <div class="col-lg-12">

                        <div class="panel panel-bordered form-icons">
                            <div class="panel-heading">
                                <h3 class="panel-title">Данные</h3>
                            </div>
                            <div class="panel-body">

                                {{-- параметр - редактировать нельзя! --}}
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <h4 class="example-title">Параметр</h4>
                                        <input type="text" class="form-control my-5" value="{{ $content->key }}" name="key" disabled>
                                    </div>
                                </div>

                                {{-- значение --}}
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <h4 class="example-title">Значение</h4>
                                        <input type="text" class="form-control my-5" value="{{ $content->value }}" placeholder="значение" name="value">
                                    </div>
                                </div>

                                {{-- редактирование поля комментария --}}
                                @include('backend.blocks_edit.comment')

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>

    @else
        {{-- сообщение Пользователю, что недостаточно прав --}}
        @include('backend.blocks_edit.no_access_mesages')
    @endif

@endsection
