<template>

    <!-- положение родителя -->
    <g :transform="
        'translate(' +  mapObject.x + ', ' + mapObject.y + ') ' +
        'rotate(' + mapObject.angle + ')'">

        <!-- 8-мь положений -->
        <g :transform="'rotate(' + myRotate8Angle + ')'">

            <!-- заземление -->

            <!-- ножка -->
            <polyline
                points="0 3, 0 9"
                :stroke="myStroke"
                :stroke-width="myStrokeWidth"
                :fill="myFill"
            ></polyline>
            <!-- полоски -->
            <polyline
                points="-4 9, 4 9"
                :stroke="myStroke"
                :stroke-width="myStrokeWidth"
                :fill="myFill"
            ></polyline>
            <polyline
                points="-2 11, 2 11"
                :stroke="myStroke"
                :stroke-width="myStrokeWidth"
                :fill="myFill"
            ></polyline>
            <polyline
                points="-1 13, 1 13"
                :stroke="myStroke"
                :stroke-width="myStrokeWidth"
                :fill="myFill"
            >
            </polyline>

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
            myFill: "red",
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
