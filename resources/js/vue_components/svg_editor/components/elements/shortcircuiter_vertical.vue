<template>
    <g :transform="`scale(${size})`" id="линия_эл" data-name="линия эл">
        <rect width="3" height="25" fill="#171636" x="28.3" y="20.6"></rect>
        <template v-if="switchedOn">
            <g id="линия_эл">
                <g id="LINE">
                    <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="29.3" y1="44.2" x2="29.3" y2="59.6" />
                </g>
                <g id="LINE-2">
                    <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="16.6" y1="22.3" x2="29.3" y2="44.2" />
                </g>
                <g id="LINE-3">
                    <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="25.3" y1="20.2" x2="33.3" y2="20.2" />
                </g>
                <g id="LINE-4">
                    <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="29.3" y1="20.2" x2="29.3" y2="0.2" />
                </g>
                <g id="LINE-6">
                    <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="8.3" y1="32.2" x2="20.3" y2="28.2" />
                </g>
                <g id="LINE-7">
                    <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="10.2" y1="24.9" x2="20.3" y2="28.2" />
                </g>
                <g id="LINE-8">
                    <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="20.3" y1="28.2" x2="0.2" y2="28.2" />
                </g>
            </g>
        </template>
        <template v-else>
            <g id="линия_эл">
                <g id="LINE">
                    <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="29.3" y1="44.6" x2="29.3" y2="60" />
                </g>
                <g id="LINE-2">
                    <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="29.3" y1="20.6" x2="29.3" y2="44.6" />
                </g>
                <g id="LINE-3">
                    <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="25.3" y1="20.6" x2="33.3" y2="20.6" />
                </g>
                <g id="LINE-4">
                    <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="29.3" y1="20.6" x2="29.3" y2="0.6" />
                </g>
                <g id="LINE-6">
                    <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="17.3" y1="32.3" x2="29.3" y2="28.3" />
                </g>
                <g id="LINE-7">
                    <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="19.2" y1="24.9" x2="29.3" y2="28.3" />
                </g>
                <g id="LINE-8">
                    <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="29.3" y1="28.3" x2="9.2" y2="28.3" />
                </g>
            </g>
        </template>
        <circle r="3" :fill="selected ? '#00DBFF' : color" cx="29.3" cy="0.6" />
        <circle r="3" :fill="selected ? '#00DBFF' : color" cx="29.3" cy="60" />
    </g>
</template>

<script>
export default {
    name: 'shortcircuiter_vertical',
    props: ['color', 'selected', 'size', 'switchedOn'],
};
</script>

<style scoped>
.st0 {
    fill: none;
    stroke-width: 3px;
    stroke-linecap: round;
    stroke-linejoin: round;
}
</style>
