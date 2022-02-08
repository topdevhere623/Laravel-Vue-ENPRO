<template>

    <!-- положение родителя -->
    <g :transform="
        'translate(' +  mapObject.x + ', ' + mapObject.y + ') ' +
        'rotate(' + mapObject.angle + ')'">

        <!-- 8-мь положений -->
        <g :transform="'rotate(' + myRotate8Angle + ')'">

            <!-- фонарь -->

            <!-- круг -->
            <circle
                cx="0"
                cy="12"
                :r="mapObject.r"
                :fill="myFill"
                :stroke="myStroke"
            >
            </circle>

            <!-- 2 полоски внутри круга -->
            <polyline
                points="-2 10, 2 14"
                :stroke="myStroke"
                :stroke-width="myStrokeWidth"
                :fill="myFill"
            ></polyline>
            <polyline
                points="2 10, -2 14"
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
            myFill: "transparent",
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
