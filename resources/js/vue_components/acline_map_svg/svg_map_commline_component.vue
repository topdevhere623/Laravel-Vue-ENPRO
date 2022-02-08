<template>

    <!-- положение родителя -->
    <g :transform="
        'translate(' +  mapObject.x + ', ' + mapObject.y + ') ' +
        'rotate(' + mapObject.angle + ')'">

        <!-- 8-мь положений -->
        <g :transform="'rotate(' + myRotate8Angle + ')'">

            <!-- линия связи -->

            <!-- буква С -->
            <text
                x="-2"
                y="14"
                font-family="Monospace"
                :font-size="myFontSize"
                :fill="myFill"
            >
                C
            </text>

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
            myFontSize: "8px",
        };
    },
    mounted() {
    },
    computed: {
        // вычисляемые значения
        // цвет от активности
        myFill: function () {
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
