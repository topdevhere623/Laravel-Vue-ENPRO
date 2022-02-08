<template>
    <g :transform="`scale(${size})`" id="линия_эл">
        <rect width="1.5" height="6" fill="#171636" x="5.7" y="1.9"></rect>
        <g id="LINE">
            <line :stroke="selected ? '#00DBFF' : color" class="st0" x1="6.4" y1="8.9" x2="10.6" y2="1.9" />
        </g>
        <g id="LINE-2">
            <line :stroke="selected ? '#00DBFF' : color" class="st0" x1="2.2" y1="1.9" x2="10.6" y2="1.9" />
        </g>
        <g id="LINE-3">
            <line :stroke="selected ? '#00DBFF' : color" class="st0" x1="2.2" y1="1.9" x2="6.4" y2="8.9" />
        </g>
    </g>
</template>

<script>
export default {
    name: 'cable_line',
    props: ['color', 'selected', 'size'],
};
</script>

<style scoped>
.st0 {
    fill: none;
    stroke-width: 1px;
    stroke-linecap: round;
    stroke-linejoin: round;
}
</style>
