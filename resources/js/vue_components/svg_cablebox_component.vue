<template>
    <g>
        <polygon fill="#ffffff" v-if="rotated == true"  points="24.7,75.7 26.8,79.4 28.9,75.7" v-bind:stroke="stroke" />
        <polygon fill="#ffffff" v-else points="29,71.9 26.9,68.2 24.8,71.9" v-bind:stroke="stroke" />
    </g>
</template>

<script>
export default {
    name: "svg-cablebox-component",
    props: {
        stroke: String,
        rotated: Boolean,
        equipment: Object
    }
}
</script>

<style scoped>

</style>
