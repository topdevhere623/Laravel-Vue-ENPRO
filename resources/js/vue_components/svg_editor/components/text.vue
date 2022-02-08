<template>
    <g :transform="`translate(${itemData.coords[0].x} ${itemData.coords[0].y})`">
        <template v-if="itemData.type === 'text'">
                <text v-for="(textLine, index) in textRows"
                      :y="index * fontSize * 1.1"
                      :fill="selected ? '#00DBFF' : 'white'"
                      :font-size="fontSize"
                      :transform="orientation === 'vertical' ? 'rotate(-90)' : ''"
                      :text-anchor="textAlign"
                >{{textLine}}</text>
                <text v-if="!itemData.value" :fill="selected ? '#00DBFF' : 'white'" style="opacity: .5"
                      :font-size="fontSize"
                      :transform="orientation === 'vertical' ? 'rotate(-90)' : ''"
                      :text-anchor="textAlign"
                >
                    Введите текст
                </text>
        </template>
    </g>
</template>

<script>
export default {
    name: 'svg-text',
    data() {
        return ({

        })
    },
    watch: {

    },
    props: ['itemData', 'selected', 'instance'],
    computed: {
        textRows() {
             return this.itemData.value?.split(/\n/);
        },
        textAlign() {
            return this.itemData.textOptions?.alignment || ''
        },
        orientation() {
            return this.itemData.textOptions?.orientation || ''
        },
        fontSize() {
            return this.itemData.textOptions?.fontSize || '';
        }
    },
    created() {

    },
    mounted() {
        this.instance.$on('move', (data) => {
            this.$emit('moveElement', data);
        });
        this.instance.$on('move-end', (data) => {
            this.$emit('saveTransform', data);
        });
    }
}
</script>
