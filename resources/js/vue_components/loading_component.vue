<template>
    <img v-if='getLoading'
         src='/public/uploads/loading.gif'
         class="loading"
    />
</template>

<script>
export default {
    props: {
        getLoading: Boolean,
    },
}
</script>

<style scoped>
.loading {
    position: fixed;
    margin: auto;
    width: 150px;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 999999;
}
</style>
