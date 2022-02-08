<template>
    <g>
        <g v-if="rotated == false">
            <line fill="none" v-bind:stroke="stroke" stroke-miterlimit="10" x1="26.9" y1="108.2" x2="26.9" y2="72.2"/>
            <line fill="none" v-bind:stroke="stroke" stroke-miterlimit="10" x1="26.9" y1="126" x2="26.9" y2="118.5"/>
            <rect x="22.1" y="108.1" fill="none" v-bind:stroke="stroke" stroke-miterlimit="10" width="9.4" height="10.4"/>
            <line fill="none" v-bind:stroke="stroke" stroke-miterlimit="10" x1="26.9" y1="110.3" x2="26.9" y2="116.1" v-if="normalopen == ''"/>
            <line fill="none" v-bind:stroke="stroke" stroke-miterlimit="10" x1="29.8" y1="113.2" x2="24" y2="113.2" v-else />
        </g>
        <g v-else transform="translate(0, 5)">
            <line fill="none" v-bind:stroke="stroke" stroke-miterlimit="10" x1="27.1" y1="36.1" x2="27.1" y2="78"/>
            <line fill="none" v-bind:stroke="stroke" stroke-miterlimit="10" x1="27.1" y1="15.2" x2="27.1" y2="25.7"/>
            <rect x="22.4" y="25.7" transform="matrix(-1 -1.224647e-16 1.224647e-16 -1 54.26 61.8424)" fill="none" v-bind:stroke="stroke" stroke-miterlimit="10" width="9.4" height="10.4"/>
            <line fill="none" v-bind:stroke="stroke" stroke-miterlimit="10" x1="27.1" y1="33.9" x2="27.1" y2="28.1" v-if="normalopen == ''"/>
            <line fill="none" v-bind:stroke="stroke" stroke-miterlimit="10" x1="29.9" y1="31" x2="24.2" y2="31" v-else/>
        </g>
    </g>
</template>

<script>
export default {
name: "svg-loadbreakswitch-component",
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
