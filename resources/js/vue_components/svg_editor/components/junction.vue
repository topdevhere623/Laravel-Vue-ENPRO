<template>
    <rect
        :x="itemData.coords[0].x"
        :y="itemData.coords[0].y"
        :ref="itemData.id"
        :stroke="selected ? '#00DBFF' : currentColor"
        stroke-width="1"
        stroke-linejoin="round"
        :width="itemData.inverted ? lineHeight : '1'"
        :height="itemData.inverted ? '1' : lineHeight"
        fill="none"
    >
    </rect>
</template>

<script>
import manipulate from '../../../mixins/manipulate.js';

export default {
    name: 'junction',
    mixins: [manipulate],
    data() {
        return {
            width: 0,
            height: 0,
        };
    },
    methods: {},
    props: ['itemData', 'selected', 'instance'],
    computed: {
        currentColor() {
            if (this.itemData.color) {
                return this.itemData.color;
            } else {
                return 'white';
            }
        },
        lineHeight() {
            return parseInt(this.itemData.lineHeight) ? parseInt(this.itemData.lineHeight) : 10;
        },
    },
    mounted() {
        this.width = this.$refs[this.itemData.id].getBBox().width;
        this.height = this.$refs[this.itemData.id].getBBox().height;
        this.instance.$on('move', (data) => {
            if (this.itemData.inverted) {
                data.y = 0;
            } else {
                data.x = 0;
            }
            this.$emit('moveElement', data);
        });
        this.instance.$on('move-end', (data) => {
            if (this.itemData.inverted) {
                data.y = 0;
            } else {
                data.x = 0;
            }
            this.$emit('saveTransform', data);
        });
    },
};
</script>

<style scoped>
/*#point-handle {*/
/*    display: none;*/
/*}*/
</style>
