<template>

    <!-- положение родителя -->
    <g :transform="
        'translate(' +  mapObject.x + ', ' + mapObject.y + ') ' +
        'rotate(' + mapObject.angle + ')'">

        <g :transform="'translate(15 -3)'">

            <!-- разрядник -->

            <!-- ножка -->
            <polyline
                :points="'0 3, 0 ' + (7 + mapObject.height - 3)"
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

            <!-- внутри корпуса -->
            <polyline
                :points="-mapObject.width/2 + ' ' + (7 + mapObject.height - 6) + ', ' + mapObject.width/2 + ' '+ (7 + mapObject.height - 6)"
                :stroke="myStroke"
                :stroke-width="myStrokeWidth"
                :fill="myFill"
            ></polyline>
            <polyline
                :points="-mapObject.width/2 + ' ' + (7 + mapObject.height - 3) + ', ' + mapObject.width/2 + ' '+ (7 + mapObject.height - 3)"
                :stroke="myStroke"
                :stroke-width="myStrokeWidth"
                :fill="myFill"
            ></polyline>

            <!-- ножка к заземлению -->
            <polyline
                :points="'0 ' + (7 + mapObject.height) + ', 0 ' + (7 + mapObject.height + 3)"
                :stroke="myStroke"
                :stroke-width="myStrokeWidth"
                :fill="myFill"
            ></polyline>

            <!-- полоски -->
            <polyline
                :points="'-4 '+ (7 + mapObject.height + 3) + ', 4 ' + (7 + mapObject.height + 3)"
                :stroke="myStroke"
                :stroke-width="myStrokeWidth"
                :fill="myFill"
            ></polyline>
            <polyline
                :points="'-2 '+ (7 + mapObject.height + 3 + 2) + ', 2 ' + (7 + mapObject.height + 3 + 2)"
                :stroke="myStroke"
                :stroke-width="myStrokeWidth"
                :fill="myFill"
            ></polyline>
            <polyline
                :points="'-1 '+ (7 + mapObject.height + 3 +4) + ', 1 ' + (7 + mapObject.height + 3 + 4)"
                :stroke="myStroke"
                :stroke-width="myStrokeWidth"
                :fill="myFill"
            >
            </polyline>


            <!-- всплывающий hint -->
            <title>
                {{ mapObject.hint }}
            </title>

        </g>
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
    },
    watch: {},
    methods: {}
};
</script>

<style scoped></style>
