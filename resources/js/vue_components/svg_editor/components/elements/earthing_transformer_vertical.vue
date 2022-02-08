<template>
    <g :transform="`scale(${size})`" id="линия_эл" data-name="линия эл">
        <g id="LINE">
            <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="16.43" y1="0.43" x2="40.43" y2="0.43" />
        </g>
        <g id="LINE-2" data-name="LINE">
            <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="16.43" y1="28.43" x2="16.43" y2="0.43" />
        </g>
        <g id="LINE-3" data-name="LINE">
            <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="20.43" y1="52.43" x2="12.43" y2="52.43" />
        </g>
        <g id="LINE-4" data-name="LINE">
            <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="8.43" y1="48.43" x2="24.43" y2="48.43" />
        </g>
        <g id="LINE-5" data-name="LINE">
            <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="28.43" y1="44.43" x2="4.43" y2="44.43" />
        </g>
        <g id="LINE-6" data-name="LINE">
            <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="0.43" y1="40.43" x2="32.43" y2="40.43" />
        </g>
        <g id="LINE-7" data-name="LINE">
            <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="16.43" y1="28.43" x2="16.43" y2="40.43" />
        </g>
        <circle r="3" :fill="selected ? '#00DBFF' : color" cx="40.43" cy="0.43" />
    </g>
</template>

<script>
export default {
    name: 'earthing_transformer_down',
    props: ['color', 'selected', 'size'],
};
</script>

<style scoped>
.cls-1 {
    fill: none;
    stroke-linecap: round;
    stroke-linejoin: round;
    stroke-width: 3px;
}

</style>
