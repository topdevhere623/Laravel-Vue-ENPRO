<template>
    <g>

        <!-- внешний большой анимированный круг-->
        <circle
            :cx="getCoord.x"
            :cy="getCoord.y"
            r="7"
            fill="none"
            stroke="red"
            stroke-width="1"
            stroke-dashoffset="314"
            stroke-dasharray="3.14, 3.14">
            <animate
                attributeName="stroke-dashoffset"
                values="314;0"
                fill="freeze"
                dur="6s"
                repeatCount="indefinite"/>
        </circle>

        <!-- внутренний маленький круг-->
        <circle
            :cx="getCoord.x"
            :cy="getCoord.y"
            r="4"
            fill="none"
            stroke="red"
            stroke-width="1"
        ></circle>

        <!-- прицел во внутреннем маленьком круге-->
        <line
            :x1="getCoord.x"
            :y1="getCoord.y - 6"
            :x2="getCoord.x"
            :y2="getCoord.y - 2"
            stroke="red">
        </line>
        <line
            :x1="getCoord.x - 6"
            :y1="getCoord.y"
            :x2="getCoord.x - 2"
            :y2="getCoord.y"
            stroke="red"
        ></line>
        <line
            :x1="getCoord.x"
            :y1="getCoord.y + 2"
            :x2="getCoord.x"
            :y2="getCoord.y + 6"
            stroke="red"
        ></line>
        <line
            :x1="getCoord.x + 2"
            :y1="getCoord.y"
            :x2="getCoord.x + 6"
            :y2="getCoord.y"
            stroke="red"
        ></line>

    </g>
</template>

<script>
export default {
    props: {
        getCoord: Object,
    },
}
</script>

<style scoped>

</style>
