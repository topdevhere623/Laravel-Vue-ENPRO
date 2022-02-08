<template>
  <g id="Слой_1-2" :transform="`scale(${size})`">
    <rect data-v-ea959604="" width="2" height="18" fill="#171636" x="2.5" y="3"></rect>
    <template v-if="switchedOn">
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="3.4" y1="3.4" x2="3.4" y2="0.4"/>
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="3.4" y1="15.4" x2="3.4" y2="12.4"/>
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="6.4" y1="15.4" x2="0.4" y2="15.4"/>
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="5.7" y1="16.9" x2="1.2" y2="16.9"/>
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="4.9" y1="18.4" x2="1.9" y2="18.4"/>
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="3.4" y1="12.4" x2="3.4" y2="15.4"/>
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="4.9" y1="12.4" x2="1.9" y2="12.4"/>
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="4.9" y1="3.4" x2="1.9" y2="3.4"/>
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="3.4" y1="3.4" x2="3.4" y2="12.4"/>
      <path class="st0" :stroke="selected ? '#00DBFF' : color" d="M2.9,0.1"/>
      <circle r="1" :fill="selected ? '#00DBFF' : color" cx="3.4" cy="0"/>
    </template>
    <template v-else>
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="3.4" y1="3.4" x2="3.4" y2="0.4"/>
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="3.4" y1="15.4" x2="3.4" y2="12.4"/>
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="6.4" y1="15.4" x2="0.4" y2="15.4"/>
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="5.7" y1="16.9" x2="1.2" y2="16.9"/>
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="4.9" y1="18.4" x2="1.9" y2="18.4"/>
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="3.4" y1="12.4" x2="3.4" y2="15.4"/>
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="4.9" y1="12.4" x2="1.9" y2="12.4"/>
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="4.9" y1="3.4" x2="1.9" y2="3.4"/>
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="6.4" y1="7.9" x2="0.4" y2="7.9"/>
      <path class="st0" :stroke="selected ? '#00DBFF' : color" d="M2.9,0.1"/>
      <circle r="1" :fill="selected ? '#00DBFF' : color" cx="3.4" cy="0"/>
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
  stroke-width: 0.75;
  stroke-linecap: square;
  stroke-linejoin: bevel;
}
</style>
