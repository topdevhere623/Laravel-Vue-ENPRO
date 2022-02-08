<template>
    <g :transform="`scale(${size})`" id="линия_эл" data-name="линия эл">
        <g id="LINE">
            <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="68.43" y1="30.43" x2="72.43" y2="30.43" />
        </g>
        <g id="LINE-2" data-name="LINE">
            <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="66.51" y1="20.38" x2="80.43" y2="44.43" />
        </g>
        <g id="LINE-3" data-name="LINE">
            <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="60.43" y1="64.43" x2="80.43" y2="64.43" />
        </g>
        <g id="ARC">
            <path class="cls-1" :stroke="selected ? '#00DBFF' : color" d="M79,64.43a1.45,1.45,0,1,0,2.9,0" />
        </g>
        <g id="ARC-2" data-name="ARC">
            <path class="cls-1" :stroke="selected ? '#00DBFF' : color" d="M81.88,64.43a1.45,1.45,0,0,0-2.9,0" />
        </g>
        <g id="LINE-4" data-name="LINE">
            <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="58.43" y1="30.43" x2="64.43" y2="30.43" />
        </g>
        <g id="LINE-5" data-name="LINE">
            <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="50.43" y1="48.43" x2="50.43" y2="54.43" />
        </g>
        <g id="LINE-6" data-name="LINE">
            <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="50.43" y1="38.43" x2="50.43" y2="44.43" />
        </g>
        <g id="LINE-7" data-name="LINE">
            <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="50.43" y1="30.43" x2="54.43" y2="30.43" />
        </g>
        <g id="LINE-8" data-name="LINE">
            <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="50.43" y1="30.43" x2="50.43" y2="34.43" />
        </g>
        <g id="LINE-9" data-name="LINE">
            <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="0.43" y1="68.43" x2="0.43" y2="60.43" />
        </g>
        <g id="LINE-10" data-name="LINE">
            <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="4.43" y1="56.43" x2="4.43" y2="72.43" />
        </g>
        <g id="LINE-11" data-name="LINE">
            <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="8.43" y1="76.43" x2="8.43" y2="52.43" />
        </g>
        <g id="LINE-12" data-name="LINE">
            <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="12.43" y1="48.43" x2="12.43" y2="80.43" />
        </g>
        <g id="LINE-13" data-name="LINE">
            <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="36.43" y1="64.43" x2="12.43" y2="64.43" />
        </g>
        <g id="LINE-14" data-name="LINE">
            <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="60.47" y1="50.51" x2="36.43" y2="64.43" />
        </g>
        <g id="LINE-15" data-name="LINE">
            <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="60.43" y1="60.43" x2="60.43" y2="68.43" />
        </g>
        <g id="LINE-16" data-name="LINE">
            <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="84.43" y1="20.43" x2="76.43" y2="20.43" />
        </g>
        <g id="LINE-17" data-name="LINE">
            <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="80.43" y1="20.43" x2="80.43" y2="0.43" />
        </g>
        <g id="LINE-18" data-name="LINE">
            <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="80.43" y1="44.43" x2="80.43" y2="80.43" />
        </g>
        <g id="CIRCLE">
            <circle class="cls-1" :stroke="selected ? '#00DBFF' : color" cx="80.43" cy="23.23" r="2.8" />
        </g>
    </g>
</template>

<script>
export default {
    name: 'grounding_knife',
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
