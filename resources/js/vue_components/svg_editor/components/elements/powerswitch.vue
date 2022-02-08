<template>
  <g :transform="`scale(${size})`" id="линия_эл" data-name="линия эл">
    <rect width="3" height="23" fill="#171636" x="13" y="20.84"></rect>
    <template v-if="switchedOn">
      <g id="LINE">
        <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="10.34" y1="24.43" x2="18.34"
              y2="16.43"/>
      </g>
      <g id="LINE-2" data-name="LINE">
        <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="18.34" y1="24.43" x2="10.34"
              y2="16.43"/>
      </g>
      <g id="LINE-3" data-name="LINE">
        <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="14.34" y1="44.43" x2="14.34"
              y2="60.43"/>
      </g>
      <g id="LINE-4" data-name="LINE">
        <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="14.34" y1="20.38" x2="14.34"
              y2="44.43"/>
      </g>
      <g id="LINE-5" data-name="LINE">
        <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="14.34" y1="20.43" x2="14.34" y2="0.43"/>
      </g>
    </template>
    <template v-else>
      <g id="LINE">
        <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="10.34" y1="24.43" x2="18.34"
              y2="16.43"/>
      </g>
      <g id="LINE-2" data-name="LINE">
        <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="18.34" y1="24.43" x2="10.34"
              y2="16.43"/>
      </g>
      <g id="LINE-3" data-name="LINE">
        <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="14.34" y1="44.43" x2="14.34"
              y2="60.43"/>
      </g>
      <g id="LINE-4" data-name="LINE">
        <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="0.43" y1="20.38" x2="14.34" y2="44.43"/>
      </g>
      <g id="LINE-5" data-name="LINE">
        <line class="cls-1" :stroke="selected ? '#00DBFF' : color" x1="14.34" y1="20.43" x2="14.34" y2="0.43"/>
      </g>
    </template>

    <circle r="3" :fill="selected ? '#00DBFF' : color" cx="14.34"/>
    <circle r="3" :fill="selected ? '#00DBFF' : color" cx="14.34" cy="60.43"/>
  </g>
</template>

<script>
  export default {
    name: 'power_switch',
    props: ['color', 'selected', 'size', 'switchedOn'],
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
