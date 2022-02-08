<template>
    <g>
        <!-- ТП -->

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

        <g :transform="'translate(' + (mapObject.x - mapObject.width/2) + ', ' + (mapObject.y - mapObject.height/2) + ')'">

            <!-- основной обьект -->
            <rect
                :width="mapObject.width"
                :height="mapObject.height"
                :fill="myFill"
                :stroke="myStroke"
                :stroke-width="myStrokeWidth"
            ></rect>

            <!-- всплывающий хинт -->
            <title>
                {{ mapObject.hint }}
            </title>

        </g>
    </g>
</template>

<script>
import {serviceCalcAngleByPosition} from "./services/serviceCalcAnglePositions";

export default {
    props: {
        mapObject: Object,
    },
    data() {
        return {
            // оформление обьекта по-умолчанию
            myFill: 'orange',
            myStrokeWidth: '1',
            // оформление текста-надписи по-умолчанию
            myTextNameFontSize: '7px',
            myTextNameFill: '#000000',
        }
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
    watch: {},
    methods: {}
}
</script>

<style scoped>
</style>
