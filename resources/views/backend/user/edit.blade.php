{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'User',
])

{{-- секция контента --}}
@section("content")

    {{-- проверка прав доступа к странице --}}
    @if (
        Auth::user()->isVendor() or
        Auth::user()->isAdmin()
        )
        {{-- права есть --}}

        <form class="form-horizontal" name="form_user" action="{{ route('user.update', ['id'=>$content->id]) }}" method="POST" enctype="multipart/form-data">
            {!! method_field('POST') !!}
            {{ @csrf_field() }}

            <div class="page-header">
                {{-- заголовок --}}
                <h2 class="page-title">
                    {{ App\Models\User::title1 }}
                </h2>

                {{-- хлебные крошки --}}
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Главная</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('user.index') }}">{{ App\Models\User::title2 }}</a></li>
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
                            <div class="panel-body">

                                <div class="example-wrap">
                                    <div class="nav-tabs-horizontal" data-plugin="tabs">
                                        <ul class="nav nav-tabs" role="tablist">

                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link active" data-toggle="tab" href="#tabsMain" aria-controls="tabsMain" role="tab" aria-selected="true">
                                                    Основное
                                                </a>
                                            </li>

                                        </ul>
                                        <div class="tab-content pt-20">

                                            {{-- вкладка Основное --}}
                                            <div class="tab-pane active" id="tabsMain" role="tabpanel">
                                                <div class="row">
                                                    <div class="col-6">

                                                        {{-- редактирование названия --}}
                                                        @include('backend.blocks_edit.name',[
                                                        'myFieldTitle' => 'Имя Пользователя',
                                                        'myFieldName' => 'username',
                                                        'myRequired' => 1,
                                                        ])

                                                        {{-- редактирование role (роли) --}}
                                                        @include('backend.blocks_edit.options_role')

                                                        {{-- email --}}
                                                        <div class="row">
                                                            <div class="form-group col-md-12">
                                                                <h4 class="example-title">Логин (Email)</h4>
                                                                <input type="text" class="form-control" value="{{ $content->email }}" name="email" placeholder="Логин (или E-mail)">
                                                                <small>Не должна повторяться с имеющимися Пользователями</small>
                                                            </div>
                                                        </div>

                                                        {{-- пароль --}}
                                                        <div class="row">
                                                            <div class="form-group col-md-12">
                                                                <h4 class="example-title">Новый пароль</h4>
                                                                <input type="password" class="form-control" value="" name="newPassword" placeholder="пароль"
                                                                       @if (!isset($content->password))
                                                                       required
                                                                    @endif
                                                                >
                                                                <small>Введите новый, если хотите поменять. Старый пароль вводить не нужно</small>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="col-6">

                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
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
