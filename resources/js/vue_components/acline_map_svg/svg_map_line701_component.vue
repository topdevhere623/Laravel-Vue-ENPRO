<template>
    <g>

        <!-- разьединитель, реклоузер, ОПН если есть -->

        <!-- положение родителя -->
        <g :transform="
        'translate(' +  myTranslateX1 + ', ' + myTranslateY1 + ') ' +
        'rotate(' + myAngle + ')'">

            <!-- рисовать с ключами, если длина позволяет или просто линию, если короткая дистанция -->

            <g v-if="myHypotenuse <= 40">
                <rect
                    x="0"
                    y="0"
                    :width="myHypotenuse"
                    height="1"
                    :fill="myFill"
                ></rect>
            </g>

            <g v-else>

                <!-- от опоры start -->
                <rect
                    x="0"
                    y="0"
                    width="10"
                    height="1"
                    :fill="myFill"
                ></rect>

                <!-- ключ на start-е-->
                <!-- ключ есть -->
                <g v-if="(myHypotenuse > 40 && (mapObject.eqDisconnectorStart > 0 || mapObject.eqReklouzerStart > 0 || mapObject.eqVNaStart > 0))">
                    <svg-map-disconnector-component
                        :get-x="10"
                        :get-rotate="0"
                        :object-map-id="mapObject.eqDisconnectorStart"
                        :map-objects="mapObjects"
                    ></svg-map-disconnector-component>
                </g>
                <!-- ключ нет - соединено -->
                <rect
                    v-else
                    x="10"
                    y="0"
                    width="10"
                    height="1"
                    :fill="myFill"
                ></rect>

                <!-- середина -->
                <rect v-if="(myHypotenuse > 40)"
                      x="20"
                      y="0"
                      :width="myOtrezok4"
                      height="1"
                      :fill="myFill"
                ></rect>

                <!-- ключ на end -->
                <!-- ключ есть -->
                <g v-if="(myHypotenuse > 40 && (mapObject.eqDisconnectorEnd > 0 || mapObject.eqReklouzerEnd > 0 || mapObject.eqVNaEnd > 0))">
                    <svg-map-disconnector-component
                        :get-x="myOtrezok2"
                        :get-rotate="180"
                        :object-map-id="mapObject.eqDisconnectorStart"
                        :map-objects="mapObjects"
                    ></svg-map-disconnector-component>
                </g>
                <!-- ключ нет - соединено -->
                <rect
                    v-else
                    :x="myOtrezok2"
                    y="0"
                    width="10"
                    height="1"
                    :fill="myFill"
                ></rect>
                <!-- от точки end -->
                <rect
                    :x="myOtrezok1"
                    y="0"
                    width="10"
                    height="1"
                    :fill="myFill"
                ></rect>

            </g>

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

        // угол для отступа
        myAngle: function () {
            if (typeof (this.mapObject) !== 'undefined') {
                let dx = Number(this.mapObject.x1) - Number(this.mapObject.x2);
                let dy = Number(this.mapObject.y1) - Number(this.mapObject.y2);
                let rad = Math.atan2(dy, dx);

                // возвращаемый параметр
                return rad * (180 / 3.14) - 180;
            } else {
                return 0;
            }
        },

        // длина линии - гипотенуза между двумя крайними точками
        myHypotenuse: function () {
            if (typeof (this.mapObject) !== 'undefined') {
                let dx = Math.abs(Number(this.mapObject.x2) - Number(this.mapObject.x1));
                let dy = Math.abs(Number(this.mapObject.y2) - Number(this.mapObject.y1));

                // возвращаемый параметр
                return Math.sqrt(Math.pow(dx, 2) + Math.pow(dy, 2));
            } else {
                return 0;
            }
        },

        // отрезки
        myOtrezok4: function () {
            if (this.myHypotenuse > 0) {
                return this.myHypotenuse - 10 - 10 - 10 - 10;
            } else {
                return 0;
            }
        },

        myOtrezok2: function () {
            if (this.myHypotenuse > 0) {
                return this.myHypotenuse - 10 - 10;
            } else {
                return 0;
            }
        },

        myOtrezok1: function () {
            if (this.myHypotenuse > 0) {
                return this.myHypotenuse - 10;
            } else {
                return 0;
            }
        },

        // положение родителя (а то в начале null)
        myTranslateX1: function () {
            if (this.mapObject.x1 !== null) {
                // возвращаемый параметр
                return this.mapObject.x1;
            } else {
                return 0;
            }
        },
        myTranslateY1: function () {
            if (this.mapObject.y1 !== null) {
                // возвращаемый параметр
                return this.mapObject.y1 - 0.5;
            } else {
                return 0;
            }
        },

        // цвет от активности
        myFill: function () {
            return ((this.mapObject.current) ? 'red' : 'black');
        },

        // цвет от активности
        myStroke: function () {
            return ((this.mapObject.current) ? 'red' : 'black');
        },

    },
    methods: {}
}
</script>

<style scoped>
</style>
