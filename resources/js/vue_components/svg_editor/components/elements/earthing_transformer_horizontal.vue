<template>
    <g :transform="`scale(${size})`" id="линия_эл" data-name="линия эл">
        <g id="LINE">
            <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="24.43" y1="24.43" x2="12.43" y2="24.43" />
        </g>
        <g id="LINE-2" data-name="LINE">
            <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="12.43" y1="40.43" x2="12.43" y2="8.43" />
        </g>
        <g id="LINE-3" data-name="LINE">
            <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="8.43" y1="12.43" x2="8.43" y2="36.43" />
        </g>
        <g id="LINE-4" data-name="LINE">
            <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="4.43" y1="32.43" x2="4.43" y2="16.43" />
        </g>
        <g id="LINE-5" data-name="LINE">
            <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="0.43" y1="20.43" x2="0.43" y2="28.43" />
        </g>
        <g id="LINE-6" data-name="LINE">
            <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="24.43" y1="24.43" x2="52.43" y2="24.43" />
        </g>
        <g id="LINE-7" data-name="LINE">
            <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="52.43" y1="24.43" x2="52.43" y2="0.43" />
        </g>
    </g>
</template>

<script>
export default {
    name: 'earthing_transformer',
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
