<template>
    <section>

        <!-- содержимое -->
        <div class="custom-page">

            <!-- центральная часть -->
            <div class="page-content main-content">

                <!-- сюда буду выводить карту yandex -->
                <div v-show="viewWindowMap"
                     id="YMapsID">
                </div>

                <transition name="fade">
                    <div v-show="!viewWindowMap">
                        <div v-show="viewWindowOpenAcline">
                            <open-acline-component></open-acline-component>
                        </div>
                    </div>
                </transition>

                <!-- все обьекты карты -->
                <transition name="fade">
                    <div v-show="!viewWindowMap">
                        <div v-show="viewWindowOpenMapObjects">
                            <open-map-objects-component
                                :mapObjects="mapAttributes.objects"
                            ></open-map-objects-component>
                        </div>
                    </div>
                </transition>

                <!-- текущие обьекты (выделенные красным) -->
                <transition name="fade">
                    <div v-show="!viewWindowMap">
                        <div v-show="viewWindowOpenCurrentObjects">
                            <open-current-objects-component
                                :mapObjects="mapAttributes.objects"
                            ></open-current-objects-component>
                        </div>
                    </div>
                </transition>

                <!-- сегменты -->
                <transition name="fade">
                    <div v-show="!viewWindowMap">
                        <div v-show="viewWindowOpenSegments">
                            <open-segments-component
                                :mapObjects="mapAttributes.segments"
                            ></open-segments-component>
                        </div>
                    </div>
                </transition>

            </div>

            <!-- боковая правая панель -->
            <div class="panel-body p-0">
                <div class="sidebar-panel">
                    <div class="form-group">

                        <!-- кнопки -->
                        <div class="border p-10">
                            <h3>Линия ЛЭП</h3>
                            <button type="button" class="button_mini"
                                    @click="funWindowOpen('aclines')">
                                Загрузить
                            </button>
                            <button type="button" class="button_mini"
                                    @click="funToEventBus({
                                    'regim' : 'mapSave',
                                    })">
                                Сохранить
                            </button>
                            <button type="button" class="button_mini"
                                    @click="funToEventBus({
                                    'regim' : 'mapClear',
                                    'needConfirm' : 'true',
                                    })">
                                Очистить карту
                            </button>
                            <button type="button" class="button_mini"
                                    @click="funWindowOpen('mapObjects')">
                                Все обьекты
                            </button>
                            <button type="button" class="button_mini"
                                    @click="funWindowOpen('currentObjects')">
                                Выделенные обьекты
                            </button>
                            <button type="button" class="button_mini"
                                    @click="funWindowOpen('segments')">
                                Сегменты
                            </button>
                        </div>

                        <div v-if="(typeof(mapAttributes.dynamic) !== 'undefined')">

                            <!-- добавить новый обьект -->
                            <div
                                v-if="currentObject === null"
                                class="border p-10">
                                <h3>Добавить новый обьект</h3>

                                <button type="button" class="button_mini"
                                        @click="funToEventBus({
                                            'regim' : 'objectAdd',
                                            'type' : 'substation',
                                            'parents' :
                                                {
                                                'placemark' : null,
                                                'polyline' : null,
                                                'segment' : null,
                                                },
                                            })">
                                    ТП
                                </button>
                                <button type="button" class="button_mini"
                                        @click="funToEventBus({
                                            'regim' : 'objectAdd',
                                            'type' : 'tower',
                                            'parents' :
                                                {
                                                'placemark' : null,
                                                'polyline' : null,
                                                'segment' : null,
                                                },
                                            })">
                                    Опора
                                </button>
                                <button type="button" class="button_mini"
                                        @click="funToEventBus({
                                            'regim' : 'objectAdd',
                                            'type' : 'customer',
                                            'parents' :
                                                {
                                                'placemark' : null,
                                                'polyline' : null,
                                                'segment' : null,
                                                },
                                            })">
                                    Потребитель
                                </button>
                                <button type="button" class="button_mini"
                                        @click="funToEventBus({
                                            'regim' : 'objectAdd',
                                            'type' : 'text',
                                            'parents' :
                                                {
                                                'placemark' : null,
                                                'polyline' : null,
                                                'segment' : null,
                                                },
                                            })">
                                    Текст
                                </button>

                                <br><br>
                                Соединить линией:
                                <select
                                    v-model="mapAttributes.dynamic.lineType"
                                    class="form-control mt-10">
                                    <option value='701'>воздушной</option>
                                    <option value='702'>кабельной</option>
                                </select>

                                <br>
                                Широта: {{ mapAttributes.dynamic.currentLat }}
                                <br>
                                Долгота: {{ mapAttributes.dynamic.currentLong }}
                                <br>
                                X/Y: {{ mapAttributes.dynamic.currentSvgX }} px / {{
                                mapAttributes.dynamic.currentSvgY
                                }} px
                            </div>

                            <!-- данные текущего обьекта -->
                            <div v-if="(currentObject !== null)"
                                 class="border p-10">

                                <!-- данные текущей ТП -->
                                <div v-if="(currentObject.type === 'substation')">
                                    <h3>ТП</h3>

                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            Наименование:
                                        </div>
                                        <div class="col-5 mt-1">
                                            <input
                                                v-model="currentObject.name"
                                                type="text"
                                                class="form-control">
                                        </div>
                                        <div class="col-3 mt-1 pl-1">
                                            <!-- положение - vue-компонент-->
                                            <get-position-component
                                                :get-position="currentObject.positionText"
                                                v-on:positionChange="funPositionChange($event)">
                                            </get-position-component>
                                        </div>
                                    </div>
                                </div>

                                <!-- данные текущей опоры -->
                                <div v-if="(currentObject.type === 'tower')">
                                    <h3>Опора</h3>

                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            Диспетчерский номер:
                                        </div>
                                        <div class="col-5 mt-1" style="padding-right: 0px !important;">
                                            <input
                                                v-model="currentObject.localName"
                                                type="text"
                                                class="form-control">
                                        </div>
                                        <div class="col-3 mt-1 pl-1">
                                            <!-- положение - vue-компонент-->
                                            <get-position-component
                                                :get-position="currentObject.positionText"
                                                v-on:positionChange="funPositionChange($event)">
                                            </get-position-component>
                                        </div>
                                    </div>

                                    <!-- основное -->
                                    <div>
                                        <div class="row d-flex align-items-center">
                                            <div class="col-4 text-right">
                                                Марка:
                                            </div>
                                            <div class="col-8 mt-1">
                                                <select
                                                    v-model="currentObject.towerInfo"
                                                    class="form-control">
                                                    <option
                                                        v-for="item in allSpravs.towerinfos"
                                                        :value="item.id">
                                                        {{ item.name }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row d-flex align-items-center">
                                            <div class="col-4 text-right">
                                                Материал:
                                            </div>
                                            <div class="col-8 mt-1">
                                                <select
                                                    v-model="currentObject.towerMaterial"
                                                    class="form-control">
                                                    <option
                                                        v-for="item in allSpravs.towermaterials"
                                                        :value="item.id">
                                                        {{ item.name }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <div v-if="Number(currentObject.towerMaterial) === 1"
                                             class="row d-flex align-items-center">
                                            <div class="col-4 text-right">
                                                Приставка:
                                            </div>
                                            <div class="col-8 mt-1">
                                                <select
                                                    v-model="currentObject.annex"
                                                    class="form-control">
                                                    <option value="no" selected>нет</option>
                                                    <option value="metal">металл</option>
                                                    <option value="concrete">железобетон</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row d-flex align-items-center">
                                            <div class="col-4 text-right">
                                                Назначение:
                                            </div>
                                            <div class="col-8 mt-1">
                                                <select
                                                    v-model="currentObject.towerKind"
                                                    class="form-control">
                                                    <option
                                                        v-for="item in allSpravs.towerkinds"
                                                        :value="item.id">
                                                        {{ item.name }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row d-flex align-items-center">
                                            <div class="col-4 text-right">
                                                Конструкция:
                                            </div>
                                            <div class="col-8 mt-1">
                                                <select
                                                    v-model="currentObject.towerConstruction"
                                                    class="form-control">
                                                    <option
                                                        v-for="item in allSpravs.towerconstructionkinds"
                                                        :value="item.id">
                                                        {{ item.name }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row d-flex align-items-center">
                                            <div class="col-4 text-right">
                                                Кол-во стоек:
                                            </div>
                                            <div class="col-8 mt-1">
                                                <select
                                                    v-model="currentObject.propN"
                                                    class="form-control">
                                                    <option value="no" selected>не указано</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row d-flex align-items-center">
                                            <div class="col-4 text-right">
                                                Оттяжка:
                                            </div>
                                            <div class="col-8 mt-1">
                                                <select
                                                    v-model="currentObject.guy"
                                                    class="form-control">
                                                    <option value="no" selected>нет</option>
                                                    <option value="left">слева</option>
                                                    <option value="right">справа</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row d-flex align-items-center">
                                            <div class="col-4 text-right">
                                                Подкос:
                                            </div>
                                            <div class="col-3 mt-1">
                                                <select
                                                    v-model="currentObject.strutN"
                                                    class="form-control">
                                                    <option value="no" selected>не указано</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                </select>
                                            </div>
                                            <div class="col-5 mt-1">
                                                <select
                                                    v-if="Number(currentObject.strutN) > 0"
                                                    v-model="currentObject.strut"
                                                    class="form-control">
                                                    <option value="no" selected>нет</option>
                                                    <option value="concrete">железобетон</option>
                                                    <option value="wood">дерево</option>
                                                    <option value="metal">металл</option>
                                                </select>
                                            </div>
                                        </div>

                                    </div>

                                    <!-- оборудование прочее -->
                                    <div>
                                        Оборудование прочее:

                                        <div class="row d-flex align-items-center">
                                            <div class="col-4 text-right">
                                                - фонарь:
                                            </div>
                                            <div class="col-8 mt-1">
                                                <button
                                                    v-if="(typeof(currentObject.eqLamp) !== 'undefined' && currentObject.eqLamp > 0)"
                                                    @click="funFromOpenMapObjects(currentObject.eqLamp)"
                                                    type="button" class="button_mini bg_have">
                                                    установлен...
                                                </button>
                                                <button type="button" class="button_mini"
                                                        v-else
                                                        @click="funToEventBus({
                                                            'regim' : 'objectAdd',
                                                            'type' : 'lamp',
                                                            'parents' :
                                                                {
                                                                'placemark' : currentObject.mapID,
                                                                'polyline' : null,
                                                                'segment' : null,
                                                                },
                                                            })">
                                                    добавить
                                                </button>
                                            </div>
                                        </div>

                                        <div class="row d-flex align-items-center">
                                            <div class="col-4 text-right">
                                                - адаптер:
                                            </div>
                                            <div class="col-8 mt-1">
                                                <button
                                                    v-if="(typeof(currentObject.eqAdapter) !== 'undefined' && currentObject.eqAdapter > 0)"
                                                    @click="funFromOpenMapObjects(currentObject.eqAdapter)"
                                                    type="button" class="button_mini bg_have">
                                                    установлен...
                                                </button>
                                                <button type="button" class="button_mini"
                                                        v-else
                                                        @click="funToEventBus({
                                                            'regim' : 'objectAdd',
                                                            'type' : 'adapter',
                                                            'parents' :
                                                                {
                                                                'placemark' : currentObject.mapID,
                                                                'polyline' : null,
                                                                'segment' : null,
                                                                },
                                                            })">
                                                    добавить
                                                </button>
                                            </div>
                                        </div>

                                        <div class="row d-flex align-items-center">
                                            <div class="col-4 text-right">
                                                - линии связи:
                                            </div>
                                            <div class="col-8 mt-1">
                                                <button
                                                    v-if="(typeof(currentObject.eqCommLine) !== 'undefined' && currentObject.eqCommLine > 0)"
                                                    @click="funFromOpenMapObjects(currentObject.eqCommLine)"
                                                    type="button" class="button_mini bg_have">
                                                    установлена...
                                                </button>
                                                <button type="button" class="button_mini"
                                                        v-else
                                                        @click="funToEventBus({
                                                            'regim' : 'objectAdd',
                                                            'type' : 'commline',
                                                            'parents' :
                                                                {
                                                                'placemark' : currentObject.mapID,
                                                                'polyline' : null,
                                                                'segment' : null,
                                                                },
                                                            })">
                                                    добавить
                                                </button>
                                            </div>
                                        </div>

                                        <div class="row d-flex align-items-center">
                                            <div class="col-4 text-right">
                                                - линии разных классов напряжения:
                                            </div>
                                            <div class="col-8 mt-1">
                                                <input
                                                    v-model="currentObject.eqOtherLine"
                                                    true-value="1"
                                                    false-value="0"
                                                    type="checkbox">
                                            </div>
                                        </div>

                                        <div class="row d-flex align-items-center">
                                            <div class="col-4 text-right">
                                                - аварийная:
                                            </div>
                                            <div class="col-8 mt-1">
                                                <input
                                                    v-model="currentObject.eqAccident"
                                                    true-value="1"
                                                    false-value="0"
                                                    type="checkbox">
                                            </div>
                                        </div>

                                        <div class="row d-flex align-items-center">
                                            <div class="col-4 text-right">
                                                - подьем запрещен:
                                            </div>
                                            <div class="col-8 mt-1">
                                                <input
                                                    v-model="currentObject.eqNoUp"
                                                    true-value="1"
                                                    false-value="0"
                                                    type="checkbox">
                                            </div>
                                        </div>

                                    </div>

                                    <!-- оборудование грозозащиты -->
                                    <div>
                                        Оборудование грозозащиты:

                                        <div class="row d-flex align-items-center">
                                            <div class="col-4 text-right">
                                                - заземление:
                                            </div>
                                            <div class="col-8 mt-1">
                                                <button
                                                    v-if="(typeof(currentObject.eqGrounding) !== 'undefined' && currentObject.eqGrounding > 0)"
                                                    @click="funFromOpenMapObjects(currentObject.eqGrounding)"
                                                    type="button" class="button_mini bg_have">
                                                    установлено...
                                                </button>
                                                <button type="button" class="button_mini"
                                                        v-else
                                                        @click="funToEventBus({
                                                            'regim' : 'objectAdd',
                                                            'type' : 'grounding',
                                                            'parents' :
                                                                {
                                                                'placemark' : currentObject.mapID,
                                                                'polyline' : null,
                                                                'segment' : null,
                                                                },
                                                            })">
                                                    добавить
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <!-- данные текущего Потребителя -->
                                <div v-if="(currentObject.type === 'customer')">
                                    <h3>Потребитель</h3>

                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            Адрес:
                                        </div>
                                        <div class="col-5 mt-1">
                                            <input
                                                v-model="currentObject.address"
                                                type="text"
                                                class="form-control">
                                        </div>
                                        <div class="col-3 mt-1 pl-1">
                                            <!-- положение - vue-компонент-->
                                            <get-position-component
                                                :get-position="currentObject.positionText"
                                                v-on:positionChange="funPositionChange($event)">
                                            </get-position-component>
                                        </div>
                                    </div>
                                </div>

                                <!-- данные текущего разрядника -->
                                <div v-if="(currentObject.type === 'discharger')">
                                    <h3>Разрядник</h3>

                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            Марка:
                                        </div>
                                        <div class="col-8 mt-1">
                                            <select
                                                v-model="currentObject.eqDischargerInfo"
                                                class="form-control">
                                                <option
                                                    v-for="item in allSpravs.dischargerinfos"
                                                    :value="item.id">
                                                    {{ item.ASSETINFOKEY }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            Установлен на:
                                        </div>
                                        <div class="col-8 mt-1">
                                            <button
                                                v-if="(typeof(currentObject.parentPlacemarkMapID) !== 'undefined' && currentObject.parentPlacemarkMapID >= 0)"
                                                type="button" class="button_mini"
                                                @click="funFromOpenMapObjects(currentObject.parentPlacemarkMapID)">
                                                подробнее...
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            Установлен в линии:
                                        </div>
                                        <div class="col-8 mt-1">
                                            <button
                                                v-if="(typeof(currentObject.parentPolylineMapID) !== 'undefined' && currentObject.parentPolylineMapID >= 0)"
                                                type="button" class="button_mini"
                                                @click="funFromOpenMapObjects(currentObject.parentPolylineMapID)">
                                                подробнее...
                                            </button>
                                        </div>
                                    </div>

                                </div>

                                <!-- данные текущего ОПН -->
                                <div v-if="(currentObject.type === 'opn')">
                                    <h3>ОПН</h3>

                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            Марка:
                                        </div>
                                        <div class="col-8 mt-1">
                                            <select
                                                v-model="currentObject.eqOPNInfo"
                                                class="form-control">
                                                <option
                                                    v-for="item in allSpravs.dischargerinfos"
                                                    :value="item.id">
                                                    {{ item.ASSETINFOKEY }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            Установлен на:
                                        </div>
                                        <div class="col-8 mt-1">
                                            <button
                                                v-if="(typeof(currentObject.parentPlacemarkMapID) !== 'undefined' && currentObject.parentPlacemarkMapID >= 0)"
                                                type="button" class="button_mini"
                                                @click="funFromOpenMapObjects(currentObject.parentPlacemarkMapID)">
                                                подробнее...
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            Установлен в линии:
                                        </div>
                                        <div class="col-8 mt-1">
                                            <button
                                                v-if="(typeof(currentObject.parentPolylineMapID) !== 'undefined' && currentObject.parentPolylineMapID >= 0)"
                                                type="button" class="button_mini"
                                                @click="funFromOpenMapObjects(currentObject.parentPolylineMapID)">
                                                подробнее...
                                            </button>
                                        </div>
                                    </div>

                                </div>

                                <!-- данные текущего заземления -->
                                <div v-if="(currentObject.type === 'grounding')">
                                    <h3>Заземление</h3>

                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            Марка:
                                        </div>
                                        <div class="col-5 mt-1">

                                        </div>
                                        <div class="col-3 mt-1 pl-1">
                                            <!-- положение - vue-компонент-->
                                            <get-position-component
                                                :get-position="currentObject.position"
                                                v-on:positionChange="funPositionChange($event)">
                                            </get-position-component>
                                        </div>
                                    </div>

                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            Установлено на:
                                        </div>
                                        <div class="col-8 mt-1">
                                            <button
                                                v-if="(typeof(currentObject.parentPlacemarkMapID) !== 'undefined' && currentObject.parentPlacemarkMapID >= 0)"
                                                type="button" class="button_mini"
                                                @click="funFromOpenMapObjects(currentObject.parentPlacemarkMapID)">
                                                подробнее...
                                            </button>
                                        </div>
                                    </div>

                                </div>

                                <!-- данные текущего фонаря -->
                                <div v-if="(currentObject.type === 'lamp')">
                                    <h3>Фонарь</h3>

                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            Марка:
                                        </div>
                                        <div class="col-5 mt-1">

                                        </div>
                                        <div class="col-3 mt-1 pl-1">
                                            <!-- положение - vue-компонент-->
                                            <get-position-component
                                                :get-position="currentObject.position"
                                                v-on:positionChange="funPositionChange($event)">
                                            </get-position-component>
                                        </div>
                                    </div>

                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            Установлен на:
                                        </div>
                                        <div class="col-8 mt-1">
                                            <button
                                                v-if="(typeof(currentObject.parentPlacemarkMapID) !== 'undefined' && currentObject.parentPlacemarkMapID >= 0)"
                                                type="button" class="button_mini"
                                                @click="funFromOpenMapObjects(currentObject.parentPlacemarkMapID)">
                                                подробнее...
                                            </button>
                                        </div>
                                    </div>

                                </div>

                                <!-- данные текущего адаптера -->
                                <div v-if="(currentObject.type === 'adapter')">
                                    <h3>Адаптер</h3>

                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            Марка:
                                        </div>
                                        <div class="col-5 mt-1">

                                        </div>
                                        <div class="col-3 mt-1 pl-1">
                                            <!-- положение - vue-компонент-->
                                            <get-position-component
                                                :get-position="currentObject.position"
                                                v-on:positionChange="funPositionChange($event)">
                                            </get-position-component>
                                        </div>
                                    </div>

                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            Установлен на:
                                        </div>
                                        <div class="col-8 mt-1">
                                            <button
                                                v-if="(typeof(currentObject.parentPlacemarkMapID) !== 'undefined' && currentObject.parentPlacemarkMapID >= 0)"
                                                type="button" class="button_mini"
                                                @click="funFromOpenMapObjects(currentObject.parentPlacemarkMapID)">
                                                подробнее...
                                            </button>
                                        </div>
                                    </div>

                                </div>

                                <!-- данные текущей линии связи -->
                                <div v-if="(currentObject.type === 'commline')">
                                    <h3>Линия связи</h3>

                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            Марка:
                                        </div>
                                        <div class="col-5 mt-1">

                                        </div>
                                        <div class="col-3 mt-1 pl-1">
                                            <!-- положение - vue-компонент-->
                                            <get-position-component
                                                :get-position="currentObject.position"
                                                v-on:positionChange="funPositionChange($event)">
                                            </get-position-component>
                                        </div>
                                    </div>

                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            Установлена на:
                                        </div>
                                        <div class="col-8 mt-1">
                                            <button
                                                v-if="(typeof(currentObject.parentPlacemarkMapID) !== 'undefined' && currentObject.parentPlacemarkMapID >= 0)"
                                                type="button" class="button_mini"
                                                @click="funFromOpenMapObjects(currentObject.parentPlacemarkMapID)">
                                                подробнее...
                                            </button>
                                        </div>
                                    </div>

                                </div>

                                <!-- данные текущего разьединителя -->
                                <div v-if="(currentObject.type === 'disconnector')">
                                    <h3>Разьединитель</h3>

                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            Марка:
                                        </div>
                                        <div class="col-8 mt-1">
                                            <select
                                                v-model="currentObject.eqDisconnectorInfo"
                                                class="form-control">
                                                <option
                                                    v-for="item in allSpravs.disconnectorinfos"
                                                    :value="item.id">
                                                    {{ item.ASSETINFOKEY }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            Установлен на:
                                        </div>
                                        <div class="col-8 mt-1">
                                            <button
                                                v-if="(typeof(currentObject.parentPlacemarkMapID) !== 'undefined' && currentObject.parentPlacemarkMapID >= 0)"
                                                type="button" class="button_mini"
                                                @click="funFromOpenMapObjects(currentObject.parentPlacemarkMapID)">
                                                подробнее...
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            Установлен в линии:
                                        </div>
                                        <div class="col-8 mt-1">
                                            <button
                                                v-if="(typeof(currentObject.parentPolylineMapID) !== 'undefined' && currentObject.parentPolylineMapID >= 0)"
                                                type="button" class="button_mini"
                                                @click="funFromOpenMapObjects(currentObject.parentPolylineMapID)">
                                                подробнее...
                                            </button>
                                        </div>
                                    </div>

                                </div>

                                <!-- данные текущего реклоузера -->
                                <div v-if="(currentObject.type === 'reklouzer')">
                                    <h3>Реклоузер</h3>

                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            Марка:
                                        </div>
                                        <div class="col-8 mt-1">
                                            <select
                                                v-model="currentObject.eqReklouzerInfo"
                                                class="form-control">
                                                <option
                                                    v-for="item in allSpravs.disconnectorinfos"
                                                    :value="item.id">
                                                    {{ item.ASSETINFOKEY }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            Установлен на:
                                        </div>
                                        <div class="col-8 mt-1">
                                            <button
                                                v-if="(typeof(currentObject.parentPlacemarkMapID) !== 'undefined' && currentObject.parentPlacemarkMapID >= 0)"
                                                type="button" class="button_mini"
                                                @click="funFromOpenMapObjects(currentObject.parentPlacemarkMapID)">
                                                подробнее...
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            Установлен в линии:
                                        </div>
                                        <div class="col-8 mt-1">
                                            <button
                                                v-if="(typeof(currentObject.parentPolylineMapID) !== 'undefined' && currentObject.parentPolylineMapID >= 0)"
                                                type="button" class="button_mini"
                                                @click="funFromOpenMapObjects(currentObject.parentPolylineMapID)">
                                                подробнее...
                                            </button>
                                        </div>
                                    </div>

                                </div>

                                <!-- данные текущего выключателя нагрузки -->
                                <div v-if="(currentObject.type === 'vna')">
                                    <h3>Выключатель нагрузки</h3>

                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            Марка:
                                        </div>
                                        <div class="col-8 mt-1">
                                            <select
                                                v-model="currentObject.eqVNaInfo"
                                                class="form-control">
                                                <option
                                                    v-for="item in allSpravs.disconnectorinfos"
                                                    :value="item.id">
                                                    {{ item.ASSETINFOKEY }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            Установлен на:
                                        </div>
                                        <div class="col-8 mt-1">
                                            <button
                                                v-if="(typeof(currentObject.parentPlacemarkMapID) !== 'undefined' && currentObject.parentPlacemarkMapID >= 0)"
                                                type="button" class="button_mini"
                                                @click="funFromOpenMapObjects(currentObject.parentPlacemarkMapID)">
                                                подробнее...
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            Установлен в линии:
                                        </div>
                                        <div class="col-8 mt-1">
                                            <button
                                                v-if="(typeof(currentObject.parentPolylineMapID) !== 'undefined' && currentObject.parentPolylineMapID >= 0)"
                                                type="button" class="button_mini"
                                                @click="funFromOpenMapObjects(currentObject.parentPolylineMapID)">
                                                подробнее...
                                            </button>
                                        </div>
                                    </div>

                                </div>

                                <!-- данные текущей линии 701 -->
                                <div v-if="(currentObject.type === '701')">
                                    <h3>Воздушная линия</h3>

                                    <!-- габарит -->
                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            Габарит:
                                        </div>
                                        <div class="col-8 mt-1">
                                            <input
                                                v-model="currentObject.gabarit"
                                                type="text"
                                                class="form-control">
                                        </div>
                                    </div>

                                    <!-- число проводов -->
                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            Число проводов:
                                        </div>
                                        <div class="col-8 mt-1">
                                            <input
                                                v-model="currentObject.wireN"
                                                type="text"
                                                class="form-control">
                                        </div>
                                    </div>

                                    <!-- длина провода -->
                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            Длина провода:
                                        </div>
                                        <div class="col-8 mt-1">
                                            <input
                                                v-model="currentObject.wireLength"
                                                type="text"
                                                class="form-control">
                                        </div>
                                    </div>

                                    <!-- оборудование коммутационное -->
                                    <div>
                                        Оборудование грозозащиты:

                                        <div class="row d-flex align-items-center">
                                            <div class="col-4 text-right">
                                                - разьединитель:
                                            </div>
                                            <div class="col-8 mt-1">
                                                <div class="row d-flex align-items-center">
                                                    <div class="col-6">
                                                        <button
                                                            v-if="(typeof(currentObject.eqDisconnectorStart) !== 'undefined' && currentObject.eqDisconnectorStart > 0)"
                                                            @click="funFromOpenMapObjects(currentObject.eqDisconnectorStart)"
                                                            type="button" class="button_mini bg_have">
                                                            установлен...
                                                        </button>
                                                        <button type="button" class="button_mini"
                                                                v-else
                                                                @click="funToEventBus({
                                                                    'regim' : 'objectAdd',
                                                                    'type' : 'disconnector',
                                                                    'parents' :
                                                                        {
                                                                        'placemark' : currentObject.startMapID,
                                                                        'polyline' : currentObject.mapID,
                                                                        'segment' : null,
                                                                        },
                                                                    })">
                                                            добавить
                                                        </button>
                                                    </div>
                                                    <div class="col-6">
                                                        <span :title="currentObject.startName">
                                                            ({{ currentObject.startName.slice(0, 8) }})
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="row d-flex align-items-center">
                                                    <div class="col-6">
                                                        <button
                                                            v-if="(typeof(currentObject.eqDisconnectorEnd) !== 'undefined' && currentObject.eqDisconnectorEnd > 0)"
                                                            @click="funFromOpenMapObjects(currentObject.eqDisconnectorEnd)"
                                                            type="button" class="button_mini bg_have">
                                                            установлен...
                                                        </button>
                                                        <button type="button" class="button_mini"
                                                                v-else
                                                                @click="funToEventBus({
                                                                    'regim' : 'objectAdd',
                                                                    'type' : 'disconnector',
                                                                    'parents' :
                                                                        {
                                                                        'placemark' : currentObject.endMapID,
                                                                        'polyline' : currentObject.mapID,
                                                                        'segment' : null,
                                                                        },
                                                                    })">
                                                            добавить
                                                        </button>
                                                    </div>
                                                    <div class="col-6">
                                                        <span :title="currentObject.endName">
                                                            ({{ currentObject.endName.slice(0, 8) }})
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row d-flex align-items-center">
                                            <div class="col-4 text-right">
                                                - реклоузер:
                                            </div>
                                            <div class="col-8 mt-1">
                                                <div class="row d-flex align-items-center">
                                                    <div class="col-6">
                                                        <button
                                                            v-if="(typeof(currentObject.eqReklouzerStart) !== 'undefined' && currentObject.eqReklouzerStart > 0)"
                                                            @click="funFromOpenMapObjects(currentObject.eqReklouzerStart)"
                                                            type="button" class="button_mini bg_have">
                                                            установлен...
                                                        </button>
                                                        <button type="button" class="button_mini"
                                                                v-else
                                                                @click="funToEventBus({
                                                                    'regim' : 'objectAdd',
                                                                    'type' : 'reklouzer',
                                                                    'parents' :
                                                                        {
                                                                        'placemark' : currentObject.startMapID,
                                                                        'polyline' : currentObject.mapID,
                                                                        'segment' : null,
                                                                        },
                                                                    })">
                                                            добавить
                                                        </button>
                                                    </div>
                                                    <div class="col-6">
                                                        <span :title="currentObject.startName">
                                                            ({{ currentObject.startName.slice(0, 8) }})
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="row d-flex align-items-center">
                                                    <div class="col-6">
                                                        <button
                                                            v-if="(typeof(currentObject.eqReklouzerEnd) !== 'undefined' && currentObject.eqReklouzerEnd > 0)"
                                                            @click="funFromOpenMapObjects(currentObject.eqReklouzerEnd)"
                                                            type="button" class="button_mini bg_have">
                                                            установлен...
                                                        </button>
                                                        <button type="button" class="button_mini"
                                                                v-else
                                                                @click="funToEventBus({
                                                                    'regim' : 'objectAdd',
                                                                    'type' : 'reklouzer',
                                                                    'parents' :
                                                                        {
                                                                        'placemark' : currentObject.endMapID,
                                                                        'polyline' : currentObject.mapID,
                                                                        'segment' : null,
                                                                        },
                                                                    })">
                                                            добавить
                                                        </button>
                                                    </div>
                                                    <div class="col-6">
                                                        <span :title="currentObject.endName">
                                                            ({{ currentObject.endName.slice(0, 8) }})
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row d-flex align-items-center">
                                            <div class="col-4 text-right">
                                                - выключатель нагрузки:
                                            </div>
                                            <div class="col-8 mt-1">
                                                <div class="row d-flex align-items-center">
                                                    <div class="col-6">
                                                        <button
                                                            v-if="(typeof(currentObject.eqVNaStart) !== 'undefined' && currentObject.eqVNaStart > 0)"
                                                            @click="funFromOpenMapObjects(currentObject.eqVNaStart)"
                                                            type="button" class="button_mini bg_have">
                                                            установлен...
                                                        </button>
                                                        <button type="button" class="button_mini"
                                                                v-else
                                                                @click="funToEventBus({
                                                                    'regim' : 'objectAdd',
                                                                    'type' : 'vna',
                                                                    'parents' :
                                                                        {
                                                                        'placemark' : currentObject.startMapID,
                                                                        'polyline' : currentObject.mapID,
                                                                        'segment' : null,
                                                                        },
                                                                    })">
                                                            добавить
                                                        </button>
                                                    </div>
                                                    <div class="col-6">
                                                        <span :title="currentObject.startName">
                                                            ({{ currentObject.startName.slice(0, 8) }})
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="row d-flex align-items-center">
                                                    <div class="col-6">
                                                        <button
                                                            v-if="(typeof(currentObject.eqVNaEnd) !== 'undefined' && currentObject.eqVNaEnd > 0)"
                                                            @click="funFromOpenMapObjects(currentObject.eqVNaEnd)"
                                                            type="button" class="button_mini bg_have">
                                                            установлен...
                                                        </button>
                                                        <button type="button" class="button_mini"
                                                                v-else
                                                                @click="funToEventBus({
                                                                    'regim' : 'objectAdd',
                                                                    'type' : 'vna',
                                                                    'parents' :
                                                                        {
                                                                        'placemark' : currentObject.endMapID,
                                                                        'polyline' : currentObject.mapID,
                                                                        'segment' : null,
                                                                        },
                                                                    })">
                                                            добавить
                                                        </button>
                                                    </div>
                                                    <div class="col-6">
                                                        <span :title="currentObject.endName">
                                                            ({{ currentObject.endName.slice(0, 8) }})
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <!-- оборудование грозозащиты -->
                                    <div>
                                        Оборудование грозозащиты:

                                        <div class="row d-flex align-items-center">
                                            <div class="col-4 text-right">
                                                - разрядник:
                                            </div>
                                            <div class="col-8 mt-1">
                                                <div class="row d-flex align-items-center">
                                                    <div class="col-6">
                                                        <button
                                                            v-if="(typeof(currentObject.eqDischargerStart) !== 'undefined' && currentObject.eqDischargerStart > 0)"
                                                            @click="funFromOpenMapObjects(currentObject.eqDischargerStart)"
                                                            type="button" class="button_mini bg_have">
                                                            установлен...
                                                        </button>
                                                        <button type="button" class="button_mini"
                                                                v-else
                                                                @click="funToEventBus({
                                                                    'regim' : 'objectAdd',
                                                                    'type' : 'discharger',
                                                                    'parents' :
                                                                        {
                                                                        'placemark' : currentObject.startMapID,
                                                                        'polyline' : currentObject.mapID,
                                                                        'segment' : null,
                                                                        },
                                                                    })">
                                                            добавить
                                                        </button>
                                                    </div>
                                                    <div class="col-6">
                                                        <span :title="currentObject.startName">
                                                            ({{ currentObject.startName.slice(0, 8) }})
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="row d-flex align-items-center">
                                                    <div class="col-6">
                                                        <button
                                                            v-if="(typeof(currentObject.eqDischargerEnd) !== 'undefined' && currentObject.eqDischargerEnd > 0)"
                                                            @click="funFromOpenMapObjects(currentObject.eqDischargerEnd)"
                                                            type="button" class="button_mini bg_have">
                                                            установлен...
                                                        </button>
                                                        <button type="button" class="button_mini"
                                                                v-else
                                                                @click="funToEventBus({
                                                                    'regim' : 'objectAdd',
                                                                    'type' : 'discharger',
                                                                    'parents' :
                                                                        {
                                                                        'placemark' : currentObject.endMapID,
                                                                        'polyline' : currentObject.mapID,
                                                                        'segment' : null,
                                                                        },
                                                                    })">
                                                            добавить
                                                        </button>
                                                    </div>
                                                    <div class="col-6">
                                                        <span :title="currentObject.endName">
                                                            ({{ currentObject.endName.slice(0, 8) }})
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row d-flex align-items-center">
                                            <div class="col-4 text-right">
                                                - ОПН:
                                            </div>
                                            <div class="col-8 mt-1">
                                                <div class="row d-flex align-items-center">
                                                    <div class="col-6">
                                                        <button
                                                            v-if="(typeof(currentObject.eqOPNStart) !== 'undefined' && currentObject.eqOPNStart > 0)"
                                                            @click="funFromOpenMapObjects(currentObject.eqOPNStart)"
                                                            type="button" class="button_mini bg_have">
                                                            установлен...
                                                        </button>
                                                        <button type="button" class="button_mini"
                                                                v-else
                                                                @click="funToEventBus({
                                                                    'regim' : 'objectAdd',
                                                                    'type' : 'opn',
                                                                    'parents' :
                                                                        {
                                                                        'placemark' : currentObject.startMapID,
                                                                        'polyline' : currentObject.mapID,
                                                                        'segment' : null,
                                                                        },
                                                                    })">
                                                            добавить
                                                        </button>
                                                    </div>
                                                    <div class="col-6">
                                                        <span :title="currentObject.startName">
                                                            ({{ currentObject.startName.slice(0, 8) }})
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="row d-flex align-items-center">
                                                    <div class="col-6">
                                                        <button
                                                            v-if="(typeof(currentObject.eqOPNEnd) !== 'undefined' && currentObject.eqOPNEnd > 0)"
                                                            @click="funFromOpenMapObjects(currentObject.eqOPNEnd)"
                                                            type="button" class="button_mini bg_have">
                                                            установлен...
                                                        </button>
                                                        <button type="button" class="button_mini"
                                                                v-else
                                                                @click="funToEventBus({
                                                                    'regim' : 'objectAdd',
                                                                    'type' : 'opn',
                                                                    'parents' :
                                                                        {
                                                                        'placemark' : currentObject.endMapID,
                                                                        'polyline' : currentObject.mapID,
                                                                        'segment' : null,
                                                                        },
                                                                    })">
                                                            добавить
                                                        </button>
                                                    </div>
                                                    <div class="col-6">
                                                        <span :title="currentObject.endName">
                                                            ({{ currentObject.endName.slice(0, 8) }})
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                                <!-- данные текущей линии 702 -->
                                <div v-if="(currentObject.type === '702')">
                                    <h3>Кабельная линия</h3>

                                    <!-- проводов в фазе -->
                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            Проводов в фазе:
                                        </div>
                                        <div class="col-8 mt-1">
                                            <input
                                                v-model="currentObject.wirePhaseN"
                                                type="text"
                                                class="form-control">
                                        </div>
                                    </div>

                                    <!-- условие прокладки -->
                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            Условие прокладки:
                                        </div>
                                        <div class="col-8 mt-1">
                                            <select
                                                v-model="currentObject.layingCondition"
                                                class="form-control">
                                                <option
                                                    v-for="item in allSpravs.layingconditionkinds"
                                                    :value="item.id">
                                                    {{ item.name }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- кабелей в траншее -->
                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            Кабелей в траншее:
                                        </div>
                                        <div class="col-8 mt-1">
                                            <input
                                                v-model="currentObject.cabelsN"
                                                type="text"
                                                class="form-control">
                                        </div>
                                    </div>

                                    <!-- характерные точки -->
                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            Характерные точки:
                                        </div>
                                        <div class="col-8 mt-1">
                                            <textarea
                                                v-model="currentObject.coord"
                                                class="form-control">
                                                </textarea>
                                        </div>
                                    </div>

                                </div>

                                <!-- данные текущей текстовой надписи -->
                                <div v-if="(currentObject.type === 'text')">
                                    <h3>Текстовая надпись</h3>

                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            Текст:
                                        </div>
                                        <div class="col-8 mt-1">
                                            <input
                                                v-model="currentObject.text"
                                                type="text"
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <!-- общее, кроме линий -->
                                <div
                                    v-if="(currentObject.type !== '701' && currentObject.type !== '702')">

                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            Широта:
                                        </div>
                                        <div class="col-8 mt-1">
                                            <input
                                                v-model="currentObject.lat"
                                                type="text"
                                                class="form-control">
                                        </div>
                                    </div>

                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            Долгота:
                                        </div>
                                        <div class="col-8 mt-1">
                                            <input
                                                v-model="currentObject.long"
                                                type="text"
                                                class="form-control">
                                        </div>
                                    </div>

                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            X:
                                        </div>
                                        <div class="col-8 mt-1">
                                            <input
                                                v-model="currentObject.x"
                                                type="text"
                                                class="form-control">
                                        </div>
                                    </div>

                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            Y:
                                        </div>
                                        <div class="col-8 mt-1">
                                            <input
                                                v-model="currentObject.y"
                                                type="text"
                                                class="form-control">
                                        </div>
                                    </div>

                                </div>

                                <!-- общее, только для линий -->
                                <div
                                    v-if="(currentObject.type === '701' || currentObject.type === '702')">

                                    <!-- сечение -->
                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            Сечение:
                                        </div>
                                        <div class="col-8 mt-1">
                                            <input
                                                v-model="currentObject.wireS"
                                                type="text"
                                                class="form-control">
                                        </div>
                                    </div>

                                    <!-- длина пролета/участка -->
                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            Длина пролета/участка:
                                        </div>
                                        <div class="col-8 mt-1">
                                            <input
                                                v-model="currentObject.spanLength"
                                                type="text"
                                                class="form-control">
                                        </div>
                                    </div>

                                    <!-- тип -->
                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            Тип:
                                        </div>
                                        <div class="col-8 mt-1">
                                            <select
                                                v-model="currentObject.towerInfo"
                                                class="form-control">
                                                <option value="701">воздушная</option>
                                                <option value="702">кабельная</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- марка провода/кабеля -->
                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            Марка провода/кабеля:
                                        </div>
                                        <div class="col-8 mt-1">
                                            <select
                                                v-model="currentObject.wireMark"
                                                class="form-control">
                                                <option
                                                    v-for="item in allSpravs.aclinesegmentinfos"
                                                    :value="item.id">
                                                    {{ item.assetinfokey }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- startMapID -->
                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            startMapID:
                                        </div>
                                        <div class="col-8 mt-1">
                                            <input
                                                v-model="currentObject.startMapID"
                                                type="text"
                                                class="form-control">
                                        </div>
                                    </div>

                                    <!-- endMapID -->
                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            endMapID:
                                        </div>
                                        <div class="col-8 mt-1">
                                            <input
                                                v-model="currentObject.endMapID"
                                                type="text"
                                                class="form-control">
                                        </div>
                                    </div>

                                    <!-- X1 -->
                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            X1:
                                        </div>
                                        <div class="col-8 mt-1">
                                            <input
                                                v-model="currentObject.x1"
                                                type="text"
                                                class="form-control">
                                        </div>
                                    </div>

                                    <!-- Y1 -->
                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            Y1:
                                        </div>
                                        <div class="col-8 mt-1">
                                            <input
                                                v-model="currentObject.y1"
                                                type="text"
                                                class="form-control">
                                        </div>
                                    </div>

                                    <!-- X2 -->
                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            X2:
                                        </div>
                                        <div class="col-8 mt-1">
                                            <input
                                                v-model="currentObject.x2"
                                                type="text"
                                                class="form-control">
                                        </div>
                                    </div>

                                    <!-- Y2 -->
                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            Y2:
                                        </div>
                                        <div class="col-8 mt-1">
                                            <input
                                                v-model="currentObject.y2"
                                                type="text"
                                                class="form-control">
                                        </div>
                                    </div>

                                </div>

                                <!-- общее для всех (mapID, dbID, dbIOID, parentPlacemarkMapID, угол) -->
                                <div>

                                    <!-- mapID -->
                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            mapID:
                                        </div>
                                        <div class="col-8 mt-1">
                                            {{ currentObject.mapID }}
                                        </div>
                                    </div>

                                    <!-- parentPlacemarkMapID -->
                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            parentPlacemarkMapID:
                                        </div>
                                        <div class="col-8 mt-1">
                                            {{ currentObject.parentPlacemarkMapID }}
                                        </div>
                                    </div>

                                    <!-- parentPolylineMapID -->
                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            parentPolylineMapID:
                                        </div>
                                        <div class="col-8 mt-1">
                                            {{ currentObject.parentPolylineMapID }}
                                        </div>
                                    </div>

                                    <!-- position -->
                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            position:
                                        </div>
                                        <div class="col-8 mt-1">
                                            {{ currentObject.position }}
                                        </div>
                                    </div>

                                    <!-- positionText -->
                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            positionText:
                                        </div>
                                        <div class="col-8 mt-1">
                                            {{ currentObject.positionText }}
                                        </div>
                                    </div>

                                    <!-- вычисляемый угол -->
                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            угол:
                                        </div>
                                        <div class="col-8 mt-1">
                                            {{ currentObject.angle }}
                                        </div>
                                    </div>

                                    <!-- dbID -->
                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            dbID:
                                        </div>
                                        <div class="col-8 mt-1">
                                            {{ currentObject.dbID }}
                                        </div>
                                    </div>

                                    <!-- dbIOID -->
                                    <div class="row d-flex align-items-center">
                                        <div class="col-4 text-right">
                                            dbIOID:
                                        </div>
                                        <div class="col-8 mt-1">
                                            {{ currentObject.dbIOID }}
                                        </div>
                                    </div>

                                </div>

                                <br><br>

                                <button type="button" class="button bordered"
                                        @click="funToEventBus({
                                        'regim' : 'objectDelete',
                                        })">
                                    Удалить
                                </button>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </section>
