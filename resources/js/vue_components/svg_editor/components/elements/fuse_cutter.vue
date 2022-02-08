<template>
  <g :transform="`scale(${size})`" fill="none">
    <template v-if="switchedOn">
      <g id="линия_эл">
        <g id="LINE">
          <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="0.4" y1="12.4" x2="16.4" y2="12.4"/>
        </g>
        <g id="LINE-2">
          <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="16.4" y1="12.4" x2="16.4" y2="52.4"/>
        </g>
        <g id="LINE-3">
          <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="16.4" y1="52.4" x2="0.4" y2="52.4"/>
        </g>
        <g id="LINE-4">
          <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="0.4" y1="52.4" x2="0.4" y2="12.4"/>
        </g>
        <g id="LINE-5">
          <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="8.4" y1="2.4" x2="8.4" y2="62.4"/>
        </g>
      </g>
      <circle r="3" :fill="selected ? '#00DBFF' : color" cx="8.43"/>
    </template>
    <template v-else>
      <g id="LINE-6">
        <line style="stroke-width: 3px" class="st0" stroke="#171636" x1="8.4" y1="2.4" x2="8.4" y2="60.4"/>
      </g>
      <g id="линия_эл"
         style="transform: rotate(-45deg);transform-origin: 0 bottom;transform-box: fill-box;">
        <g id="LINE">
          <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="0.4" y1="12.4" x2="16.4" y2="12.4"/>
        </g>
        <g id="LINE-2">
          <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="16.4" y1="12.4" x2="16.4" y2="52.4"/>
        </g>
        <g id="LINE-3">
          <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="16.4" y1="52.4" x2="0.4" y2="52.4"/>
        </g>
        <g id="LINE-4">
          <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="0.4" y1="52.4" x2="0.4" y2="12.4"/>
        </g>
        <g id="LINE-5">
          <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="8.4" y1="2.4" x2="8.4" y2="63.4"/>
        </g>
      </g>
    </template>
    <circle r="3" :fill="selected ? '#00DBFF' : color" cx="8.43" cy="60.4"/>
  </g>
</template>

<script>
export default {
  name: "fuse_cutter",
  props: ['color', 'selected', 'size', 'switchedOn'],
}
</script>

<style scoped>
.st0 {
  fill: none;
  stroke-width: 3px;
  stroke-linecap: round;
  stroke-linejoin: round;
}
</style>