<template>
    <g class="ad-Grid" v-html="grid"></g>
</template>

<script>
export default {
    name: 'grid-component',
    data() {
        return (
            {
                grid: null
            }
        )
    },
    mounted() {
        this.grid = this.createGrid();
    },
    props: [
        'SVGParams',
        'size'
    ],
    methods: {
        createGrid() {
            let gridData = '';

            // X lines
            const xCount = Math.floor(this.SVGParams.width / this.size);
            for (let i = 1; i <= xCount; ++i) {
                gridData += `<line x1="${i * this.size}" y1="0" x2="${i * this.size}" y2="${this.SVGParams.height}"></line>`
            }

            // Y lines
            const yCount = Math.floor(this.SVGParams.height / this.size);
            for (let i = 1; i <= yCount; ++i) {
                gridData += `<line x1="0" y1="${i * this.size}" x2="${this.SVGParams.width}" y2="${i * this.size}"></line>`
            }

            return gridData;
        },
    }
}

</script>