</template>

<script>
// подключение сервиса с общими функциями
import {
    serviceGetSvgFigure,
} from "./services/serviceGetSvgFigure";
import {serviceFilterObjectsOnCurrent} from "./services/serviceGetFilterMapObjects";

export default {
    components: {
        // если так загрузить, то появляются файлы 0.js, 1.js в папке public
        //'open-acline-component': () => import('./openAcline_component'),
    },
    props: {},
    data() {
        return {
            loading: false,
            errored: false,

            viewWindowMap: true, // показать окно с картой
            viewWindowOpenAcline: false, // показать окно с выбором линий
            viewWindowOpenMapObjects: false, // показать окно с выбором обьектов всех
            viewWindowOpenCurrentObjects: false, // показать окно с выбором обьектов текущих, выделенных красным цветом
            viewWindowOpenSegments: false, // показать окно с сегментами

            mapAttributes: {},
            allSpravs: {}, // все справочники, полученные с PHP
        }
    },
    mounted() {

        // включить прослушку клавиатуры
        window.addEventListener("keydown", this.funOnKeyDown);

        // все справчоники записать в vue-переменную
        this.allSpravs = mmAllSpravs;
    },
    created() {

        // подписаться на получение с шины
        this.$eventBus.$on('fromMap', this.funFromMap);
        this.$eventBus.$on('fromOpenAcline', this.funFromOpenAcline);
        this.$eventBus.$on('fromOpenMapObjects', this.funFromOpenMapObjects);
        this.$eventBus.$on('fromOpenSegments', this.funFromOpenSegments);
    },
    beforeDestroy() {
        // отписаться на получение с шины
        this.$eventBus.$off('fromMap');
        this.$eventBus.$off('fromOpenAcline');
        this.$eventBus.$off('fromOpenMapObjects');
        this.$eventBus.$off('fromOpenSegments');
    },
    watch: {
        currentObject: {
            handler: function (after, before) {
                //console.log("Смена значения");

                let myCurrentObjects = serviceFilterObjectsOnCurrent(this.mapAttributes.objects);
                if (myCurrentObjects !== null && myCurrentObjects.length > 1) {
                    // несколько текущих

                    // пройтись по всем полям и узнать, в каком поле были сделаны изменения
                    for (let key in after) {
                        // поля, которые можно менять
                        let myFields = [
                            'name', 'localName', 'address',
                            'position', 'positionText',
                            'textNameAlign', 'textNameX', 'textNameY',
                            'towerMaterial',
                        ];
                        if (myFields.includes(key)) {
                            // прогнать по всем выделенным обьектам и записать значение
                            for (let i = 0; i < myCurrentObjects.length; i++) {
                                myCurrentObjects[i][key] = after[key];
                            }
                        }
                    }
                }
            },
            deep: true,
        }
    },
    computed: {

        // текущий обьект
        currentObject: function () {
            let myObject = null;

            let myCurrentObjects = serviceFilterObjectsOnCurrent(this.mapAttributes.objects);
            if (myCurrentObjects !== null) {
                if (myCurrentObjects.length === 1) {
                    // один текущий
                    myObject = myCurrentObjects[0];
                } else {
                    if (myCurrentObjects.length > 1) {
                        // несколько текущих
                        // определить тип и создать пустой макет
                        //myObject = serviceGetSvgFigure(myCurrentObjects[0].type);
                        myObject = myCurrentObjects[0];

                        // прогнать по всем выделенным обьектам и сравнить с первым (т.е.нулевым)
                        for (let i = 1; i < myCurrentObjects.length; i++) {

                            // прогнать по всем полям, и если у выделенных разные значения в поле - оствить его пустым. Иначе вывести одинаковые
                            for (let key in myCurrentObjects[i]) {

                                // добавить поле, если его не было
                                if (typeof (myObject[key]) === 'undefined') {
                                    myObject[key] = myCurrentObjects[i][key];
                                }

                                // очистить поле, если оно отличается
                                if (myObject[key] !== myCurrentObjects[i][key]) {
                                    //myObject[key] = null;
                                }
                            }
                        }
                    }
                }
            }

            //console.log("Текущий:");
            //console.log(myObject);

            // возвращаемый параметр
            return myObject;
        },
    },
    methods: {

        // ------------------------------------------------------------------
        // событие нажатия клавиши на клавиатуре
        funOnKeyDown(event) {

            switch (event.key) {
                case "Escape":
                    this.funFromOpenMapObjects(null);
                    break;
            }

            //return event.preventDefault();
        },

        // ------------------------------------------------------------------
        // свернуть все окна
        funAllWindowsClose() {

            // выбор
            this.viewWindowOpenAcline = false;
            this.viewWindowOpenMapObjects = false;
            this.viewWindowOpenCurrentObjects = false;
            this.viewWindowOpenSegments = false;
            // карта
            this.viewWindowMap = false;
        },

        // ------------------------------------------------------------------
        // открыть окно
        funWindowOpen(getWindowsName) {
            // свернуть все окна
            this.funAllWindowsClose();

            // показать нужное
            switch (getWindowsName) {
                case "aclines":
                    this.viewWindowOpenAcline = true;
                    break;
                case "mapObjects":
                    this.viewWindowOpenMapObjects = true;
                    break;
                case "currentObjects":
                    this.viewWindowOpenCurrentObjects = true;
                    break;
                case "segments":
                    this.viewWindowOpenSegments = true;
                    break;
            }
        },

        // ------------------------------------------------------------------
        // открыть окно с текущими обьектами карты (выделенные красным)
        funOpenCurrentObjects() {
        },

        // ------------------------------------------------------------------
        // получение события с карты
        funFromMap(mapAttributes) {

            // обновить массив
            this.mapAttributes = mapAttributes;
        },

        // ------------------------------------------------------------------
        // получение события с окна выбора линии
        funFromOpenAcline(getAclineID) {

            // закрыть диалоговое окно
            this.viewWindowOpenAcline = false;
            this.viewWindowMap = true;

            if (getAclineID !== null) {
                // сообщить шине
                this.funToEventBus({
                    'regim': 'mapLoad',
                    'aclineID': getAclineID,
                });
            }
        },

        // ------------------------------------------------------------------
        // получение события с окна выбора списка обьектов
        funFromOpenMapObjects(getObjectMapID = null) {

            // свернуть все окна
            this.funAllWindowsClose();
            // показать карту
            this.viewWindowMap = true;

            if (typeof (getObjectMapID) !== 'undefined' && getObjectMapID !== null) {
                // сообщить шине
                this.funToEventBus({
                    'regim': 'objectSelect',
                    'mapID': getObjectMapID,
                });
            }
        },

        // ------------------------------------------------------------------
        // получение события с окна выбора сегмента
        funFromOpenSegments(getSegmentIndex = null) {

            // свернуть все окна
            this.funAllWindowsClose();
            // показать карту
            this.viewWindowMap = true;

            if (typeof (getSegmentIndex) !== 'undefined' && getSegmentIndex !== null) {
                // сообщить шине
                this.funToEventBus({
                    'regim': 'segmentSelect',
                    'segmentIndex': getSegmentIndex,
                });
            }
        },

        // ------------------------------------------------------------------
        // сообщить шине
        funToEventBus(getArgs) {

            if (typeof (getArgs.needConfirm) && getArgs.needConfirm === true) {
                if (!confirm('Вы уверены?')) return;
            }
            // сообщить шине
            this.$eventBus.$emit('fromControl', getArgs);

            if (getArgs.regim === 'mapSave') {
                console.log('--------- mapAttributes.objects =');
                console.log(this.mapAttributes.objects);
                //console.log(this.mapAttributes.keys.shift);
            }
        },

        // ------------------------------------------------------------------
        // смена положения текста-названия текущего обьекта в зависимости от переключателя
        funPositionChange(getPosition) {

            // записать в текущий обьект
            if (this.currentObject.parentPlacemarkMapID === null && this.currentObject.parentPolylineMapID === null) {
                // это родитель - меняем его текст-название
                this.currentObject.positionText = getPosition;
            } else {
                // это дочерний обьект - меняем его положение
                this.currentObject.position = getPosition;
            }
        },
    },
}
</script>

<style scoped>

.button_mini {
    background: #00dbff;
    color: #202346;
    border-radius: 6px;
    border: 0;
    margin: 3px 1px;
    padding: 5px 16px;
    font-size: 13px;
    cursor: pointer;
    text-align: center;
}

/* -------------------------------------- */
/* цвет кнопки - если на родителе уже установлен дочерний обьект */
.bg_have {
    background: #ffbf00;
    color: #202346;
}

/* -------------------------------------- */
/* анимация - плавное появление окон open */
.fade-enter-active, .fade-leave-active {
    transition: opacity .5s;
}

.fade-enter, .fade-leave-to /* .fade-leave-active до версии 2.1.8 */
{
    opacity: 0;
}

</style>
