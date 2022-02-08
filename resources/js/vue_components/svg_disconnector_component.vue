<template>
    <g>
        <g v-if="rotated == true" transform="translate(-0, 5)">
            <line fill="none" v-bind:stroke="stroke" stroke-miterlimit="10" x1="27" y1="34.1" x2="27" y2="77.7"/>
            <line fill="none" v-bind:stroke="stroke" stroke-miterlimit="10" x1="27" y1="18.3" x2="27" y2="23.9"/>
            <line fill="none" v-bind:stroke="stroke" stroke-miterlimit="10" x1="24.7" y1="23.5" x2="29.3" y2="23.5"/>
            <g v-if="normalopen == ''">
                <line fill="none" v-bind:stroke="stroke" stroke-miterlimit="10" x1="27" y1="24.6" x2="27" y2="33.1"/>
                <line fill="none" v-bind:stroke="stroke" stroke-miterlimit="10" x1="24.7" y1="34.5" x2="29.3" y2="34.5"/>
            </g>
            <g v-else>
                <line fill="none" v-bind:stroke="stroke" stroke-miterlimit="10" x1="22.3" y1="23.9" x2="26.6" y2="31.7"/>
                <circle fill="none" v-bind:stroke="stroke" stroke-width="0.5" stroke-miterlimit="10" cx="26.9" cy="32.8" r="1.1"/>
            </g>
        </g>
        <g v-else>
            <line display="inline" fill="none" v-bind:stroke="stroke" stroke-miterlimit="10" x1="27" y1="109.9" x2="27" y2="72.3"/>
            <line display="inline" fill="none" v-bind:stroke="stroke" stroke-miterlimit="10" x1="27" y1="125.7" x2="27" y2="120.1"/>
            <line display="inline" fill="none" v-bind:stroke="stroke" stroke-miterlimit="10" x1="24.7" y1="120.5" x2="29.3" y2="120.5"/>
            <g v-if="normalopen == ''">
                <line display="inline" fill="none" v-bind:stroke="stroke" stroke-miterlimit="10" x1="27" y1="119.4" x2="27" y2="110.9"/>
                <line display="inline" fill="none" v-bind:stroke="stroke" stroke-miterlimit="10" x1="24.7" y1="109.5" x2="29.3" y2="109.5"/>
            </g>
            <g v-else >
                <line fill="none" v-bind:stroke="stroke" stroke-miterlimit="10" x1="22.3" y1="119.8" x2="26.6" y2="112"/>
                <circle fill="none" v-bind:stroke="stroke" stroke-width="0.5" stroke-miterlimit="10" cx="26.9" cy="111" r="1.1"/>
            </g>
        </g>
    </g>
</template>

<script>
export default {
name: "svg-disconnector-component",
    props: {
        stroke: String,
        rotated: Boolean,
        normalopen: String,
        equipment: Object
    }
}
</script>

<style scoped>

</style>
