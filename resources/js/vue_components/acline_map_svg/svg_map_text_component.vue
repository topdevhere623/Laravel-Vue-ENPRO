<template>
    <g :transform="'translate(' + (mapObject.x - mapObject.width/2) + ', ' + (mapObject.y + mapObject.height/2) + ')'">

        <!-- текст -->
        <text class="mapText"
              :fill="myFill"
              :text-anchor="myTextAnchor"
        >
            {{ mapObject.text }}
        </text>

        <!-- всплывающий hint -->
        <title>
            {{ mapObject.hint }}
        </title>

    </g>
</template>

<script>

export default {
    props: {
        mapObject: Object,
    },
    data() {
        return {
            // оформление обьекта по-умолчанию
            myTextAnchor: 'left',
        }
    },
    mounted() {
    },
    computed: {
        // вычисляемые значения
        // цвет от активности
        myFill: function () {
            return ((this.mapObject.current) ? 'red' : 'black');
        },
    },
    watch: {},
}
</script>

<style scoped>
.mapText {
    font: normal 7px;
}
</style>
