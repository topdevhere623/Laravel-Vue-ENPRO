<template>
  <g :transform="`scale(${size})`" id="Слой_2" data-name="Слой 2">
    <g id="линия_эл">
      <g id="ARC">
        <path class="st0" :stroke="selected ? '#00DBFF' : color" d="M8.4,13.9c-4.4,0-8,3.6-8,8s3.6,8,8,8"/>
      </g>
      <g id="ARC-2">
        <path class="st0" :stroke="selected ? '#00DBFF' : color"
              d="M8.4,29.9c-4.4,0-8,3.6-8,8c0,4.4,3.6,8,8,8"/>
      </g>
      <g id="LINE">
        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="8.4" y1="13.9" x2="16.4" y2="13.9"/>
      </g>
      <g id="LINE-2">
        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="8.4" y1="45.9" x2="16.4" y2="45.9"/>
      </g>
      <g id="LINE-3">
        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="8.4" y1="-0.1" x2="8.4" y2="59.9"/>
      </g>
      <circle r="3" :fill="selected ? '#00DBFF' : color" cx="8.4"/>
      <!--            <circle r="3" :fill="selected ? '#00DBFF' : color" cx="8.4" cy="59.9" />-->
    </g>
  </g>
</template>

<script>
  export default {
    name: 'transformer',
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
