<template>
    <g :transform="`scale(${size})`" id="линия_эл" data-name="линия эл">
        <rect width="3" height="43" fill="#171636" x="13" y="10"></rect>
        <g id="линия_эл">
            <g id="LINE">
                <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="28.4" y1="21.6" x2="28.4" y2="16" />
            </g>
            <g id="LINE-2">
                <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="0.4" y1="47.9" x2="28.4" y2="21.6" />
            </g>
            <g id="LINE-3">
                <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="14.4" y1="49.8" x2="14.4" y2="61" />
            </g>
            <g id="LINE-4">
                <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="14.4" y1="1" x2="14.4" y2="12.3" />
            </g>
            <g id="LINE-5">
                <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="6.4" y1="49.8" x2="6.4" y2="12.3" />
            </g>
            <g id="LINE-6">
                <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="22.4" y1="49.8" x2="6.4" y2="49.8" />
            </g>
            <g id="LINE-7">
                <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="22.4" y1="12.3" x2="22.4" y2="49.8" />
            </g>
            <g id="LINE-8">
                <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="6.4" y1="12.3" x2="22.4" y2="12.3" />
            </g>
            <g id="TEXT">
                <text :fill="selected ? '#00DBFF' : color" transform="matrix(0.7467 0 0 1 36.4309 16.0002)"
                      class="st1 st2 st3">U
                </text>
            </g>
        </g>
        <circle r="3" :fill="selected ? '#00DBFF' : color" cx="14.43" />
        <circle r="3" :fill="selected ? '#00DBFF' : color" cx="14.43" cy="60.5" />
    </g>
</template>

<script>
export default {
    name: 'surge_arrester',
    props: ['color', 'selected', 'size'],
};
</script>

<style scoped>
.st0 {
    fill: none;
    stroke-linecap: round;
    stroke-linejoin: round;
    stroke-width: 3px;
}

.st2 {
    font-size: 14.12px;
    font-family: MyriadPro-Regular,
    Myriad Pro, sans-serif;
}
</style>
