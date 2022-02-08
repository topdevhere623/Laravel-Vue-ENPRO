<template>
    <g :transform="`scale(${size})`" id="линия_эл" data-name="линия эл">
        <g id="LINE">
            <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="20.43" y1="18.34" x2="20.43" y2="10.34" />
        </g>
        <g id="LINE-2" data-name="LINE">
            <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="20.43" y1="14.34" x2="0.43" y2="14.34" />
        </g>
        <g id="LINE-3" data-name="LINE">
            <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="20.38" y1="0.43" x2="44.43" y2="14.34" />
        </g>
        <g id="LINE-4" data-name="LINE">
            <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="44.43" y1="14.34" x2="64.43" y2="14.34" />
        </g>
    </g>
</template>

<script>
export default {
    name: 'disconnector_horizontal',
    props: ['color', 'selected', 'size'],
};
</script>

<style scoped>
.cls-1 {
    fill: none;
    stroke-linecap: round;
    stroke-linejoin: round;
    stroke-width: 2px;
}
</style>
