<template>

    <!-- положение родителя -->
    <g :transform="
        'translate(' +  mapObject.x + ', ' + mapObject.y + ') ' +
        'rotate(' + mapObject.angle + ')'">

        <!-- разьединитель -->
        <polyline
            points="7 7, 10 7, 13 5"
            :stroke="myStroke"
            :stroke-width="myStrokeWidth"
            :fill="myFill"
        ></polyline>
        <polyline
            points="13 7, 16 7"
            :stroke="myStroke"
            :stroke-width="myStrokeWidth"
            :fill="myFill"
        ></polyline>

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
            myFill: "white",
            myStrokeWidth: "1",
        };
    },
    mounted() {
        console.log(this.mapObject);
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
};
</script>

<style scoped></style>
