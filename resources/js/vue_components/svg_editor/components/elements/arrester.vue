<template>
    <g :transform="`scale(${size})`">
        <rect x="0.43" y="16.43" height="48" width="24" fill="#171636"></rect>
        <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="12.43" y1="76.43" x2="12.43" y2="80.43" />
        <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="0.43" y1="16.43" x2="24.43" y2="16.43" />
        <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="24.43" y1="16.43" x2="24.43" y2="64.43" />
        <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="24.43" y1="64.43" x2="0.43" y2="64.43" />
        <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="0.43" y1="64.43" x2="0.43" y2="16.43" />
        <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="6.43" y1="28.43" x2="12.43" y2="36.43" />
        <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="12.43" y1="36.43" x2="18.43" y2="28.43" />

        <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="12.43" y1="44.43" x2="18.43" y2="52.43" />
        <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="6.43" y1="52.43" x2="12.43" y2="44.43" />
        <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="12.43" y1="36.43" x2="12.43" y2="0.43" />
        <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="12.43" y1="44.43" x2="12.43" y2="76.43" />
        <circle r="3" :fill="selected ? '#00DBFF' : color" cx="12.43" />
        <circle r="3" :fill="selected ? '#00DBFF' : color" cx="12.43" cy="80.43" />
    </g>
</template>

<script>
export default {
    name: 'arrester',
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
