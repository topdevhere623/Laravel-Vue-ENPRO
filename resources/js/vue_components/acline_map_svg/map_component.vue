<template>
    <svg
        version="1.1"
        xmlns="http://www.w3.org/2000/svg"
        style="border:1px solid #ff0000;"
        :width="mapAttributes.settings.svgWidthOriginalPx"
        :height="mapAttributes.settings.svgHeightOriginalPx"
        :viewBox="
            '0 0 ' +
                mapAttributes.settings.svgWidthOriginalPx *
                    mapAttributes.settings.svgScale +
                ' ' +
                mapAttributes.settings.svgHeightOriginalPx *
                    mapAttributes.settings.svgScale
        "
        preserveAspectRatio="xMinYMin meet"
        @mousemove="funOnMouseMove($event)"
        @mouseup="funOnMouseUp($event)"
        @click="funOnClickMapObject($event)"
        class="svg_area"
    >
            <g v-if="currentObject === null">
                <svg-map-new-object-target-component
                    :get-coord="{
                    x: mapAttributes.dynamic.currentSvgX,
                    y: mapAttributes.dynamic.currentSvgY
                }"
                ></svg-map-new-object-target-component>
            </g>

            <!-- вывод фигур в цикле -->
            <!--Полилайны сегментов-->
            <g v-for="mapObject in segmentsObj" :key="mapObject.id">
                <polyline style="pointer-events: none" :points="linesToPolyline(mapObject)" stroke-width="4" fill="none"  stroke="blue" />
            </g>
            <!-- линии - чтоб слоем ниже -->
            <g
                v-if="mapObject.mapType === 'polyline'"
                v-for="mapObject in mapAttributes.objects" :key="mapObject.mapID"
                @click="funOnClickMapObject($event, mapObject)"
                @_mouseout="funOnMouseUp($event, mapObject)"
                @mousedown="funOnMouseDownRegion($event, mapObject)"
            >
                <!-- линия 701 -->
                <g v-if="mapObject.type === '701'">
                    <svg-map-line701-component
                        :map-object="mapObject"
                        :map-objects="mapAttributes.objects"
                    ></svg-map-line701-component>
                </g>

                <!-- линия 702 -->
                <g v-if="mapObject.type === '702'">
                    <svg-map-line702-component
                        :map-object="mapObject"
                    ></svg-map-line702-component>
                </g>

            </g>
            <!-- точки - чтоб слоем выше -->
            <g
                v-if="mapObject.mapType === 'placemark'"
                v-for="mapObject in mapAttributes.objects" :key="mapObject.mapID"
                @click="funOnClickMapObject($event, mapObject)"
                @_mouseout="funOnMouseUp($event, mapObject)"
                @mousedown="funOnMouseDownRegion($event, mapObject)"
            >

                <!-- ТП -->
                <g v-if="mapObject.type === 'substation'">
                    <svg-map-substation-component
                        :map-object="mapObject"
                    ></svg-map-substation-component>
                </g>

                <!-- опора -->
                <g v-if="mapObject.type === 'tower'">
                    <svg-map-tower-component
                        :map-object="mapObject"
                    ></svg-map-tower-component>
                </g>

                <!-- потребитель -->
                <g v-if="mapObject.type === 'customer'">
                    <svg-map-customer-component
                        :map-object="mapObject"
                    ></svg-map-customer-component>
                </g>

                <!-- разрядник -->
                <g v-if="mapObject.type === 'discharger'">
                    <svg-map-discharger-component
                        :map-object="mapObject"
                    ></svg-map-discharger-component>
                </g>

                <!-- ОПН -->
                <g v-if="mapObject.type === 'opn'">
                    <svg-map-opn-component
                        :map-object="mapObject"
                    ></svg-map-opn-component>
                </g>

                <!-- заземление -->
                <g v-if="mapObject.type === 'grounding'">
                    <svg-map-grounding-component
                        :map-object="mapObject"
                    ></svg-map-grounding-component>
                </g>

                <!-- фонарь -->
                <g v-if="mapObject.type === 'lamp'">
                    <svg-map-lamp-component
                        :map-object="mapObject"
                    ></svg-map-lamp-component>
                </g>

                <!-- адаптер -->
                <g v-if="mapObject.type === 'adapter'">
                    <svg-map-adapter-component
                        :map-object="mapObject"
                    ></svg-map-adapter-component>
                </g>

                <!-- линия связи (c) -->
                <g v-if="mapObject.type === 'commline'">
                    <svg-map-commline-component
                        :map-object="mapObject"
                    ></svg-map-commline-component>
                </g>

                <!-- эти элементы внутри линии 701/702 вызываются и управляются -->
                <g v-if="false">

                    <!-- разьединитель -->
                    <g v-if="mapObject.type === 'disconnector'">
                        <svg-map-disconnector-component
                            :map-object="mapObject"
                        ></svg-map-disconnector-component>
                    </g>

                    <!-- реклоузер -->
                    <g v-if="mapObject.type === 'reklouzer'">
                        <svg-map-reklouzer-component
                            :map-object="mapObject"
                        ></svg-map-reklouzer-component>
                    </g>

                    <!-- выключатель нагрузки -->
                    <g v-if="mapObject.type === 'vna'">
                        <svg-map-vna-component
                            :map-object="mapObject"
                        ></svg-map-vna-component>
                    </g>
                </g>

                <!-- текст -->
                <g v-if="mapObject.type === 'text'">
                    <svg-map-text-component
                        :map-object="mapObject"
                    ></svg-map-text-component>
                </g>

            </g>
    </svg>
</template>

<script>
// подключение сервиса с общими функциями
import { serviceGetSvgFigure } from './services/serviceGetSvgFigure';
import {
    serviceCalcAnglePlacemark,
    serviceCalcAnglePolyline,
    serviceCalcPosition,
} from './services/serviceCalcAnglePositions';
import { serviceFilterObjectsOnCurrent, serviceFilterObjectsOnMapType } from './services/serviceGetFilterMapObjects';
import { serviceCoordsTLBR, serviceMapChangeCoordsCenter } from './services/serviceGeo';
import { serviceGetPlacemarkViewName, serviceGetPolylineStartEnd } from './services/serviceObjects';

