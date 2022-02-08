<template>

    <g>
        <!-- основная линия -->
        <line
            :x1="mapObject.x1"
            :y1="mapObject.y1"
            :x2="mapObject.x2"
            :y2="mapObject.y2"
            :stroke="myStroke"
            :stroke-width="myStrokeWidth"
        ></line>

        <!-- треугольник на start -->
        <g :transform="`translate(${mapObject.x1}, ${mapObject.y1})`">
            <polyline
                :transform="
                    'translate(7 -3)' +
                        'rotate(' +
                        funGetAngle(
                            mapObject.x1,
                            mapObject.y1,
                            mapObject.x2,
                            mapObject.y2
                        ) +
                        ' -7 3' +
                        ')'
                "
                points="0,0  6,3  0,6  0,0"
                :stroke="myStroke"
                fill="transparent"
                stroke-width="1"
            ></polyline>
        </g>

        <!-- треугольник на end -->
        <g :transform="`translate(${mapObject.x2}, ${mapObject.y2})`">
            <polyline
                :transform="
                    'translate(7 -3)' +
                        'rotate(' +
                        funGetAngle(
                            mapObject.x2,
                            mapObject.y2,
                            mapObject.x1,
                            mapObject.y1
                        ) +
                        ' -7 3' +
                        ')'
                "
                points="0,0  6,3  0,6  0,0"
                :stroke="myStroke"
                fill="transparent"
                stroke-width="1"
            ></polyline>
        </g>

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
            myStrokeWidth: "1",

            // выделение по концам
            tools: {
                squaresize: 2,
            },
        };
    },
    mounted() {
    },
    computed: {
        // вычисляемые значения
        // цвет от активности
        myStroke: function () {
            return ((this.mapObject.current) ? 'red' : 'black');
        }
    },
    methods: {

        // угол для треугольников в начале и в конце линии
        funGetAngle(x1, y1, x2, y2) {
            x1 = Number(x1);
            x2 = Number(x2);
            y1 = Number(y1);
            y2 = Number(y2);
            let dx = x1 - x2;
            let dy = y1 - y2;
            let rad = Math.atan2(dy, dx);

            // возвращаемый параметр
            return rad * (180 / 3.14) - 180;
        },
    }
};
</script>

<style scoped></style>
