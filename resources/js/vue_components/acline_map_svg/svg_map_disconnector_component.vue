<template>

    <!-- координату, и отразить или нет - дает линия -->
    <g :transform="'rotate(' + getRotate + ' ' + (getX + 5) + ' 0.5)'">

        <!-- разьединитель -->

        <circle
            r="1"
            :cx="getX"
            cy="0.5"
            :stroke="myStroke"
        ></circle>
        <line
            :x1="getX"
            y1="0.5"
            :x2="getX+3"
            y2="0.5"
            :stroke="myStroke"
            :stroke-width="myStrokeWidth"
        ></line>

        <!-- ключ -->
        <line
            :x1="getX+3"
            y1="0.5"
            :x2="getX+3+4"
            y2="-4"
            :stroke="myStroke"
            :stroke-width="myStrokeWidth"
        ></line>

        <line
            :x1="getX+3+4"
            y1="0.5"
            :x2="getX+10"
            y2="0.5"
            :stroke="myStroke"
            :stroke-width="myStrokeWidth"
        ></line>
        <line
            :x1="getX+3+4"
            y1="-1.5"
            :x2="getX+3+4"
            y2="2.5"
            :stroke="myStroke"
            :stroke-width="myStrokeWidth"
        ></line>
        <circle
            r="1"
            :cx="getX+10"
            cy="0.5"
            :stroke="myStroke"
        ></circle>

    </g>
</template>

<script>
export default {
    props: {
        getX: Number,
        getRotate: Number,
        objectMapID: Number,
        mapObjects: Array,
    },
    data() {
        return {
            // оформление обьекта по-умолчанию
            myFill: "white",
            myStrokeWidth: "1",
        };
    },
    mounted() {
    },
    computed: {
        // вычисляемые значения

        // текущий обьект
        mapObject: function (getObjectMapID = this.objectMapID) {
            return this.mapObjects.filter(function (item) {
                return (item.mapID === getObjectMapID);
            });
        },

        // цвет от активности
        myStroke: function () {
            return ((this.mapObject.current) ? 'red' : 'black');
        }
    },
    watch: {},
    methods: {}
};
</script>

<style scoped></style>
