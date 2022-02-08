<template>
    <g v-if="normalopen == ''">
        <rect x="24.6" y="40.1" fill="none" v-bind:stroke="stroke" stroke-miterlimit="10" width="5.1" height="14.4"/>
        <line fill="none" v-bind:stroke="stroke" stroke-miterlimit="10" x1="27.1" y1="34.5" x2="27.1" y2="78.1"/>
        <line fill="none" v-bind:stroke="stroke" stroke-miterlimit="10" x1="27.1" y1="18.7" x2="27.1" y2="24.3"/>
        <line fill="none" v-bind:stroke="stroke" stroke-miterlimit="10" x1="27.1" y1="25" x2="27.1" y2="33.5"/>
        <line fill="none" v-bind:stroke="stroke" stroke-miterlimit="10" x1="24.9" y1="34.9" x2="29.4" y2="34.9"/>
        <line fill="none" v-bind:stroke="stroke" stroke-miterlimit="10" x1="24.9" y1="23.9" x2="29.4" y2="23.9"/>
    </g>
    <g v-else>
        <rect x="18.6" y="40.1" fill="none" v-bind:stroke="stroke" stroke-miterlimit="10" width="5.1" height="14.4"/>
        <line fill="none" v-bind:stroke="stroke" stroke-miterlimit="10" x1="27.1" y1="57.2" x2="27.1" y2="78.1"/>
        <line fill="none" v-bind:stroke="stroke" stroke-miterlimit="10" x1="27.1" y1="37.1" x2="25.1" y2="40"/>
        <line fill="none" v-bind:stroke="stroke" stroke-miterlimit="10" x1="27.2" y1="37.1" x2="29.2" y2="40"/>
        <path fill="none" v-bind:stroke="stroke" stroke-miterlimit="10" d="M29.2,40.7"/>
        <line fill="none" v-bind:stroke="stroke" stroke-miterlimit="10" x1="27.1" y1="57.6" x2="25.1" y2="54.7"/>
        <line fill="none" v-bind:stroke="stroke" stroke-miterlimit="10" x1="27.2" y1="57.6" x2="29.2" y2="54.7"/>
        <path fill="none" v-bind:stroke="stroke" stroke-miterlimit="10" d="M29.2,54"/>
        <path fill="none" v-bind:stroke="stroke" stroke-miterlimit="10" d="M27.2,37.8"/>
        <line fill="none" v-bind:stroke="stroke" stroke-miterlimit="10" x1="21.1" y1="40" x2="21.1" y2="54"/>
        <line fill="none" v-bind:stroke="stroke" stroke-miterlimit="10" x1="27.1" y1="35.5" x2="27.1" y2="37.2"/>
        <line fill="none" v-bind:stroke="stroke" stroke-miterlimit="10" x1="27.1" y1="18.7" x2="27.1" y2="24.3"/>
        <line fill="none" v-bind:stroke="stroke" stroke-miterlimit="10" x1="22.6" y1="25.6" x2="26.8" y2="33.4"/>
        <line fill="none" v-bind:stroke="stroke" stroke-miterlimit="10" x1="24.9" y1="23.9" x2="29.4" y2="23.9"/>
        <circle fill="none" v-bind:stroke="stroke" stroke-width="0.5" stroke-miterlimit="10" cx="27.1" cy="34.5" r="1.1"/>
    </g>
</template>

<script>
export default {
name: "svg_disconnectorfuse-component",
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
