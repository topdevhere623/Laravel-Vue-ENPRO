<template>
  <g id="Слой_1-2" :transform="`scale(${size})`">
    <template v-if="switchedOn">
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="15.4" y1="0" x2="18.4" y2="0"/>
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="3.4" y1="0" x2="6.4" y2="0"/>
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="3.4" y1="3" x2="3.4" y2="-3"/>
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="1.9" y1="2.2" x2="1.9" y2="-2.3"/>
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="0.4" y1="1.5" x2="0.4" y2="-1.5"/>
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="6.4" y1="0" x2="3.4" y2="0"/>
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="6.4" y1="1.5" x2="6.4" y2="-1.5"/>
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="15.4" y1="1.5" x2="15.4" y2="-1.5"/>
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="6.4" y1="0" x2="15.4" y2="0"/>
      <circle r="1" :fill="selected ? '#00DBFF' : color" cx="19" cy="0"/>
    </template>
    <template v-else>
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="15.4" y1="0" x2="18.4" y2="0"/>
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="3.4" y1="0" x2="6.4" y2="0"/>
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="3.4" y1="3" x2="3.4" y2="-3"/>
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="1.9" y1="2.2" x2="1.9" y2="-2.3"/>
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="0.4" y1="1.5" x2="0.4" y2="-1.5"/>
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="6.4" y1="0" x2="3.4" y2="0"/>
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="6.4" y1="1.5" x2="6.4" y2="-1.5"/>
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="15.4" y1="1.5" x2="15.4" y2="-1.5"/>
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="10.9" y1="3" x2="10.9" y2="-3"/>
      <circle r="1" :fill="selected ? '#00DBFF' : color" cx="19" cy="0"/>
    </template>
  </g>
</template>

<script>
  export default {
    name: 'grounding',
    props: ['color', 'selected', 'size', 'switchedOn'],
  };
</script>

<style scoped>
.st0 {
  fill: none;
  stroke-width: 1;
  stroke-linecap: square;
  stroke-linejoin: bevel;
}
</style>
