@if (count($aclineData) > 0)

    @if ($aclineName =='Все линии')
        <table>
            <tr>
                <td colspan="11" style="background-color: #f79646; color: #000000;">
                    {{ $aclineName }}
                </td>
            </tr>
            <tr>
                <td colspan="11" style="background-color: #f79646; color: #000000;">
                    Всего длина: {{ $aclineLenght }}
                </td>
            </tr>
            <tr>
                <td colspan="11" style="background-color: #f79646; color: #000000;">
                    Всего опор: {{ $aclineTowers }}
                </td>
            </tr>
            {{--<tr>--}}
            {{--<td colspan="11">--}}
            {{--{{ $aclineVoltage }} кВ--}}
            {{--</td>--}}
            {{--</tr>--}}
        </table>
    @endif

    <table>
        <tbody>
        @foreach($aclineData as $item)

            {{-- название линии --}}
            @if (isset($item['thisAclineName']) and $item['thisAclineName'])
                <tr>
                    <td colspan="11" style="background-color: #f79646; color: #000000;">
                        {{ $item['1'] }}
                    </td>
                </tr>
            @endif

            {{-- шапка --}}
            @if (isset($item['thisShapka']) and $item['thisShapka'])
                <tr>
                    <th style="background-color: #4f6228; color: #ebf1de;">
                        №
                    </th>
                    <th style="background-color: #4f6228; color: #ebf1de;">
                        Тип сегмента
                    </th>
                    <th style="background-color: #4f6228; color: #ebf1de;">
                        Сегмент
                    </th>
                    <th style="background-color: #4f6228; color: #ebf1de;">
                        Марка провода
                    </th>
                    <th style="background-color: #4f6228; color: #ebf1de;">
                        Длина, м
                    </th>
                    <th style="background-color: #4f6228; color: #ebf1de;">
                        Всего опор
                    </th>
                    <th style="background-color: #4f6228; color: #ebf1de;">
                        Ж/Б
                    </th>
                    <th style="background-color: #4f6228; color: #ebf1de;">
                        Дер.на ж/б пр.
                    </th>
                    <th style="background-color: #4f6228; color: #ebf1de;">
                        Дер.
                    </th>
                    <th style="background-color: #4f6228; color: #ebf1de;">
                        Металл.
                    </th>
                    <th style="background-color: #4f6228; color: #ebf1de;">
                        Проч.
                    </th>
                </tr>
            @endif

            {{-- итого - 1-й цвет --}}
            @if (isset($item['thisItogo1']) and $item['thisItogo1'])
                <tr>
                    @for($i=1; $i <= 11; $i++)
                        <td style="background-color: #f79646; color: #000000;">
                            @if (isset($item[$i]))
                                {{ $item[$i] }}
                            @endif
                        </td>
                    @endfor
                </tr>
            @endif

            {{-- итого - 2-й цвет --}}
            @if (isset($item['thisItogo2']) and $item['thisItogo2'])
                <tr>
                    @for($i=1; $i <= 11; $i++)
                        <td style="background-color: #4f6228; color: #ebf1de;">
                            @if (isset($item[$i]))
                                {{ $item[$i] }}
                            @endif
                        </td>
                    @endfor
                </tr>
            @endif

            {{-- основные данные - список сегментов --}}
            @if (!isset($item['thisAclineName']) and !isset($item['thisShapka']) and !isset($item['thisItogo1']) and !isset($item['thisItogo2']))
                <tr>
                    @for($i=1; $i <= 11; $i++)
                        <td>
                            @if (isset($item[$i]))
                                {{ $item[$i] }}
                            @endif
                        </td>
                    @endfor
                </tr>
            @endif

        @endforeach
        </tbody>
    </table>
@endif