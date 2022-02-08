<template>
  <g :transform="`scale(${size})`">
    <rect width="3" height="26" fill="#171636" x="13" y="23"></rect>
    <template v-if="switchedOn">
      <g id="LINE">
        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="10.5" y1="22.4" x2="18.5" y2="22.4"/>
      </g>
      <g id="LINE-2">
        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="14.5" y1="22.4" x2="14.5" y2="0.5"/>
      </g>
      <g id="LINE-3">
        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="14.5" y1="18.8" x2="14.5" y2="48.8"/>
      </g>
      <g id="LINE-4">
        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="14.5" y1="48.8" x2="14.5" y2="70.7"/>
      </g>
      <g id="LINE_1_">
        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="6.5" y1="84.1" x2="22.5" y2="84.1"/>
      </g>
      <g id="LINE-2_1_">
        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="22.5" y1="84.1" x2="22.5" y2="128"/>
      </g>
      <g id="LINE-3_1_">
        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="22.5" y1="128" x2="6.5" y2="128"/>
      </g>
      <g id="LINE-4_1_">
        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="6.5" y1="128" x2="6.5" y2="84.1"/>
      </g>
      <g id="LINE-5">
        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="14.5" y1="70.2" x2="14.5" y2="140.5"/>
      </g>
    </template>
    <template v-else>
      <g id="LINE">
        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="10.5" y1="22.4" x2="18.5" y2="22.4"/>
      </g>
      <g id="LINE-2">
        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="14.5" y1="22.4" x2="14.5" y2="0.5"/>
      </g>
      <g id="LINE-3">
        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="0.5" y1="22.4" x2="14.5" y2="48.8"/>
      </g>
      <g id="LINE-4">
        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="14.5" y1="48.8" x2="14.5" y2="70.7"/>
      </g>
      <g id="LINE_1_">
        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="6.5" y1="84.1" x2="22.5" y2="84.1"/>
      </g>
      <g id="LINE-2_1_">
        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="22.5" y1="84.1" x2="22.5" y2="128"/>
      </g>
      <g id="LINE-3_1_">
        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="22.5" y1="128" x2="6.5" y2="128"/>
      </g>
      <g id="LINE-4_1_">
        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="6.5" y1="128" x2="6.5" y2="84.1"/>
      </g>
      <g id="LINE-5">
        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="14.5" y1="70.2" x2="14.5" y2="140.5"/>
      </g>
    </template>
    <circle r="3" :fill="selected ? '#00DBFF' : color" cx="14.3"/>
    <circle r="3" :fill="selected ? '#00DBFF' : color" cx="14.3" cy="140"/>
  </g>
</template>

<script>
  export default {
    name: 'fuse_disconnector',
    props: ['color', 'selected', 'size', 'switchedOn'],
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
