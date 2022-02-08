<template>
    <g :transform="`scale(${size})`" id="линия_эл">
        <circle class="st0" cx="57.6" cy="14.3" r="2.8" />
        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="36.4" y1="14.3" x2="0.4" y2="14.3" />
        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="60.5" y1="0.4" x2="36.4" y2="14.3" />
        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="60.4" y1="14.3" x2="80.4" y2="14.3" />
        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="60.4" y1="10.3" x2="60.4" y2="18.3" />
        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="20.4" y1="34.3" x2="12.4" y2="34.3" />
        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="30.4" y1="34.3" x2="16.4" y2="58.3" />
        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="16.4" y1="58.3" x2="16.4" y2="82.3" />
        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="32.4" y1="82.3" x2="0.4" y2="82.3" />
        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="4.4" y1="86.3" x2="28.4" y2="86.3" />
        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="24.4" y1="90.3" x2="8.4" y2="90.3" />
        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="12.4" y1="94.3" x2="20.4" y2="94.3" />
        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="50.4" y1="44.3" x2="46.4" y2="44.3" />
        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="50.4" y1="44.3" x2="50.4" y2="40.3" />
        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="42.4" y1="44.3" x2="36.4" y2="44.3" />
        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="32.4" y1="44.3" x2="26.4" y2="44.3" />
        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="50.4" y1="36.3" x2="50.4" y2="30.3" />
        <path class="st0" :stroke="selected ? '#00DBFF' : color"
              d="M16.4,15.8c-0.8,0-1.4-0.6-1.4-1.4c0-0.8,0.6-1.5,1.4-1.5" />
        <path class="st0" :stroke="selected ? '#00DBFF' : color"
              d="M16.4,12.9c0.8,0,1.5,0.6,1.5,1.4c0,0.8-0.6,1.5-1.5,1.5" />
        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="16.4" y1="34.3" x2="16.4" y2="14.3" />
        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="50.4" y1="26.3" x2="50.4" y2="20.3" />
        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="50.4" y1="16.3" x2="50.4" y2="10.3" />
        <circle r="3" :fill="selected ? '#00DBFF' : color" cx="80.4" cy="14.3" />
    </g>
</template>

<script>
export default {
    name: 'loadbreak_switch',
    props: ['color', 'selected', 'size'],
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
