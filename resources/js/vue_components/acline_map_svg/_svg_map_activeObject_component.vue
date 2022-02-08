<template>
    <g v-if="(mapObject.mapType === 'placemark' || mapObject.mapType === 'text')">
        <rect
            class="kontur"
            :width="mapObject.width"
            :height="mapObject.height"
            :transform="'translate(' + (mapObject.x - mapObject.width/2) + ', ' + (mapObject.y - mapObject.height/2) + ')'"
        />

        <!-- 4-е угла по бокам -->
        <g :transform="'translate(' + (mapObject.x - mapObject.width/2) + ', ' + (mapObject.y - mapObject.height/2) + ')'">
            <rect
                class="ugolok"
                :width="tools.squaresize"
                :height="tools.squaresize"
                data-handleid="1"
                :x="0-tools.squaresize/2"
                :y="0-tools.squaresize/2"
            />
            <rect
                class="ugolok"
                :width="tools.squaresize"
                :height="tools.squaresize"
                data-handleid="3"
                :x="mapObject.width-tools.squaresize/2"
                :y="0-tools.squaresize/2"
            />
            <rect
                class="ugolok"
                :width="tools.squaresize"
                :height="tools.squaresize"
                data-handleid="7"
                :x="0-tools.squaresize/2"
                :y="mapObject.height-tools.squaresize/2"
            />
            <rect
                class="ugolok"
                :width="tools.squaresize"
                :height="tools.squaresize"
                data-handleid="9"
                :x="mapObject.width-tools.squaresize/2"
                :y="mapObject.height-tools.squaresize/2"
            />
        </g>
    </g>

    <!-- по квадратику в начале и в конце (только у воздушной, в кабельной свой редактор) -->
    <g v-else-if="(mapObject.type === '701')">
        <g :transform="'translate(' + (mapObject.x1 - tools.squaresize) + ', ' + (mapObject.y1 - tools.squaresize) + ')'">
            <rect
                class="ugolok"
                :width="tools.squaresize * 2"
                :height="tools.squaresize * 2"
                data-handleid="1"
            />
        </g>
        <g :transform="'translate(' + (mapObject.x2 - tools.squaresize) + ', ' + (mapObject.y2 - tools.squaresize) + ')'">
            <rect
                class="ugolok"
                :width="tools.squaresize * 2"
                :height="tools.squaresize * 2"
                data-handleid="1"
            />
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
            tools: { // 4-е угла выделения
                squaresize: 2,
            },
        }
    },
    mounted() {
    },
}
</script>

<style scoped>

.kontur {
    fill-opacity: 0;
    stroke: #222222;
    stroke-width: 1;
    stroke-linecap: round;
    stroke-dasharray: 2, 4;
}

.ugolok {
    fill: #FFFFFF;
    stroke: red;
    stroke-width: 0.5;
}
</style>
