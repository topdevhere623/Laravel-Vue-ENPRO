<template>
    <g :transform="`scale(${size})`" id="линия_эл" data-name="линия эл">
        <g id="LINE">
            <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="64.43" y1="23.16" x2="64.43" y2="0.43" />
        </g>
        <g id="LINE-2" data-name="LINE">
            <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="60.43" y1="11.16" x2="64.43" y2="23.16" />
        </g>
        <g id="LINE-3" data-name="LINE">
            <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="68.43" y1="11.16" x2="64.43" y2="23.16" />
        </g>
        <g id="LINE-4" data-name="LINE">
            <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="112.43" y1="32.16" x2="100.43" y2="32.16" />
        </g>
        <g id="LINE-5" data-name="LINE">
            <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="56.43" y1="32.16" x2="0.43" y2="32.16" />
        </g>
        <g id="LINE-6" data-name="LINE">
            <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="56.43" y1="28.16" x2="56.43" y2="36.16" />
        </g>
        <g id="LINE-7" data-name="LINE">
            <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="56.38" y1="18.24" x2="80.43" y2="32.16" />
        </g>
        <g id="LINE-8" data-name="LINE">
            <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="80.43" y1="32.16" x2="100.43" y2="32.16" />
        </g>
    </g>
</template>

<script>
export default {
    name: 'shortcircuiter_horizontal',
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
