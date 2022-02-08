{{-- карта --}}

{{-- лайоут --}}
@extends("backend.layouts.report")

{{-- тайтл страницы и мета-данные --}}
@section("title")
    ТЕХНИЧЕСКИЙ ПАСПОРТ ВЛ
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

        {{-- содержимое --}}
        <div class="custom-page">
            <div style="margin:30px 75px 200px 150px;">

                ООО «Режевские электрические сети
                <br>
                _____________________________________
                <br>
                Участок______________________________

                <h2>ТЕХНИЧЕСКИЙ ПАСПОРТ ВЛ</h2>

                ВЛ <span class="calc">{{ $acline->identifiedobject->basevoltage->name }}</span> кВ <span class="calc">{{ $acline->identifiedobject->name }}</span> от ПС ___________
                <br>
                <small><i>(наименование ВЛ)</i></small>
                <small><i>(наименование ПС)</i></small>

                <h4>
                    Основные данные:
                </h4>

                1.Протяженность линии всего: <span class="calc">{{ round($aclineLength / 1000,2) }}</span> км, в т.ч.:
                <br>
                магистрали ___________ км, совместной подвески ___________ км, кабельной вставки ___________ км, марка и сечение кабеля ___________ , количество отпаек ___________ шт.
                <br>
                2.Количество опор всего: <span class="calc">{{ $towers->count() }}</span> шт.,
                <br>
                в т.ч. деревянные без ж\б приставок <span class="calc">{{ count($towersWoodWithoutAnnex) }}</span> шт.
                {{--@if (count($towersWoodWithoutAnnex)>0)--}}
                {{--, (--}}
                {{--<span class="calc">--}}
                {{--@foreach($towersWoodWithoutAnnex as $item)--}}
                {{--{{ $item->identifiedobject->name }}--}}
                {{--@if (!$loop->last)--}}
                {{--,--}}
                {{--@endif--}}
                {{--@endforeach--}}
                {{--</span>--}}
                {{--),--}}
                {{--@endif--}}
                <br>
                деревянные с ж/б приставками <span class="calc">{{ count($towersWoodWithAnnex) }}</span> шт.
                {{--@if (count($towersWoodWithAnnex)>0)--}}
                {{--, (--}}
                {{--<span class="calc">--}}
                {{--@foreach($towersWoodWithAnnex as $item)--}}
                {{--{{ $item->identifiedobject->name }}--}}
                {{--@if (!$loop->last)--}}
                {{--,--}}
                {{--@endif--}}
                {{--@endforeach--}}
                {{--</span>--}}
                {{--),--}}
                {{--@endif--}}
                <br>
                железобетонные <span class="calc">{{ count($towersIronConcrete) }}</span> шт.
                {{--@if (count($towersIronConcrete)>0)--}}
                {{--, (--}}
                {{--<span class="calc">--}}
                {{--@foreach($towersIronConcrete as $item)--}}
                {{--{{ $item->identifiedobject->name }}--}}
                {{--@if (!$loop->last)--}}
                {{--,--}}
                {{--@endif--}}
                {{--@endforeach--}}
                {{--</span>--}}
                {{--)--}}
                {{--@endif--}}
                <br>
                3. Марка и сечение провода по магистрали ___________
                <br>
                4.Изоляторы (тип, количество) шт. ___________
                <br>
                5.Количество и номера ТП <span class="calc"> </span> шт.
                {{--@if (count($acline->substations)>0)--}}
                {{--, №--}}
                {{--<span class="calc">--}}
                {{--@foreach($acline->substations as $item)--}}
                {{--{{ $item->identifiedobject->name }}--}}
                {{--@if (!$loop->last)--}}
                {{--,--}}
                {{--@endif--}}
                {{--@endforeach--}}
                {{--</span>--}}
                {{--@endif--}}
                <br>
                6.Средняя длина пролета: <span class="calc">{{  $aclineLengthAverage }}</span> м.

                <h4>
                    Список опор на линии:
                </h4>

                <div class="row row-lg">
                    <div class="col-lg-12">

                        <table class="table table-bordered">
                            <thead>
                            <th> №</th>
                            <th> Диспетчерский номер</th>
                            <th> Марка</th>
                            <th> Назначение</th>
                            <th> Материал</th>
                            <th> Конструкция</th>
                            <th> Стоек</th>
                            <th> Оттяжка</th>
                            <th> Подкос</th>
                            <th> Приставка</th>
                            <th> Разъединитель</th>
                            </thead>
                            <tbody>

                            @if (count($towers) > 0)
                                @php $i=0; @endphp
                                @foreach($towers as $item)
                                    @if ($item->fict_tp == 0)

                                        @php $i++; @endphp
                                        <tr>
                                            <td>
                                            {{ $i }}
                                            <td>
                                                {{ $item->identifiedobject->localname }}
                                            </td>
                                            <td>
                                                {{ $item->towerinfo->name }}
                                            </td>
                                            <td>
                                                {{ $item->towerkind->name }}
                                            </td>
                                            <td>
                                                {{ $item->towermaterial->name }}
                                            </td>
                                            <td>
                                                {{ $item->towerconstruction->name }}
                                            </td>
                                            <td>
                                                {{ $item->propn }}
                                            </td>
                                            <td>
                                                @if( $item->guy === 'left' or $item->guy === 'right')
                                                    &#10004;
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if( $item->strut <> null and $item->strut <> '' and $item->strut <> 'no')
                                                    &#10004;
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if( $item->annex <> null and $item->annex <> '' and $item->annex <> 'no')
                                                    &#10004;
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if( $item->disconnector <> null and $item->disconnector <> '' and $item->disconnector <> 'no')
                                                    &#10004;
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                            </tbody>

                        </table>
                    </div>
                </div>

                <h4>
                    Список воздушных пролетов:
                </h4>

                <div class="row row-lg">
                    <div class="col-lg-12">

                        <table class="table table-bordered">
                            <thead>
                            <th> №</th>
                            <th> Пролет</th>
                            <th> Длина пролета, м</th>
                            <th> Марка провода</th>
                            <th> Сечение</th>
                            <th> Габарит</th>
                            <th> Число проводов</th>
                            <th> Длина провода</th>
                            </thead>
                            <tbody>

                            @if (count($span701) > 0)
                                @php $i=0; @endphp
                                @foreach($span701 as $item)
                                    @php $i++; @endphp
                                    <tr>
                                        <td>
                                            {{ $i }}
                                        </td>
                                        <td>
                                            {{ $item->startIO->name }} - {{ $item->endIO->name }}
                                        </td>
                                        <td>
                                            {{ $item->spanlength }}
                                        </td>
                                        <td>
                                            {{ $item->aclinesegment->wiremark->assetinfokey }}
                                        </td>
                                        <td>
                                            {{ $item->aclinesegment->wires }}
                                        </td>
                                        <td>
                                            {{ $item->gabarit }}
                                        </td>
                                        <td>
                                            {{ $item->aclinesegment->wiren }}
                                        </td>
                                        <td>
                                            {{ $item->aclinesegment->wirelength }}
                                        </td>
                                    </tr>
                            @endforeach
                            @endif
                            <tbody>

                        </table>

                    </div>
                </div>

                <h4>
                    Список кабельных участков:
                </h4>

                <div class="row row-lg">
                    <div class="col-lg-12">

                        <table class="table table-bordered">
                            <thead>
                            <th> №</th>
                            <th> Кабельный пролет</th>
                            <th> Длина участка, м</th>
                            <th> Марка кабеля</th>
                            <th> Сечение</th>
                            <th> Проводов в фазе</th>
                            <th> Условие прокладки</th>
                            <th> Кабелей в траншее</th>
                            </thead>
                            <tbody>

                            @if (count($span702) > 0)
                                @php $i=0; @endphp
                                @foreach($span702 as $item)
                                    @php $i++; @endphp
                                    <tr>
                                        <td>
                                            {{ $i }}
                                        </td>
                                        <td>
                                            {{ $item->startIO->name }} - {{ $item->endIO->name }}
                                        </td>
                                        <td>
                                            {{ $item->spanlength }}
                                        </td>
                                        <td>
                                            {{ $item->aclinesegment->wiremark->assetinfokey }}
                                        </td>
                                        <td>
                                            {{ $item->aclinesegment->wires }}
                                        </td>
                                        <td>
                                            {{ $item->aclinesegment->wirephasen }}
                                        </td>
                                        <td>
                                            {{ $item->aclinesegment->layingcondition->name }}
                                        </td>
                                        <td>
                                            {{ $item->aclinesegment->cabelsn }}
                                        </td>
                                    </tr>
                            @endforeach
                            @endif
                            <tbody>

                        </table>

                    </div>
                </div>

                <div style="text-align: right; margin-top: 100px; font-size: larger;">
                    АФ/СЭ ЗЭС/СРС/Пр/ф.26/2015
                </div>

            </div>
        </div>

    @else
        {{-- сообщение Пользователю, что недостаточно прав --}}
        @include('backend.blocks_edit.no_access_mesages')
    @endif

@endsection

{{-- секция моих скриптов --}}
@section("scripts")
    <style>
        .calc {
            font-weight: bold;
        / / font-size: larger;
        }

        h1 {
            text-align: center;
        }

        h2 {
            text-align: center;
        }

        h3 {
            text-align: center;
        }

        h4 {
            margin: 40px 0 20px 0;
            text-align: center;
        }

        table tr {
            vertical-align: middle;
            text-align: center;
        }

        table td {
            text-align: center;
        }
    </style>
@endsection
