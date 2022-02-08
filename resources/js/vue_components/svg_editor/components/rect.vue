<template>
    <polygon
        class="editable"
        :stroke="selected ? '#00DBFF' : 'white'"
        stroke-width="1"
        fill="none"
        :points="`${itemData.coords[0].x},${itemData.coords[0].y}
                ${itemData.coords[0].x + itemData.coords[1].x - itemData.coords[0].x},${itemData.coords[0].y}
                ${itemData.coords[0].x + itemData.coords[1].x - itemData.coords[0].x},${itemData.coords[0].y + itemData.coords[1].y - itemData.coords[0].y}
                ${itemData.coords[0].x},${itemData.coords[0].y + itemData.coords[1].y - itemData.coords[0].y}
                `"
    />
</template>

<script>
export default {
    name: 'svg-rect',
    data() {
        return ({});
    },
    watch: {},
    props: ['itemData', 'selected', 'instance'],
    computed: {},
    created() {

    },
    mounted() {
        this.instance.$on('move', (data) => {
            this.$emit('moveElement', data);
        });
        this.instance.$on('move-end', (data) => {
            this.$emit('saveTransform', data);
        });
    },
};
</script>
