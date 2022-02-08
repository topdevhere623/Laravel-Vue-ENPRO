{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main_full_screen")


{{-- вывод заголовка страницы --}}
@include('backend.lib.title', [
    'modelName'=> 'Substation',
])

{{-- секция контента --}}
@section("content")

    <div class="custom-page">
        <div class="page-content main-content">
            @include('backend.substation.parsecontent')
            {{--индикатор ожидания ajax--}}
            <img src='/public/uploads/loading.gif' id='loading' style='display:none; width: 150px; position:fixed; margin:auto; top:0; bottom:0; left:0; right:0; z-index:9999;'/>

            {{-- панель навигации --}}


            <div>

                {{-- фильтр стилизация карты яндекса --}}
                @include("backend.lib.filter")
                {{-- карта --}}
                <div id="map" class="map-auto-height"></div>

            </div>
        </div>
        <div class="sidebar-panel">
            <div class="form-group">
                @include('backend.substation.rightForm')
            </div>
            <hr>
            {{-- действия на странице --}}
            <div class="form-group">
                <span>Действия на странице</span>
                <div class="page-header-actions">
                    @if ($substation->xsde)
                        <a href="{{ route('substation.parse', ['id'=>$substation->id]) }}"  class="button" data-toggle="tooltip" onclick="sendAndParseXsde(this.href, []); return false"
                           data-original-title="Сохранить">
                            Разобрать заново
                        </a>
                    @endif
                    <a href="{{ route('substation.index') }}" class="button bordered" data-toggle="tooltip"
                       data-original-title="">
                        Закрыть
                    </a>
                    <scheme_controls></scheme_controls>
{{--                    <a href="{{ route('substation.clear', ['id'=>$substation->id]) }}" class="button bordered" data-toggle="tooltip"--}}
{{--                       data-original-title="" onclick="clearSubstationScheme(this); return false;">--}}
{{--                        Очистить--}}
{{--                    </a>--}}
{{--                    <a href="{{ route('substation.update', ['id'=>$substation->id]) }}" onclick="saveSubstationScheme(this); return false" class="button" data-toggle="tooltip"--}}
{{--                       data-original-title="Сохранить" >--}}
{{--                        Сохранить--}}
{{--                    </a>--}}
                </div>
            </div>
            <hr>
            {{-- кнопка импортировать --}}
            @include('backend.substation.importForm')

        </div>
    </div>


@endsection

@section("scripts")
    @parent
    <!-- substation libs -->
    <script>
        let pageData = {
            id: {{$substation->id}},
        }
    </script>
    <script>let substationData = '{!! $data !!}';</script>
    <script src="/public/js/substation.edit.js" type="text/javascript"></script>
    @include('backend.acline.map.css')

@endsection
