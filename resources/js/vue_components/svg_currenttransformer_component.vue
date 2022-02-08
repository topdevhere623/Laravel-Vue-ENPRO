<template>
    <g>
        <path fill="none"  v-bind:stroke="stroke" stroke-miterlimit="10" d="M31.5,88.2h-5.9c-1.5,0-2.7-1.1-2.7-2.3s2.2-2.3,3.7-2.3
		c-1.5,0-3.7-1.1-3.7-2.3c0-1.3,1.2-2.3,2.7-2.3h5.9"/>
    </g>
</template>

<script>
export default {
name: "svg-currenttransformer-component",
    props: {
        stroke: String,
        rotated: Boolean,
        equipment: Object
    }
}
</script>

<style scoped>

</style>