export default {
    //el: '#svgContent',
    data() {
        return {
            loading: false,
            errored: false,
            unWatch: null,

            mapAttributes: {
                // настройки карты
                settings: {
                    centerGeo: [
                        myMapDefault.centerLat,
                        myMapDefault.centerLong,
                    ],
                    type: 'map', // тип карты
                    zoom: myMapDefault.zoom,
                    svgWidthOriginalPx: 900, // размеры SVG как на карте в пикселях
                    svgHeightOriginalPx: 900,
                    svgScale: 1, // во сколько раз увеличить кол-во точек на холсте svg
                    svgMinLat: null,
                    svgMinLong: null,
                    prX: null,
                    prY: null,
                },
                // динамика
                dynamic: {
                    currentSvgX: null, // координаты декартовые клика Пользователя текущие
                    currentSvgY: null,
                    currentLat: null,
                    moveTarget: null, // обьект перемещения
                    lineType: '701', // тип соединительной линией
                    objectMapIDMax: 0, // порядковый номер mapID нового добавляемого обьекта
                    segmentNMax: 0, // порядковый номер сегмента
                },
                keys: { // нажатие клавиш
                    shift: false,
                    ctrl: false,
                    alt: false,
                },
                // обьекты карты и svg
                objects: [],
                // сегменты
                segments: [],
            },
            zoom: 0.665,
            lowestX: Number.POSITIVE_INFINITY,
            lowestY: Number.POSITIVE_INFINITY,
            highestX: Number.NEGATIVE_INFINITY,
            highestY: Number.NEGATIVE_INFINITY,
            loadedMap: false,
        };
    },
    watch: {
        loadedMap: {
            deep: true,
            immediate: true,
            handler(val) {
                if (val) {
                    if (this.mapAttributes.objects.length > 0) {
                        this.lowestX = Number.POSITIVE_INFINITY;
                        this.lowestY = Number.POSITIVE_INFINITY;
                        this.highestX = Number.NEGATIVE_INFINITY;
                        this.highestY = Number.NEGATIVE_INFINITY;
                        this.mapAttributes.objects.map(obj => {
                            if (obj.x < this.lowestX) {
                                this.lowestX = obj.x;
                            }
                            if (obj.y < this.lowestY) {
                                this.lowestY = obj.y;
                            }
                            if (obj.x > this.highestX) {
                                this.highestX = obj.x;
                            }
                            if (obj.y > this.highestY) {
                                this.highestY = obj.y;
                            }
                        });
                        if (this.highestX - this.lowestX > 0 && this.highestY - this.lowestY > 0) {
                            this.mapAttributes.settings.svgWidthOriginalPx = this.highestX - this.lowestX;
                            this.mapAttributes.settings.svgHeightOriginalPx = this.highestY - this.lowestY;
                        } else {
                            this.mapAttributes.settings.svgWidthOriginalPx = 900;
                            this.mapAttributes.settings.svgHeightOriginalPx = 900;
                        }
                        this.mapAttributes.objects.map(obj => {
                            obj.x += ((this.highestX - this.lowestX) - 900)/2;
                            obj.y += ((this.highestY -  this.lowestY) - 900)/2;
                        });
                    }
                }
            },
        },
    },
    mounted() {
        const yandexMap = document.querySelector('.YMaps-layer-container');
        yandexMap.addEventListener('click', (e) => {
            const overlayArea = document.querySelector('.overlay-area');
            const yMapOverlayArea = document.querySelector('.YMaps-layer.YMaps-common-object-layer');
            const yMapLeft = parseInt(yMapOverlayArea.style.left.replace('px', ''));
            const yMapTop = parseInt(yMapOverlayArea.style.top.replace('px', ''));
            const left = parseInt(overlayArea.style.left.replace('px', '')) + yMapLeft;
            const top = parseInt(overlayArea.style.top.replace('px', '')) + yMapTop;
            const svgHalfWidth = (this.mapAttributes.settings.svgWidthOriginalPx / 2) * this.zoom;
            const svgHalfHeight = (this.mapAttributes.settings.svgHeightOriginalPx / 2) * this.zoom;
            let xPoint = e.clientX - 30;
            let yPoint = e.clientY - 57;
            let addedTop = 0;
            let addedLeft = 0;


            if (yPoint > (top - svgHalfHeight) && yPoint < (top + svgHalfHeight)) {
                const remainder = top - svgHalfHeight;
                this.mapAttributes.dynamic.currentSvgY = (yPoint - remainder) / this.zoom;
                if (xPoint < (left - svgHalfWidth)) {
                    addedLeft = ((left - svgHalfWidth) - xPoint) / this.zoom;
                    this.mapAttributes.settings.svgWidthOriginalPx += (((left - svgHalfWidth) - xPoint) * 2) / this.zoom;
                    this.mapAttributes.dynamic.currentSvgX = 0;
                } else if (xPoint > (left + svgHalfWidth)) {
                    addedLeft = (xPoint - (left + svgHalfWidth)) / this.zoom;
                    this.mapAttributes.settings.svgWidthOriginalPx += ((xPoint - (left + svgHalfWidth)) * 2) / this.zoom;
                    this.mapAttributes.dynamic.currentSvgX = this.mapAttributes.settings.svgWidthOriginalPx;
                }
            } else if (xPoint > (left - svgHalfWidth) && xPoint < (left + svgHalfWidth)) {
                const remainder = left - svgHalfWidth;
                this.mapAttributes.dynamic.currentSvgX = (xPoint - remainder) / this.zoom;
                if (yPoint < (top - svgHalfHeight)) {
                    addedTop = ((top - svgHalfHeight) - yPoint) / this.zoom;
                    this.mapAttributes.settings.svgHeightOriginalPx += (((top - svgHalfHeight) - yPoint) * 2) / this.zoom;
                    this.mapAttributes.dynamic.currentSvgY = 0;
                } else if (yPoint > (top + svgHalfHeight)) {
                    addedTop = (yPoint - (top + svgHalfHeight)) / this.zoom;
                    this.mapAttributes.settings.svgHeightOriginalPx += ((yPoint - (top + svgHalfHeight)) * 2) / this.zoom;
                    this.mapAttributes.dynamic.currentSvgY = this.mapAttributes.settings.svgHeightOriginalPx;
                }
            } else {
                if (xPoint < (left - svgHalfWidth)) {
                    this.mapAttributes.dynamic.currentSvgX = 0;
                    addedLeft = ((left - svgHalfWidth) - xPoint) / this.zoom;
                    this.mapAttributes.settings.svgWidthOriginalPx += (((left - svgHalfWidth) - xPoint) * 2) / this.zoom;
                } else if (xPoint > (left + svgHalfWidth)) {
                    this.mapAttributes.dynamic.currentSvgX = this.mapAttributes.settings.svgWidthOriginalPx;
                    addedLeft = (xPoint - (left + svgHalfWidth)) / this.zoom;
                    this.mapAttributes.settings.svgWidthOriginalPx += ((xPoint - (left + svgHalfWidth)) * 2) / this.zoom;
                }
                if (yPoint < (top - svgHalfHeight)) {
                    this.mapAttributes.dynamic.currentSvgY = 0;
                    addedTop = ((top - svgHalfHeight) - yPoint) / this.zoom;
                    this.mapAttributes.settings.svgHeightOriginalPx += (((top - svgHalfHeight) - yPoint) * 2) / this.zoom;
                } else if (yPoint > (top + svgHalfHeight)) {
                    this.mapAttributes.dynamic.currentSvgY = this.mapAttributes.settings.svgHeightOriginalPx;
                    addedTop = (yPoint - (top + svgHalfHeight)) / this.zoom;
                    this.mapAttributes.settings.svgHeightOriginalPx += ((yPoint - (top + svgHalfHeight)) * 2) / this.zoom;
                }
            }
            this.mapAttributes.objects.map(obj => {
                obj.x += addedLeft;
                obj.y += addedTop;
            });
        });
        const target = document.querySelector('.overlay-area');
        const config = { attributes: true, childList: true, subtree: true };
        const observer = new MutationObserver((mutationsList, observer) => {
            mutationsList.map(mutation => {
                if (mutation.target.style.transform.length > 0) {
                    this.zoom = mutation.target.style.transform.replace('scale(', '').replace(')', '');
                }
            });
        });
        observer.observe(target, config);

        // включить прослушку клавиатуры
        window.addEventListener('keyup', this.funOnKeyUp);
        window.addEventListener('keydown', this.funOnKeyDown);

        // расчет координат верхнего левого и нижнего правого углов
        this.funCoordsTLBR();

        // !!! временно для разработки
        //this.funMapLoad(642); //642 // 649

        // слежение за глобальным массивом
        this.funWatch('on');
    },
    created() {

        // подписаться на получение с шины
        this.$eventBus.$on('fromControl', this.funFromControl);
    },
    beforeDestroy() {

        // отписаться на получение с шины
        this.$eventBus.$off('fromControl');
    },
    computed: {
        segmentsObj() {
                const segmentsLines = [];

                this.mapAttributes.segments.forEach(segment => {
                    const lines = [];
                    const pointsStack = [];

                    segment.forEach(id=> {
                        const element = this.mapAttributes.objects.find(el => el.mapID === id);
                        pointsStack.push([ element.x1,  element.y1]);
                        pointsStack.push([ element.x2,  element.y2]);
                        lines.push({id: element.mapID, x1: element.x1, y1: element.y1, x2: element.x2, y2: element.y2, type: element.type})
                    });

                    let startEnd = [];
                    pointsStack.forEach(couple=> {
                        let pairsCount = 0;
                        pointsStack.forEach(coupleB=> {if (couple[0] === coupleB[0] && couple[1] === coupleB[1]) {pairsCount++;}});
                        if (pairsCount === 1) {startEnd.push(couple)}
                    });
                    const finalLines = [];
                    startEnd.forEach((point, index) => {
                        let line = lines.find(el=> el.x1 === point[0] && el.y1 === point[1] || el.x2 === point[0] && el.y2 === point[1]);
                        if (line) {
                            if (index === 0 && (line.x1 !== point[0] || line.y1 !== point[1])) {
                                line = this.revertLineCoords(line);

                                const lineObj = lines.find(el=>el.id === line.id);
                                //
                                lineObj.x1 = line.x1;
                                lineObj.y1 = line.y1;
                                lineObj.x2 = line.x2;
                                lineObj.y2 = line.y2;

                            }

                            finalLines.push(line);
                        }
                    });

                    lines.forEach((el, index) => {
                        if (index) {
                            const prevX2 = lines[index - 1].x2
                            const prevY2 = lines[index - 1].y2

                            if (el.x1 === prevX2 && el.y1 === prevY2) {
                                console.log('Всё хорошо');
                            } else if (el.x2 === prevX2 && el.y2 === prevY2) {
                                console.log('Линию нужно перевернуть');
                                el = this.revertLineCoords(el)
                            } else {
                                console.log('Нет общих точек');
                            }
                        }
                        lines[index] = el;
                    });

                    segmentsLines.push(lines);
                });

                console.log('Segments: ', segmentsLines);
                return segmentsLines;
        },
        // objectsCount() {
        //     return this.mapAttributes.objects.length;
        // },
        // svgPosition() {
        //     if (!this.loadedMap || this.highestX === Number.NEGATIVE_INFINITY || this.highestY === Number.NEGATIVE_INFINITY || this.lowestX === Number.POSITIVE_INFINITY || this.lowestY === Number.POSITIVE_INFINITY) {
        //         return {
        //             x: 0,
        //             y: 0,
        //         };
        //     } else {
        //         return {
        //             x: ((this.highestX - this.lowestX) - 900) / 2,
        //             y: ((this.highestY - this.lowestY) - 900) / 2,
        //         };
        //     }
        // },
        // текущий обьект
        currentObject: function () {
            let myObject = null;
            if (this.mapAttributes.objects.length > 0) {
                let myObjects = this.mapAttributes.objects.filter(function (item) {
                    return (item?.current);
                });
                if (myObjects.length > 0) {
                    myObject = myObjects[0];
                }
            }
            // возвращаемый параметр
            return myObject;
        },

        // прошлый обьект
        lastObject: function () {
            let myObject = null;
            if (this.mapAttributes.objects.length > 0) {
                let myObjects = this.mapAttributes.objects.filter(function (item) {
                    return (item.last);
                });
                if (myObjects.length > 0) {
                    myObject = myObjects[0];
                }
            }
            // возвращаемый параметр
            return myObject;
        },
    },
    methods: {
        log(e) {
            console.log('LOG:', e);
        },
        linesToPolyline(lines) {
            let string = '';

            lines.forEach((point) => {
                if (point.x1 || point.x2 || point.y1 || point.y2) {
                    string += ` ${point.x1},${point.y1} ${point.x2},${point.y2}`
                }
            });

            return string
        },
        revertLineCoords(line) {
            const x1 = line.x1;
            const y1 = line.y1;

            line.x1 = line.x2;
            line.y1 = line.y2;
            line.x2 = x1;
            line.y2 = y1;

            return line;
        },
        // слежение за глобальным массивом
        funWatch(getRegim) {
            //console.log('слежение ' + getRegim);
            switch (getRegim) {
                case 'on':
                    // включить слежение
                    this.unWatch = this.$watch(
                        'mapAttributes',
                        function () {
                            // пересчет глобального массива
                            this.funWatchMapAttributes();

                            // сообщить шине
                            this.$eventBus.$emit('fromMap', this.mapAttributes);
                        }, {
                            deep: true,
                        });
                    break;
                case 'off':
                    // выключить слежение
                    this.unWatch();
                    break;
            }
        },

        // -------------------------------------------------------------------------
        // пересчет глобального массива
        funWatchMapAttributes() {

            // но не во время перетаскивания
            if (this.mapAttributes.dynamic.moveTarget !== null) return;

            // слежение за глобальным массивом
            this.funWatch('off');

            let myObject, myObjects, myAngle;

            // ч.1 - динамическая настройка карты
            // преобразовать декартовые координаты в географические
            let myGeo = this.funConvertPxToGeo({
                x: this.mapAttributes.dynamic.currentSvgX,
                y: this.mapAttributes.dynamic.currentSvgY,
            });
            this.mapAttributes.dynamic.currentLat = myGeo.lat;
            this.mapAttributes.dynamic.currentLong = myGeo.long;

            // ч.2 - динамическая настройка обьектов карты (чтобы не вызывать при создании новых обьектов и не перепсчитывать при загрузке с БД)
            if (this.mapAttributes.objects.length > 0) {

                // ч.2.1 - только polyline - актуальная информация по вершинам, длины, марки провода и прочее)
                for (let i = 0; i < this.mapAttributes.objects.length; i++) {
                    myObject = this.mapAttributes.objects[i];
                    if ((myObject.type === '701' || myObject.type === '702') && myObject.deleted === false) {

                        // получить данные вершин (начало / конец)
                        let myStartEnd = serviceGetPolylineStartEnd(myObject, this.mapAttributes.objects);
                        myObject.x1 = myStartEnd.get('startX');
                        myObject.y1 = myStartEnd.get('startY');
                        myObject.x2 = myStartEnd.get('endX');
                        myObject.y2 = myStartEnd.get('endY');
                        myObject.startName = myStartEnd.get('startName');
                        myObject.endName = myStartEnd.get('endName');
                    }
                }

                // ч.2.2 - только placemark и только родители
                for (let i = 0; i < this.mapAttributes.objects.length; i++) {
                    myObject = this.mapAttributes.objects[i];
                    if (myObject.parentPlacemarkMapID === null && myObject.parentPolylineMapID === null &&
                        myObject.mapType === 'placemark' && myObject.deleted === false) {

                        // 2.1.1 - преобразовать декартовые координаты в географические
                        let myGeo = this.funConvertPxToGeo({
                            x: myObject.x,
                            y: myObject.y,
                        });
                        this.mapAttributes.objects[i].lat = myGeo.lat;
                        this.mapAttributes.objects[i].long = myGeo.long;

                        // 2.1.2 - видимое имя для точки от типа
                        this.mapAttributes.objects[i].viewName = serviceGetPlacemarkViewName(myObject);

                        // 2.1.3 - положение текста-названия от переключателя
                        let myCalcPositions = serviceCalcPosition(myObject);
                        this.mapAttributes.objects[i].textNameX = myCalcPositions.get('posX');
                        this.mapAttributes.objects[i].textNameY = myCalcPositions.get('posY');
                        this.mapAttributes.objects[i].textNameAlign = myCalcPositions.get('align');

                        // 2.1.4 - поиск только в связанных polyline
                        for (let j = 0; j < this.mapAttributes.objects.length; j++) {
                            let myPolyline = this.mapAttributes.objects[j];
                            if (myPolyline.mapType === 'polyline' && myPolyline.deleted === false) {

                                if (myPolyline.startMapID === myObject.mapID || myPolyline.endMapID === myObject.mapID) {

                                    let fieldX, fieldY, fieldRevers;
                                    if (myPolyline.startMapID === myObject.mapID) {
                                        fieldX = 'x1';
                                        fieldY = 'y1';
                                        fieldRevers = 'endMapID';
                                    } else {
                                        fieldX = 'x2';
                                        fieldY = 'y2';
                                        fieldRevers = 'startMapID';
                                    }

                                    this.mapAttributes.objects[j][fieldX] = myObject.x;
                                    this.mapAttributes.objects[j][fieldY] = myObject.y;

                                    // если на противоположной стороне тоже опора, то ее надо провернуть, и ее дочерние обьекты
                                    // найти обьект по mapID
                                    let myObjectRevers = this.funGetObjectOnMapID(myPolyline[fieldRevers]);
                                    if (myObjectRevers !== null && myObjectRevers.type === 'tower' && myObjectRevers.mapID !== myObject.mapID) {
                                        // поменять угол у этого обьекта
                                        myAngle = serviceCalcAnglePlacemark(myObjectRevers, serviceFilterObjectsOnMapType(this.mapAttributes.objects, 'polyline'));
                                        this.funSetObjectOnMapID(myObjectRevers.mapID, 'angle', myAngle);
                                    }

                                    // поменять угол у polyline
                                    this.mapAttributes.objects[j].angle = serviceCalcAnglePolyline(myPolyline);
                                }
                            }
                        }

                        // поменять угол и у этого обьекта
                        myAngle = serviceCalcAnglePlacemark(myObject, serviceFilterObjectsOnMapType(this.mapAttributes.objects, 'polyline'));
                        this.mapAttributes.objects[i].angle = myAngle;
                    }
                }

                // ч.2.3 - только дочерние обьекты
                // возвращает массив дочерних обьектов у родителя "по-старому" и "по-новому"
                let myArr = this.funNewParentArray();
                for (let i = 0; i < this.mapAttributes.objects.length; i++) {
                    myObject = this.mapAttributes.objects[i];
                    if ((typeof (myObject.parentPlacemarkMapID) !== 'undefined' && typeof (myObject.parentPolylineMapID) !== 'undefined' && myObject.parentPlacemarkMapID !== null && myObject.parentPolylineMapID !== null) &&
                        myObject.deleted === false) {

                        // родитель(-и)
                        let myParentPlacemark = this.funGetObjectOnMapID(myObject.parentPlacemarkMapID);
                        let myParentPolyline = this.funGetObjectOnMapID(myObject.parentPolylineMapID);

                        let myKeyStartEnd = null;
                        if (myParentPolyline !== null) {
                            // получить данные вершин (начало / конец)
                            let myStartEnd = serviceGetPolylineStartEnd(myParentPolyline, this.mapAttributes.objects);
                            if (myStartEnd.get('startMapID') === myParentPlacemark.mapID) {
                                myKeyStartEnd = 'start';
                            }
                            if (myStartEnd.get('endMapID') === myParentPlacemark.mapID) {
                                myKeyStartEnd = 'end';
                            }
                        }

                        // признак для родителя по типу дочернего обьекта
                        let myKey = null;
                        let myArr2 = myArr.filter(function (item) {
                            return (item.objectType === myObject.type && item.startEnd === myKeyStartEnd);
                        });
                        if (myArr2.length > 0) myKey = myArr2[0].key;

                        if (myParentPlacemark !== null) {
                            // взять данные родителя(-ей) и записать в дочернем обьекте
                            this.mapAttributes.objects[i].x = myParentPlacemark.x;
                            this.mapAttributes.objects[i].y = myParentPlacemark.y;
                            this.mapAttributes.objects[i].lat = myParentPlacemark.lat;
                            this.mapAttributes.objects[i].long = myParentPlacemark.long;

                            // записать признак родителю
                            myParentPlacemark[myKey] = myObject.mapID;
                        }

                        if (myParentPolyline !== null) {
                            // записать признак родителю
                            myParentPolyline[myKey] = myObject.mapID;
                        }

                        // взять данные родителя(-ей) и записать в дочернем обьекте
                        this.mapAttributes.objects[i].angle = (myParentPolyline === null) ? myParentPlacemark.angle : myParentPolyline.angle;
                    }
                }
            }

            // слежение за глобальным массивом
            this.funWatch('on');
        },

        // -------------------------------------------------------------------------
        // найти обьект по mapID
        funGetObjectOnMapID(getMapID) {

            let myReturn = null;

            let myObjects = this.mapAttributes.objects.filter(function (item) {
                return (item.mapID === getMapID);
            });
            if (myObjects.length > 0) {
                myReturn = myObjects[0];
            }

            // возвращаемый параметр
            return myReturn;
        },

        // -------------------------------------------------------------------------
        // записать обьекту по mapID и ключу значение
        funSetObjectOnMapID(getMapID, getKey, getValue) {

            this.mapAttributes.objects.map(function (item) {
                if (item.mapID === getMapID) {
                    // записать только обьекту с getMapID
                    item[getKey] = getValue;
                }
            });
        },

        // -------------------------------------------------------------------------
        // записать всем обьектам значение по ключу
        funSetAllObject(getKey, getValue) {

            this.mapAttributes.objects.map(function (item) {
                // записать всем
                item[getKey] = getValue;
            });
        },

        // -------------------------------------------------------------------------
        // получение событий с шины

        // -------------------------------------------------------------------------
        // получение события с панели управления картой
        funFromControl(getArgs) {

            switch (getArgs.regim) {
                case 'mapLoad':
                    // загрузка карты
                    this.funMapLoad(getArgs.aclineID);
                    break;
                case 'mapSave':
                    // сохранение карты
                    this.funMapSave();
                    break;
                case 'mapClear':
                    // очистить карту вернуть масштаб по умолчанию
                    this.funMapClear();
                    break;
                case 'objectAdd':
                    // добавление нового обьекта после клика на карте
                    this.funMapAddObjectAfterClick(getArgs);
                    break;
                case 'objectDelete':
                    // удаление текущего обьекта
                    this.funDeleteCurrentObject();
                    break;
                case 'objectSelect':
                    // пометить обьект текущим
                    this.funObjectCurrentLastUpdate(this.funGetObjectOnMapID(getArgs.mapID));
                    break;
                case 'segmentSelect':
                    // выделить сегмент
                    this.funSegmentCurrent(getArgs.segmentIndex);
                    break;
            }
        },

        // ------------------------------------------------------------------
        // гео

        // ------------------------------------------------------------------
        // расчет координат верхнего левого и нижнего правого углов
        funCoordsTLBR() {

            let myCoordsTLBR = serviceCoordsTLBR(
                this.mapAttributes.settings.svgWidthOriginalPx,
                this.mapAttributes.settings.svgHeightOriginalPx,
                this.mapAttributes.settings.svgScale,
            );

            this.mapAttributes.settings.prX = myCoordsTLBR.get('prX');
            this.mapAttributes.settings.prY = myCoordsTLBR.get('prY');
            this.mapAttributes.settings.svgMinLat = myCoordsTLBR.get('svgMinLat');
            this.mapAttributes.settings.svgMinLong = myCoordsTLBR.get('svgMinLong');
        },

        // ------------------------------------------------------------------
        // преобразовать географические координаты в декартовые
        funConvertGeoToPx(getGeo) {

            let myReturnPX = {
                x: Math.round((getGeo.long - this.mapAttributes.settings.svgMinLong) / this.mapAttributes.settings.prX),
                y: Math.round((getGeo.lat - this.mapAttributes.settings.svgMinLat) / this.mapAttributes.settings.prY),
            };

            // возвращаемый параметр
            return myReturnPX;
        },

        // ------------------------------------------------------------------
        // преобразовать декартовые координаты в географические
        funConvertPxToGeo(getPx) {

            let myReturnGeo = {
                lat: getPx.y * this.mapAttributes.settings.prY + this.mapAttributes.settings.svgMinLat,
                long: getPx.x * this.mapAttributes.settings.prX + this.mapAttributes.settings.svgMinLong,
            };

            // возвращаемый параметр
            return myReturnGeo;
        },

        // ------------------------------------------------------------------
        // определение географической середины ЛЭП
        funGetAclineCenter(getData) {

            let myMinLat = 999;
            let myMaxLat = -999;
            let myMinLong = 999;
            let myMaxLong = -999;

            // сканировать все опоры
            getData.map(function (item) {
                if ((item.mapType === 'placemark' || item.mapType === 'text') && item.deleted === false) {
                    if (item.lat < myMinLat) myMinLat = item.lat;
                    if (item.lat > myMaxLat) myMaxLat = item.lat;
                    if (item.long < myMinLong) myMinLong = item.long;
                    if (item.long > myMaxLong) myMaxLong = item.long;
                }
            });

            // центр
            this.mapAttributes.settings.centerGeo = [
                (myMaxLat + myMinLat) / 2,
                (myMaxLong + myMinLong) / 2,
            ];

            // записать в переменные
            myMapDefault.centerLong = this.mapAttributes.settings.centerGeo[1];
            myMapDefault.centerLat = this.mapAttributes.settings.centerGeo[0];

            //console.log("Новый центр для загруженной ЛЭП: ");
            //console.log(this.mapAttributes.settings.centerGeo);
        },

        // ------------------------------------------------------------------
        // очистить карту вернуть масштаб по умолчанию
        funMapClear() {

            // очистить массив обьектов
            this.mapAttributes.settings.svgWidthOriginalPx = 900;
            this.mapAttributes.settings.svgHeightOriginalPx = 900;
            this.mapAttributes.objects = [];
            this.mapAttributes.segments = [];
            // установить масштаб
            myMap.setZoom(myMapDefault.zoom);
        },

        // ------------------------------------------------------------------
        // сохранение карты
        funMapSave() {
        },

        // ------------------------------------------------------------------
        // клик на карте или обьекте
        funOnClickMapObject(event, getObject = null) {

            // console.log("Функция [funOnClickMapObject] с event =");
            // console.log(event);
            // console.log("getObject = ");
            // console.log(getObject);

            // пометить обьект активным (текущим)
            this.funObjectCurrentLastUpdate(getObject);

            // обновить текущие координаты
            if (
                getObject !== null &&
                this.currentObject !== null &&
                typeof (this.currentObject.x) !== 'undefined' &&
                typeof (this.currentObject.y) !== 'undefined'
            ) {
                // взять координаты обьекта
                this.mapAttributes.dynamic.currentSvgX = this.currentObject.x;
                this.mapAttributes.dynamic.currentSvgY = this.currentObject.y;
            } else {
                // взять координаты клика
                this.mapAttributes.dynamic.currentSvgX =
                    event.offsetX * this.mapAttributes.settings.svgScale;
                this.mapAttributes.dynamic.currentSvgY =
                    event.offsetY * this.mapAttributes.settings.svgScale;
            }

            // console.log("this.mapAttributes.dynamic.currentSvgX = ");
            // console.log(this.mapAttributes.dynamic.currentSvgX);
            // console.log("this.mapAttributes.dynamic.currentSvgY = ");
            // console.log(this.mapAttributes.dynamic.currentSvgY);

            // избежать дублирования клика на карте под обьектом
            event.stopPropagation();
        },

        // ------------------------------------------------------------------
        // перетаскивание обьектов

        // ------------------------------------------------------------------
        // начало перетаскивания обьекта по карте
        funOnMouseDownRegion(event, getObject) {

            if (getObject.current && getObject.parentPlacemarkMapID === null && getObject.parentPolylineMapID === null) {
                getObject.drag = {
                    type: 'MOVE',
                    x: getObject.x,
                    y: getObject.y,
                    mx: event.offsetX * this.mapAttributes.settings.svgScale,
                    my: event.offsetY * this.mapAttributes.settings.svgScale,
                };
            }
            this.mapAttributes.dynamic.moveTarget = getObject;

            // избежать дублирования события на карте
            event.stopPropagation();
        },

        // ------------------------------------------------------------------
        // процесс перетаскивания - событие движения мышки над холстом
        funOnMouseMove(event) {

            if (this.mapAttributes.dynamic.moveTarget == null) return;
            let getObject = this.mapAttributes.dynamic.moveTarget;

            if (getObject.current && getObject.drag !== undefined) {
                if (getObject.drag.type === 'MOVE') {
                    getObject.x =
                        (event.offsetX - getObject.drag.mx + getObject.drag.x) *
                        this.mapAttributes.settings.svgScale;
                    getObject.y =
                        (event.offsetY - getObject.drag.my + getObject.drag.y) *
                        this.mapAttributes.settings.svgScale;
                }
            }
        },

        // ------------------------------------------------------------------
        // завершение перетаскивания - событие отжатия клавиши мыши
        funOnMouseUp(event, element) {

            this.mapAttributes.dynamic.moveTarget = null;
        },

        // ------------------------------------------------------------------
        // выделенный (активный) обьект

        // ------------------------------------------------------------------
        // пометить обьект активным (текущим)
        funObjectCurrentLastUpdate(getObject) {

            // обьект, который был текущим, станет прошлым (если только это placemark)
            if (this.currentObject !== null && this.currentObject.mapType === 'placemark') {
                // записать всем обьектам значение по ключу
                this.funSetAllObject('last', false);
                // записать обьекту по mapID и ключу значение
                this.funSetObjectOnMapID(this.currentObject.mapID, 'last', true);
            }

            // записать всем обьектам значение по ключу
            if (this.mapAttributes.keys.shift === false) {
                this.funSetAllObject('current', false);
            }

            // проверка, можно ли новый обьект сделать текущим
            let needWrite = false;
            if (getObject !== null) {
                let myObjects = serviceFilterObjectsOnCurrent(this.mapAttributes.objects);
                if (myObjects !== null) {
                    if (myObjects.length === 0) {
                        // один текущий - новый обьект можно сделать текущим
                        needWrite = true;
                    } else {
                        if (myObjects.length >= 1) {
                            // несколько текущих
                            if (getObject.type === myObjects[0].type) {
                                // типы совпадают - новый обьект можно сделать текущим
                                needWrite = true;
                            } else {
                                // сообщение пользователю
                                toastr.warning('Выделяемые обьекты должны быть одного типа...');
                            }
                        }
                    }
                }
            }

            // можно ли новый обьект сделать текущим
            if (needWrite) {
                // записать обьекту по mapID и ключу значение
                this.funSetObjectOnMapID(getObject.mapID, 'current', true);
            }

        },

        // ------------------------------------------------------------------
        // удаление текущего обьекта
        funDeleteCurrentObject() {

            let myCurrentObjectMapID = this.currentObject.mapID;

            if (!confirm('Вы уверены?')) return;

            // ч.1 - удалить запись об этом дочернем обьекте у родителя (если он таковым является)
            // возвращает массив дочерних обьектов у родителя "по-старому" и "по-новому"
            let myArr = this.funNewParentArray();
            // удалить отметку о наличии дочернего обьекта у родителя
            if (this.mapAttributes.objects.length > 0) {
                for (let i = 0; i < this.mapAttributes.objects.length; i++) {
                    for (let j = 0; j < myArr.length; j++) {
                        let myKey = myArr[j].key;
                        if (this.mapAttributes.objects[i][myKey] === myCurrentObjectMapID) {
                            // записать null в поле
                            this.mapAttributes.objects[i][myKey] = null;
                            //delete this.mapAttributes.objects[i][myKey];
                        }
                    }
                }
            }

            // ч.2 - удалить сам обьект
            // вернуть массив без этого обьекта, без его дочерних обьектов и связующих линий
            this.mapAttributes.objects = this.mapAttributes.objects.filter(function (item) {
                return (
                    item.mapID !== myCurrentObjectMapID &&
                    item.parentPlacemarkMapID !== myCurrentObjectMapID &&
                    item.startMapID !== myCurrentObjectMapID &&
                    item.endMapID !== myCurrentObjectMapID);
            });
            this.currentObject = null;
        },

        // ------------------------------------------------------------------
        // событие нажатия клавиши на клавиатуре
        funOnKeyDown(event) {
            //console.log("Функция [funOnKeyDown] .Event.key = " + event.key);

            switch (event.key) {
                case 'ArrowUp':
                    break;
                case 'ArrowDown':
                    break;
                case 'ArrowLeft':
                    break;
                case 'ArrowRight':
                    break;
                case 'Delete':
                    // удаление текущего обьекта
                    this.funDeleteCurrentObject();
                    break;
                case 'Shift':
                    // сообщение пользователю
                    this.mapAttributes.keys.shift = true;
                    break;
            }

            //return event.preventDefault();
        },

        // ------------------------------------------------------------------
        // событие отпускания клавиши на клавиатуре
        funOnKeyUp(event) {
            //console.log("Функция [funOnKeyUp] .Event.key = " + event.key);

            switch (event.key) {
                case 'Shift':
                    // сообщение пользователю
                    toastr.success('Клавишу Shift больше не нажата...');
                    this.mapAttributes.keys.shift = false;
                    break;
            }

            //return event.preventDefault();
        },

        // ------------------------------------------------------------------
        // добавление нового обьекта после клика на карте
        // в глобальном массиве будет пересчет всех других данных
        funMapAddObjectAfterClick(getArgs) {

            // шаблон нового обьекта
            let myNewPlacemark = serviceGetSvgFigure(getArgs.type);
            // дописать к нему дополнительные поля
            myNewPlacemark.mapID = this.funMapIDGetNew();

            if (myNewPlacemark.type !== 'polyline') {
                myNewPlacemark.parentPlacemarkMapID = getArgs.parents.placemark;
                myNewPlacemark.parentPolylineMapID = getArgs.parents.polyline;
            }

            if (getArgs.parents.placemark === null && getArgs.parents.polyline === null) {
                // не имеет родителя(-ей) - сам родитель
                myNewPlacemark.x = this.mapAttributes.dynamic.currentSvgX;
                myNewPlacemark.y = this.mapAttributes.dynamic.currentSvgY;
            }

            // добавление обьекта в массив - он же холст svg
            this.mapAttributes.objects.push(myNewPlacemark);
            // пометить обьект активным (текущим)
            this.funObjectCurrentLastUpdate(myNewPlacemark);

            // можно ли соединить линией
            if ((getArgs.type === 'substation' || getArgs.type === 'tower' || getArgs.type === 'customer') &&
                this.lastObject !== null && this.lastObject.type !== 'text' && this.lastObject.mapType !== 'polyline') {

                // шаблон нового обьекта
                console.log('mapAttributes', this.lastObject);
                if (this.lastObject.allowedConnectionsCount === undefined || this.lastObject.allowedConnectionsCount > this.lastObject.connectionsCount) {
                    let myNewPolyline = serviceGetSvgFigure(this.mapAttributes.dynamic.lineType);
                    // дописать к нему дополнительные поля
                    myNewPolyline.mapID = this.funMapIDGetNew();
                    myNewPolyline.startMapID = this.lastObject.mapID;
                    myNewPolyline.endMapID = this.currentObject.mapID;

                    // добавление обьекта в массив - он же холст svg
                    this.mapAttributes.objects.push(myNewPolyline);
                    this.lastObject.connectionsCount++
                    this.currentObject.connectionsCount++
                }
            }

            this.funSegments();

            // возвращаемый параметр
            return myNewPlacemark.mapID;
        },

        // ------------------------------------------------------------------
        // загрузка карты
        funMapLoad(getAclineID) {

            // сообщение пользователю
            toastr.info('Начался процесс загрузки данных...');

            // признаки
            this.loading = true;
            this.errored = false;

            // данные post-запроса
            let form = new FormData();
            form.append('aclineID', getAclineID);
            this.loadedMap = false;
            axios
                .post('/api/aclineMapSvgLoad', form)
                .then(response => {
                    // для отладки
                    console.log('Загрузка данных этой ЛЭП успешно прошла!');
                    console.log(response.data);

                    let myData = JSON.parse(response.data.map_json);
                    console.log('Данные этой ЛЭП в JSON-е:');
                    console.log(myData);

                    // очистить карту вернуть масштаб по умолчанию
                    this.funMapClear();
                    // определение географической середины ЛЭП
                    this.funGetAclineCenter(myData);
                    // расчет координат верхнего левого и нижнего правого углов
                    this.funCoordsTLBR();
                    // смещение центра карты и оверлея
                    serviceMapChangeCoordsCenter();
                    // добавление на карту данных с черновика БД
                    this.funMapAddFromDraft(myData);

                    this.loadedMap = true;
                    // сообщение пользователю
                    toastr.success('Данные успешно загружены...');
                })
                .catch(error => {
                    // ошибка
                    this.errored = true;
                    // для отладки
                    console.log('Ошибка!');
                    console.log(error);
                    // сообщение пользователю
                    toastr.error('Ошибка при загрузке данных...');
                })
                .finally(() => {
                    // финальная обработка
                    this.loading = false;
                });
        },

        // ------------------------------------------------------------------
        // добавление на карту данных с черновика БД
        funMapAddFromDraft(getData) {

            // слежение за глобальным массивом
            this.funWatch('off');

            // ч.1 - добавление в массив данных с черновика - placemark и text
            this.funMapAddFromDraftPlacemarks(getData);
            // ч.2 - добавление в массив данных с черновика - polyline
            // (все Placemarks для соединения должны быть заранее определены x,y)
            this.funMapAddFromDraftPolylines(getData);
            // ч.3 - найти максимальный mapID
            // обьекты с черноваика имеют свой mapID, а для навесного оборудования создаются новые обькты с новым mapID
            this.funMapIDGetMax(getData);
            // ч.4 - разобрать на подвесное оборудование, которое сделали отдельными обьектами
            // для обратной совместимости
            this.funMapAddFromDraftEquipment();

            // пересчет глобального массива
            this.funWatchMapAttributes();
        },

        // ------------------------------------------------------------------
        // ч.1 - добавление в массив данных с черновика - placemark и text
        funMapAddFromDraftPlacemarks(getData) {

            if (getData.length > 0) {
                for (let i = 0; i < getData.length; i++) {
                    if ((getData[i].mapType === 'placemark' || getData[i].mapType === 'text') && getData[i].deleted === false) {

                        // шаблон нового обьекта
                        let myNewObject = serviceGetSvgFigure(getData[i].type);

                        // перенести обьекту все поля
                        for (let key in getData[i]) {
                            myNewObject[key] = getData[i][key];
                        }

                        // то, чего возможно нет в сохранненном массиве в старой версии
                        // декартовые координаты
                        if (myNewObject.x === 0 || myNewObject.y === 0 || typeof (myNewObject.x) === 'undefined' || typeof (myNewObject.y) === 'undefined') {
                            // преобразовать географические координаты в декартовые
                            let myPx = this.funConvertGeoToPx({
                                lat: getData[i].lat,
                                long: getData[i].long,
                            });
                            myNewObject.x = myPx.x;
                            myNewObject.y = myPx.y;
                        }

                        // добавление обьекта в массив - он же холст svg
                        this.mapAttributes.objects.push(myNewObject);
                    }
                }
            }
        },

        // ------------------------------------------------------------------
        // ч.2 - добавление в массив данных с черновика - polyline
        // (все Placemarks для соединения должны быть заранее определены x,y)
        funMapAddFromDraftPolylines(getData) {

            if (getData.length > 0) {
                for (let i = 0; i < getData.length; i++) {
                    if (getData[i].mapType === 'polyline' && getData[i].deleted === false) {
                        if (getData[i].type === 701) getData[i].type = '701';
                        if (getData[i].type === 702) getData[i].type = '702';

                        // шаблон нового обьекта
                        let myNewObject = serviceGetSvgFigure(String(getData[i].type)); // перевести в символьное - в базе есть строки с 701 и символьные "701"

                        // найти вершины
                        let myLastObject = this.mapAttributes.objects.find(item => (item.mapID === getData[i].startMapID));
                        let myCurrentObject = this.mapAttributes.objects.find(item => (item.mapID === getData[i].endMapID));
                        if (typeof (myLastObject) !== 'undefined' && typeof (myCurrentObject) !== 'undefined') {

                            myNewObject.startMapID = myLastObject.mapID;
                            myNewObject.endMapID = myCurrentObject.mapID;
                            myNewObject.x1 = myLastObject.x;
                            myNewObject.y1 = myLastObject.y;
                            myNewObject.x2 = myCurrentObject.x;
                            myNewObject.y2 = myCurrentObject.y;
                            myNewObject.name = getData[i].name;

                            // перенести обьекту все поля
                            for (let key in getData[i]) {
                                myNewObject[key] = getData[i][key];
                            }

                            // добавление обьекта в массив - он же холст svg
                            this.mapAttributes.objects.push(myNewObject);
                        }
                    }
                }
            }
        },

        // ------------------------------------------------------------------
        // ч.3 - найти максимальный mapID
        funMapIDGetMax(getMapObjects) {

            if (getMapObjects.length > 0) {
                for (let i = 0; i < getMapObjects.length; i++) {
                    if (getMapObjects[i].mapID > this.mapAttributes.dynamic.objectMapIDMax) {
                        // да, больше - тогда записать в переменную
                        this.mapAttributes.dynamic.objectMapIDMax =
                            getMapObjects[i].mapID;
                    }
                }
            }
        },

        // ------------------------------------------------------------------
        // ч.4 - разобрать на подвесное оборудование, которое сделали отдельными обьектами
        // для обратной совместимости
        funMapAddFromDraftEquipment() {

            let myNewObjectMapID, myKey, myObjectType;

            // возвращает массив дочерних обьектов у родителя "по-старому" и "по-новому"
            let myArr = this.funNewParentArray();

            if (this.mapAttributes.objects.length > 0) {
                for (let i = 0; i < this.mapAttributes.objects.length; i++) {
                    let myObject = this.mapAttributes.objects[i];
                    for (let j = 0; j < myArr.length; j++) {
                        myKey = myArr[j].key;
                        myObjectType = myArr[j].objectType;
                        if (myObject[myKey] === 1) {
                            // есть оборудование

                            if (myObject.mapType === 'placemark') {
                                // установлено на опоре
                                // добавление нового обьекта после клика на карте
                                myNewObjectMapID = this.funMapAddObjectAfterClick({
                                    'type': myObjectType,
                                    'mapID': myObject.mapID,
                                    'parents': {
                                        'placemark': myObject.mapID,
                                        'polyline': null,
                                    },
                                });
                                // записать родителю признак наличия
                                this.funSetObjectOnMapID(myObject.mapID, myKey, myNewObjectMapID);
                            } else {
                                // установлено на линии
                                // получить данные вершин (начало / конец)
                                let myStartEnd = serviceGetPolylineStartEnd(myObject, this.mapAttributes.objects);
                                if (myKey.indexOf('Start')) {
                                    // добавление нового обьекта после клика на карте
                                    myNewObjectMapID = this.funMapAddObjectAfterClick({
                                        'type': myObjectType,
                                        'mapID': myObject.mapID,
                                        'startMapID': myStartEnd.get('startMapID'), // местами поменял start-end, что грузилось правильно
                                        'parents': {
                                            'placemark': myStartEnd.get('startMapID'),
                                            'polyline': myObject.mapID,
                                        },
                                    });
                                    // записать родителю признак наличия
                                    this.funSetObjectOnMapID(myObject.mapID, myKey, myNewObjectMapID);
                                    this.funSetObjectOnMapID(myStartEnd.startMapID, myKey, myNewObjectMapID);
                                } else {
                                    // добавление нового обьекта после клика на карте
                                    myNewObjectMapID = this.funMapAddObjectAfterClick({
                                        'type': myObjectType,
                                        'mapID': myObject.mapID,
                                        'endMapID': myStartEnd.get('endMapID'), // местами поменял start-end, что грузилось правильно
                                        'parents': {
                                            'placemark': myStartEnd.get('endMapID'),
                                            'polyline': myObject.mapID,
                                        },
                                    });
                                    // записать родителю признак наличия
                                    this.funSetObjectOnMapID(myObject.mapID, myKey, myNewObjectMapID);
                                    this.funSetObjectOnMapID(myStartEnd.endMapID, myKey, myNewObjectMapID);
                                }
                            }
                            // обнулить, что в будующем не повторить
                            //delete this.mapAttributes.objects[i][myArr[j].key];
                        } else {
                            // очистить поле (по старому либо 1 - есть, либо 0 или null - если нет. А по новому 0 - это распозается, как mapID с 0-ым номером обьекта)
                            this.mapAttributes.objects[i][myKey] = null;
                        }
                    }
                }
            }
        },

        // ------------------------------------------------------------------
        // возвращает массив дочерних обьектов у родителя "по-старому" и "по-новому"
        funNewParentArray(getType = null) {

            let myReturn;

            let myArr = [
                { key: 'eqDischargerStart', objectType: 'discharger', startEnd: 'start' }, // был только один
                { key: 'eqDischargerEnd', objectType: 'discharger', startEnd: 'end' },
                { key: 'eqOPNStart', objectType: 'opn', startEnd: 'start' }, // был только один
                { key: 'eqOPNEnd', objectType: 'opn', startEnd: 'end' },
                { key: 'eqGrounding', objectType: 'grounding', startEnd: null },
                { key: 'eqLamp', objectType: 'lamp', startEnd: null },
                { key: 'eqAdapter', objectType: 'adapter', startEnd: null },
                { key: 'eqCommLine', objectType: 'commline', startEnd: null },
                { key: 'eqDisconnectorStart', objectType: 'disconnector', startEnd: 'start' },
                { key: 'eqDisconnectorEnd', objectType: 'disconnector', startEnd: 'end' },
                { key: 'eqReklouzerStart', objectType: 'reklouzer', startEnd: 'start' },
                { key: 'eqReklouzerEnd', objectType: 'reklouzer', startEnd: 'end' },
                { key: 'eqVNaStart', objectType: 'vna', startEnd: 'start' },
                { key: 'eqVNaEnd', objectType: 'vna', startEnd: 'end' },
            ];

            if (getType === null) {
                // вернуть весь массив
                myReturn = myArr;
            } else {
                // вернуть только строчку нужного типа
                let myArr2 = myArr.filter(function (item) {
                    return (item.objectType === getType);
                });
                myReturn = myArr2[0];
            }

            // возвращаемый параметр
            return myReturn;
        },

        // ------------------------------------------------------------------
        // порядковый номер mapID нового добавляемого обьекта
        funMapIDGetNew() {

            // увеличить порядковый номер
            this.mapAttributes.dynamic.objectMapIDMax++;

            // возвращаемый параметр
            return this.mapAttributes.dynamic.objectMapIDMax;
        },

        // ------------------------------------------------------------------
        // анализ сегментов

        // выделить сегмент
        funSegmentCurrent(getSegmentIndex) {

            // снять выделение со всех
            // записать всем обьектам значение по ключу
            this.funSetAllObject('current', false);

            if (typeof (this.mapAttributes.segments[getSegmentIndex]) !== 'undefined') {
                for (let i = 0; i < this.mapAttributes.segments[getSegmentIndex].length; i++) {
                    // найти обьект по mapID
                    let myPolyline = this.funGetObjectOnMapID(this.mapAttributes.segments[getSegmentIndex][i]);
                    myPolyline.current = true;
                }
            }
        },

        // ------------------------------------------------------------------
        // определение сегментов
        funSegments() {

            this.mapAttributes.segments = [];

            if (this.mapAttributes.objects.length > 0) {

                // определение точки, с которой начнется анализ сегментов (точка входа)
                let myPoint = this.funSegmentsPointIn();

                if (myPoint !== null) {
                    // рекурсивная функция иследования линии от заданной точки
                    this.funSegmentRecursExploreLine(myPoint);
                }
            }
        },

        // ------------------------------------------------------------------
        // определение точки, с которой начнется анализ сегментов (точка входа)
        funSegmentsPointIn() {

            let myPoint = null;

            // список всех линий
            let Polylines = this.mapAttributes.objects.filter(function (item) {
                return (item.type === '701' || item.type === '702');
            });

            // список всех вершин линий
            if (Polylines.length > 0) {
                let Arr1 = [];
                Polylines.forEach(function (item) {
                    Arr1.push(item.startMapID, item.endMapID);
                });

                if (Arr1.length > 0) {
                    // список вершин без повторов
                    let Arr2 = Array.from(new Set(Arr1));

                    // просчитать сколько раз повторяется вершина
                    let Arr3 = [];
                    Arr2.forEach(function (item1) {
                        let ArrTemp = Arr1.filter(function (item2) {
                            return (item2 === item1);
                        });
                        Arr3.push({
                            'mapID': item1,
                            'kol': ArrTemp.length,
                        });
                    });

                    // список вершин, которые не повторяются
                    let Arr4 = Arr3.filter(function (item) {
                        return (item.kol === 1);
                    });

                    // вершина, с которой начнем разбор
                    myPoint = Arr4[0].mapID;
                }
            }

            // возвращаемый параметр
            console.log('Точка входа = ' + myPoint);
            return myPoint;
        },

        // ---------------------------------------------------------------
        // рекурсивная функция иследования линии от заданной точки
        funSegmentRecursExploreLine(getMapID) {

            // подсчет кол-ва линий от заданной точки
            let myKolPolylines = this.funKolPolylinesThisPlacemark(getMapID);
            let myKolPolylinesAll = myKolPolylines.all;
            let myKolPolylinesOnlyCustomers = myKolPolylines.onlyCustomers;

            if (myKolPolylinesAll.length > 0) {
                // линии есть

                // удалить из исследуемого массива, чтоб в бесконечность не уходило
                myKolPolylinesAll = this.funSegmentMinusPolylines(myKolPolylinesAll);
                myKolPolylinesOnlyCustomers = this.funSegmentMinusPolylines(myKolPolylinesOnlyCustomers);

                for (let i = 0; i < myKolPolylinesAll.length; i++) {
                    let myCurrentPolylineMapID = myKolPolylinesAll[i];

                    // найти обьект по mapID
                    let myTemp = this.funGetObjectOnMapID(myCurrentPolylineMapID);
                    // конечная точка текущей линии
                    let myPolylineEndMapID = (getMapID === myTemp.startMapID) ? myTemp.endMapID : myTemp.startMapID;

                    // проверка, создать новый сегмент или нет
                    let myNeedNewegment = false;
                    if (myKolPolylinesOnlyCustomers.length > 0) {
                        // потребители есть в этой связке
                        if (myKolPolylinesAll.length - myKolPolylinesOnlyCustomers.length >= 2) {
                            myNeedNewegment = true;
                        }
                    } else {
                        // потребителей нет в этой связке
                        if (myKolPolylinesAll.length >= 2) {
                            // да, это разветвление больше 2-х линий (3-тью линию выше через функцию funSegmentMinusPolylines уже убрал)
                            myNeedNewegment = true;
                        }
                    }

                    // создать новый сегмент
                    if (myNeedNewegment) {
                        // увеличить порядковый номер, если это не начало
                        if (this.mapAttributes.segments.length > 0) {
                            this.mapAttributes.dynamic.segmentNMax++;
                        }
                    }
                    // записать пролет в сегмент
                    if (typeof (this.mapAttributes.segments[this.mapAttributes.dynamic.segmentNMax]) === 'undefined') {
                        this.mapAttributes.segments[this.mapAttributes.dynamic.segmentNMax] = [];
                    }
                    this.mapAttributes.segments[this.mapAttributes.dynamic.segmentNMax].push(myCurrentPolylineMapID);

                    // рекурсивная функция иследовать линию от заданной точки
                    this.funSegmentRecursExploreLine(myPolylineEndMapID);
                }
            }
        },

        // ---------------------------------------------------------------
        // удалить линии из прошлых уже учтенных сегментов
        funSegmentMinusPolylines(getSegments) {

            if (getSegments.length > 0) {
                for (let i = 0; i < getSegments.length; i++) {
                    let item = getSegments[i];

                    if (this.mapAttributes.segments.length > 0) {
                        for (let j = 0; j < this.mapAttributes.segments.length; j++) {
                            let item2 = this.mapAttributes.segments[j];

                            if (typeof (item2) !== 'undefined' && item2.length > 0) {
                                for (let k = 0; k < item2.length; k++) {
                                    let item3 = item2[k];

                                    if (item === item3) {
                                        getSegments.splice(i, 1);
                                    }
                                }
                            }
                        }
                    }
                }
            }
            // возвращаемый параметр
            return getSegments;
        },

        // ---------------------------------------------------------------
        // подсчет кол-ва линий от заданной точки
        // вернет массив с 3-мя массива внутри: всех, только Потребителей, без Потребителей
        funKolPolylinesThisPlacemark(getMapID) {

            let myKolPolylinesAll = [];
            let myKolPolylinesOnlyCustomers = [];
            let myKolPolylinesNoCustomers = [];

            for (let i = 0; i < this.mapAttributes.objects.length; i++) {
                let item = this.mapAttributes.objects[i];

                if (item.mapType === 'polyline' && (item.startMapID === getMapID || item.endMapID === getMapID) && item.deleted === false) {
                    // эта точка является вершиной
                    // записать в массив
                    myKolPolylinesAll.push(item.mapID);

                    // проверка является ли линия с Потребителем
                    if ((this.funGetObjectOnMapID(item.startMapID)).type === 'customer' || (this.funGetObjectOnMapID(item.endMapID)).type === 'customer') {
                        // да, один из концов - Потребитель
                        myKolPolylinesOnlyCustomers.push(item.mapID);
                    } else {
                        myKolPolylinesNoCustomers.push(item.mapID);
                    }
                }
            }

            // возвращаемый параметр
            return {
                'all': myKolPolylinesAll,
                'onlyCustomers': myKolPolylinesOnlyCustomers,
                'noCustomers': myKolPolylinesNoCustomers,
            };
        },
    },
};
</script>

<style scoped>
.area {
    position: absolute;
    width: 900px; /* было 300 */
    height: 900px; /* было 300 */
    opacity: 0.9;
    transform: translate(-50%, -50%);
    border: solid 1px blue;
}
</style>
