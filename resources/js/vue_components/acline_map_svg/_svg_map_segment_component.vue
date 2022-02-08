<template>
    <g>

        <!-- сегмент -->

    </g>
</template>

<script>

export default {
    props: {
        mapObject: Object,
        mapObjects: Array,
    },
    data() {
        return {
            // оформление обьекта по-умолчанию
            myStrokeWidth: '1',
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

        // цвет от активности
        myStroke: function () {
            return ((this.mapObject.current) ? 'red' : 'black');
        }
    },
    methods: {
    }
}
</script>

<style scoped>
</style>
