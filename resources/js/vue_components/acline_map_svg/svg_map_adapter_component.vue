<template>

    <!-- положение родителя -->
    <g :transform="
        'translate(' +  mapObject.x + ', ' + mapObject.y + ') ' +
        'rotate(' + mapObject.angle + ')'">

        <!-- 8-мь положений -->
        <g :transform="'rotate(' + myRotate8Angle + ')'">

            <!-- адаптер -->

            <!-- ножка -->
            <polyline
                points="0 3, 0 7"
                :stroke="myStroke"
                :stroke-width="myStrokeWidth"
                :fill="myFill"
            ></polyline>

            <!-- корпус -->
            <rect
                :x="-mapObject.width/2"
                y="7"
                :width="mapObject.width"
                :height="mapObject.height"
                :fill="myFill"
                :stroke="myStroke"
                :stroke-width="myStrokeWidth"
            ></rect>

            <!--  усик 1 -->
            <polyline
                :points="(-mapObject.width/2) + ' ' + (7 + mapObject.height) + ', ' + (-mapObject.width/2 - 2) + ' ' + (7 + mapObject.height + 3)"
                :stroke="myStroke"
                :stroke-width="myStrokeWidth"
                :fill="myFill"
            ></polyline>

            <!--  усик 2 -->
            <polyline
                :points="(mapObject.width/2) + ' ' + (7 + mapObject.height) + ', ' + (mapObject.width/2 + 2) + ' ' + (7 + mapObject.height + 3)"
                :stroke="myStroke"
                :stroke-width="myStrokeWidth"
                :fill="myFill"
            ></polyline>

        </g>

        <!-- всплывающий hint -->
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
            myFill: "yellow",
            myStrokeWidth: "1",
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
        // угол 8-ка
        myRotate8Angle: function () {
            return serviceCalcAngleByPosition(this.mapObject.position);
        },
    },
    watch: {},
    methods: {}
};
</script>

<style scoped></style>
