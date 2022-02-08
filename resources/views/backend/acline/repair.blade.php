{{-- список --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- тайтл страницы и мета-данные --}}
@section("title")
    Целостность данных ЛЭП
@endsection

{{-- секция контента --}}
@section("content")

    {{-- проверка прав доступа к странице --}}
    @if (
        Auth::user()->isVendor() or
        Auth::user()->isAdmin() or
        Auth::user()->isManager() or
        Auth::user()->isOperator()
        )
        {{-- права есть --}}

        <div class="page-header">
            {{-- заголовок --}}
            <h2 class="page-title">Целостность данных ЛЭП</h2>

            {{-- хлебные крошки --}}
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin') }}">Главная</a></li>
                <li class="breadcrumb-item active">{{ App\Models\Acline::title2 }}</li>
            </ol>

        </div>

        @php
            $myArrModeNames =[
                [
                    'name' => 'Корректировка длин всех пролетов ЛЭП',
                    'mode' => 'SpanLenght',
                ],
                [
                    'name' => 'Поиск и очистка от несвязанных ни с какой линией ЛЭП строк',
                    'mode' => 'FindLost',
                ],
                [
                    'name' => 'Опоры, участвующие в совместном подвесе',
                    'mode' => 'TowerInDoubleAcline',
                ],
                [
                    'name' => 'Прикрепленные изображения',
                    'mode' => 'Images',
                ],
                [
                    'name' => 'Удаления всех записей, помеченных на удаление, и оптимизация всех таблиц',
                    'mode' => 'DeletedAt',
                ],
                ];
        @endphp

        {{-- содержимое --}}
        <div class="page-content main-content">

            <div class="row row-lg">
                <ul>
                    @php
                        $n = 0;
                        foreach($myArrModeNames as $item)
                            {
                                $n++;
                                echo '<li><a href="#line'.((String)ceil($n/2)).'">'.$item['name'].'</a></li>';
                            }
                    @endphp
                </ul>
            </div>

            @php
                for ($i = 0; $i < count($myArrModeNames); $i = $i + 2)
                {
                    echo '<a name="line'.((String)ceil(($i + 1)/2)).'">';
                        echo '<div class="row row-lg mt-10">';
                            echo '<div class="col-lg-6">';
                                echo '<base-repair-component get-mode="'.$myArrModeNames[$i]['mode'].'" get-title="'.$myArrModeNames[$i]['name'].'">';
                                echo '</base-repair-component>';
                            echo '</div>';

                            echo '<div class="col-lg-6">';
                            if (isset($myArrModeNames[$i + 1]))
                                {
                                echo '<base-repair-component get-mode="'.$myArrModeNames[$i + 1]['mode'].'" get-title="'.$myArrModeNames[$i + 1]['name'].'">';
                                echo '</base-repair-component>';
                                }
                            echo '</div>';
                        echo '</div>';
                    echo '</a>';
                }
            @endphp
        </div>

    @else
        {{-- сообщение Пользователю, что недостаточно прав --}}
        @include('backend.blocks_edit.no_access_mesages')
    @endif

@endsection
