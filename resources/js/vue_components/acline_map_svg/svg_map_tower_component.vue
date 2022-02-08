<template>
    <g>
        <!-- опора -->

        <!-- надпись -->
        <text
            :transform="'translate(' + mapObject.x + ', ' + mapObject.y + ')'"
            :x="mapObject.textNameX"
            :y="mapObject.textNameY"
            :text-anchor="mapObject.textNameAlign"
            :font-size="myTextNameFontSize"
            :fill="myTextNameFill"
        >
            {{ mapObject.viewName }}
        </text>

        <!-- основной обьект -->
        <g :transform="
        'translate(' +  (mapObject.x - mapObject.r) + ', ' + (mapObject.y - mapObject.r) + ') ' +
        'rotate(' + mapObject.angle + ' ' + mapObject.r + ' ' + mapObject.r + ')'">

            <!-- --------------------------------------- -->
            <!-- опора деревянная - круг -->
            <g v-if="Number(mapObject.towerMaterial) === 1">
                <circle
                    :cx="mapObject.r"
                    :cy="mapObject.r"
                    :r="mapObject.r"
                    :fill="myFill"
                    :stroke="myStroke"
                    :stroke-width="myStrokeWidth"
                ></circle>

                <!-- опора деревянная c приставкой железобетон - квадрат внутри круга -->
                <polygon v-if="mapObject.annex === 'concrete'"
                         :points="
                            (mapObject.r - 1) + ' ' + (mapObject.r - 1) + ', ' +
                            (mapObject.r + 1) + ' ' + (mapObject.r - 1) + ', ' +
                            (mapObject.r + 1) + ' ' + (mapObject.r + 1) + ', ' +
                            (mapObject.r - 1) + ' ' + (mapObject.r + 1) + ', ' +
                            (mapObject.r - 1) + ' ' + (mapObject.r - 1)"
                         :fill="myFill"
                         :stroke="myStroke"
                         :stroke-width="myStrokeWidth"
                ></polygon>

                <!-- опора деревянная c приставкой металл - треуголник внутри круга -->
                <polygon v-if="mapObject.annex === 'metal'"
                         :points="
                            mapObject.r + ' 1.2, ' +
                            (mapObject.r * 1.4) + ' ' + (mapObject.r * 1.4) + ', ' +
                            '2 ' + (mapObject.r * 1.4) + ', ' +
                            mapObject.r + ' 1.2'"
                         :fill="myFill"
                         :stroke="myStroke"
                         :stroke-width="myStrokeWidth"
                ></polygon>

            </g>

            <!-- --------------------------------------- -->
            <!-- опора металлическая - треугольник -->
            <polygon v-if="Number(mapObject.towerMaterial) === 2"
                     :points="
                        mapObject.r + ', -1 ' +
                        (mapObject.r * 2 + 1) + ', ' + (mapObject.r * 1.7) + ' ' +
                        '-1 ,' + (mapObject.r * 1.7) + ' ' +
                        mapObject.r + ' -1'"
                     :fill="myFill"
                     :stroke="myStroke"
                     :stroke-width="myStrokeWidth"
            ></polygon>

            <!-- --------------------------------------- -->
            <!-- опора железобетон - квадрат -->
            <rect v-if="Number(mapObject.towerMaterial) === 3"
                  :width="(mapObject.r * 2)"
                  :height="(mapObject.r * 2)"
                  :fill="myFill"
                  :stroke="myStroke"
                  :stroke-width="myStrokeWidth"
            ></rect>

            <!-- --------------------------------------- -->
            <!-- оттяжка -->
            <!-- левая -->
            <g v-if="mapObject.guy === 'left'">
                <line
                    :x1="0"
                    :y1="mapObject.r"
                    :x2="-5"
                    :y2="mapObject.r"
                    :stroke="myStroke"
                    :stroke-width="myStrokeWidth"
                ></line>
                <line
                    :x1="-5"
                    :y1="0"
                    :x2="-5"
                    :y2="(mapObject.r * 2)"
                    :stroke="myStroke"
                    :stroke-width="myStrokeWidth"
                ></line>
            </g>
            <!-- правая -->
            <g v-if="mapObject.guy === 'right'">
                <line
                    :x1="(mapObject.r * 2)"
                    :y1="mapObject.r"
                    :x2="(mapObject.r * 2 + 5)"
                    :y2="mapObject.r"
                    :stroke="myStroke"
                    :stroke-width="myStrokeWidth"
                ></line>
                <line
                    :x1="(mapObject.r * 2 + 5)"
                    :y1="0"
                    :x2="(mapObject.r * 2 + 5)"
                    :y2="(mapObject.r * 2)"
                    :stroke="myStroke"
                    :stroke-width="myStrokeWidth"
                ></line>
            </g>

            <!-- --------------------------------------- -->
            <!-- подкос - в сторону угла стрелка -->
            <g v-if="Number(mapObject.strutN) > 0 && (mapObject.strut === 'concrete' || mapObject.strut === 'wood' || mapObject.strut === 'metal')">
                <!-- ножка - биссектриса угла -->
                <line
                    :x1="mapObject.r"
                    :y1="0"
                    :x2="mapObject.r"
                    :y2="- 10"
                    :stroke="myStroke"
                    :stroke-width="myStrokeWidth"
                ></line>
                <!-- стрелка -->
                <!-- подкос железобетон - треугольники вверх -->
                <polygon v-if="mapObject.strut === 'concrete'"
                         v-for="n in Number(mapObject.strutN)"
                         :key="n"
                         :points="
                            (mapObject.r - 2) + ', ' + ((-(n - 1) * 3) - 7) + ' ' +
                            (mapObject.r) + ', ' + ((-(n - 1) * 3) - 10) + ' ' +
                            (mapObject.r + 2) + ', ' + ((-(n - 1) * 3) - 7) + ' ' +
                            (mapObject.r - 2) + ', ' + ((-(n - 1) * 3) - 7)"
                         :fill="myFill"
                         :stroke="myStroke"
                         :stroke-width="myStrokeWidth"
                ></polygon>

                <!-- подкос дерево - треугольники вниз -->
                <polygon v-if="mapObject.strut === 'wood'"
                         v-for="n in Number(mapObject.strutN)"
                         :key="n"
                         :points="
                            (mapObject.r) + ', ' + ((-(n - 1) * 3) - 7) + ' ' +
                            (mapObject.r - 2) + ', ' + ((-(n - 1) * 3) - 10) + ' ' +
                            (mapObject.r + 2) + ', ' + ((-(n - 1) * 3) - 10) + ' ' +
                            (mapObject.r) + ', ' + ((-(n - 1) * 3) - 7)"
                         :fill="myFill"
                         :stroke="myStroke"
                         :stroke-width="myStrokeWidth"
                ></polygon>
                <!-- подкос металл - ромбики -->
                <polygon v-if="mapObject.strut === 'metal'"
                         v-for="n in Number(mapObject.strutN)"
                         :key="n"
                         :points="
                            (mapObject.r) + ', ' + ((-(n - 1) * 3) - 7) + ' ' +
                            (mapObject.r - 2) + ', ' + ((-(n - 1) * 3) - 9) + ' ' +
                            (mapObject.r) + ', ' + ((-(n - 1) * 3) - 11) + ' ' +
                            (mapObject.r + 2) + ', ' + ((-(n - 1) * 3) - 9) + ' ' +
                            (mapObject.r) + ', ' + ((-(n - 1) * 3) - 7)"
                         :fill="myFill"
                         :stroke="myStroke"
                         :stroke-width="myStrokeWidth"
                ></polygon>
            </g>

            <!-- --------------------------------------- -->
            <!-- аварийная -->
            <g v-if="Number(mapObject.eqAccident) === 1">
                <circle
                    r="1.5"
                    :cx="mapObject.r"
                    :cy="mapObject.r"
                    fill="red"
                ></circle>
            </g>

            <!-- --------------------------------------- -->
            <!-- подьем запрещен -->
            <g v-if="Number(mapObject.eqNoUp) === 1">
                <polyline
                    :points="
                        (mapObject.r - 1) + ', ' + (mapObject.r - 1) + ' ' +
                        (mapObject.r + 1) + ' ' + (mapObject.r + 1)"
                    stroke="red"
                    :stroke-width="myStrokeWidth"
                ></polyline>
                <polyline
                    :points="
                        (mapObject.r + 1) + ' ' +  (mapObject.r - 1) + ' ' +
                        (mapObject.r - 1) + ' ' + (mapObject.r + 1)"
                    stroke="red"
                    :stroke-width="myStrokeWidth"
                ></polyline>
            </g>

        </g>

        <!-- всплывающий хинт -->
        <title>
            {{ mapObject.hint }}
        </title>

    </g>
</template>

<script>
// подключение сервиса с общими функциями
import {
    serviceCalcAngleByPosition
} from "./services/serviceCalcAnglePositions";

export default {
    props: {
        mapObject: Object,
    },
    data() {
        return {
            // оформление обьекта по-умолчанию
            myFill: "white",
            myStrokeWidth: "1",
            // оформление текста-надписи по-умолчанию
            myTextNameFontSize: "7px",
            myTextNameFill: "#000000",
        };
    },
    mounted() {
    },
    computed: {
        // вычисляемые значения
        // цвет от активности
        myStroke: function () {
            return ((this.mapObject.current) ? 'red' : 'black');
        },
    },
};
</script>

<style scoped></style>
