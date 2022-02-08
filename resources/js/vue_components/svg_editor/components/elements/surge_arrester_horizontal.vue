<template>
  <g :transform="`scale(${size})`" id="линия_эл" data-name="линия эл">
    <line class="st4" :stroke="selected ? '#00DBFF' : color" x1="41.8" y1="0.2" x2="50" y2="0.2"/>
    <line class="st4" :stroke="selected ? '#00DBFF' : color" x1="41.8" y1="10.2" x2="41.8" y2="-9.8"/>
    <line class="st4" :stroke="selected ? '#00DBFF' : color" x1="37.7" y1="7.7" x2="37.7" y2="-7.3"/>
    <line class="st4" :stroke="selected ? '#00DBFF' : color" x1="33.6" y1="5.2" x2="33.6" y2="-4.8"/>
    <path class="st4" :stroke="selected ? '#00DBFF' : color" d="M51,11"/>
    <g id="LINE">
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="71.8" y1="-13.8" x2="65.8" y2="-13.8"/>
    </g>
    <g id="LINE-2">
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="99.8" y1="14.2" x2="71.8" y2="-13.8"/>
    </g>
    <g id="LINE-3">
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="101.8" y1="0.2" x2="125.8" y2="0.2"/>
    </g>
    <g id="LINE-4">
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="49.8" y1="0.2" x2="61.8" y2="0.2"/>
    </g>
    <g id="LINE-5">
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="101.8" y1="8.2" x2="61.8" y2="8.2"/>
    </g>
    <g id="LINE-6">
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="101.8" y1="-8.3" x2="101.8" y2="8.2"/>
    </g>
    <g id="LINE-7">
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="61.8" y1="-7.8" x2="101.8" y2="-7.8"/>
    </g>
    <g id="LINE-8">
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="61.8" y1="8.2" x2="61.8" y2="-7.8"/>
    </g>
    <g id="TEXT">
      <text transform="matrix(4.286264e-17 -0.7 1 6.123234e-17 65.8346 -21.801)" :fill="selected ? '#00DBFF' : color"
            class="st4 st2 st3">U
      </text>
    </g>
    <!--    <template v-if="switchedOn">-->
    <!--      <line class="st4" :stroke="selected ? '#00DBFF' : color" x1="41.8" y1="0.2" x2="50" y2="0.2"/>-->
    <!--      <line class="st4" :stroke="selected ? '#00DBFF' : color" x1="9.2" y1="0.2" x2="17.3" y2="0.2"/>-->
    <!--      <line class="st4" :stroke="selected ? '#00DBFF' : color" x1="9.2" y1="10.2" x2="9.2" y2="-9.8"/>-->
    <!--      <line class="st4" :stroke="selected ? '#00DBFF' : color" x1="5.1" y1="7.7" x2="5.1" y2="-7.3"/>-->
    <!--      <line class="st4" :stroke="selected ? '#00DBFF' : color" x1="1" y1="5.2" x2="1" y2="-4.8"/>-->
    <!--      <line class="st4" :stroke="selected ? '#00DBFF' : color" x1="17.3" y1="0.2" x2="9.2" y2="0.2"/>-->
    <!--      <line class="st4" :stroke="selected ? '#00DBFF' : color" x1="17.3" y1="5.2" x2="17.3" y2="-4.8"/>-->
    <!--      <line class="st4" :stroke="selected ? '#00DBFF' : color" x1="41.8" y1="5.2" x2="41.8" y2="-4.8"/>-->
    <!--      <line class="st4" :stroke="selected ? '#00DBFF' : color" x1="17.3" y1="0.2" x2="41.8" y2="0.2"/>-->
    <!--      <path class="st4" :stroke="selected ? '#00DBFF' : color" d="M51,11"/>-->
    <!--      <g id="LINE">-->
    <!--        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="71.8" y1="-13.8" x2="65.8" y2="-13.8"/>-->
    <!--      </g>-->
    <!--      <g id="LINE-2">-->
    <!--        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="99.8" y1="14.2" x2="71.8" y2="-13.8"/>-->
    <!--      </g>-->
    <!--      <g id="LINE-3">-->
    <!--        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="101.8" y1="0.2" x2="125.8" y2="0.2"/>-->
    <!--      </g>-->
    <!--      <g id="LINE-4">-->
    <!--        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="49.8" y1="0.2" x2="61.8" y2="0.2"/>-->
    <!--      </g>-->
    <!--      <g id="LINE-5">-->
    <!--        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="101.8" y1="8.2" x2="61.8" y2="8.2"/>-->
    <!--      </g>-->
    <!--      <g id="LINE-6">-->
    <!--        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="101.8" y1="-8.3" x2="101.8" y2="8.2"/>-->
    <!--      </g>-->
    <!--      <g id="LINE-7">-->
    <!--        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="61.8" y1="-7.8" x2="101.8" y2="-7.8"/>-->
    <!--      </g>-->
    <!--      <g id="LINE-8">-->
    <!--        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="61.8" y1="8.2" x2="61.8" y2="-7.8"/>-->
    <!--      </g>-->
    <!--      <g id="TEXT">-->
    <!--        <text transform="matrix(4.286264e-17 -0.7 1 6.123234e-17 65.8346 -21.801)" :fill="selected ? '#00DBFF' : color"-->
    <!--              class="st1 st2 st3">U-->
    <!--        </text>-->
    <!--      </g>-->
    <!--    </template>-->
    <!--    <template v-else>-->
    <!--      <line class="st4" :stroke="selected ? '#00DBFF' : color" x1="41.8" y1="0.2" x2="50" y2="0.2"/>-->
    <!--      <line class="st4" :stroke="selected ? '#00DBFF' : color" x1="9.2" y1="0.2" x2="17.3" y2="0.2"/>-->
    <!--      <line class="st4" :stroke="selected ? '#00DBFF' : color" x1="9.2" y1="10.2" x2="9.2" y2="-9.8"/>-->
    <!--      <line class="st4" :stroke="selected ? '#00DBFF' : color" x1="5.1" y1="7.7" x2="5.1" y2="-7.3"/>-->
    <!--      <line class="st4" :stroke="selected ? '#00DBFF' : color" x1="1" y1="5.2" x2="1" y2="-4.8"/>-->
    <!--      <line class="st4" :stroke="selected ? '#00DBFF' : color" x1="17.3" y1="0.2" x2="9.2" y2="0.2"/>-->
    <!--      <line class="st4" :stroke="selected ? '#00DBFF' : color" x1="17.3" y1="5.2" x2="17.3" y2="-4.8"/>-->
    <!--      <line class="st4" :stroke="selected ? '#00DBFF' : color" x1="41.8" y1="5.2" x2="41.8" y2="-4.8"/>-->
    <!--      <line class="st4" :stroke="selected ? '#00DBFF' : color" x1="29.6" y1="10.2" x2="29.6" y2="-9.8"/>-->
    <!--      <path class="st4" :stroke="selected ? '#00DBFF' : color" d="M51,11"/>-->
    <!--      <g id="LINE">-->
    <!--        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="71.8" y1="-13.8" x2="65.8" y2="-13.8"/>-->
    <!--      </g>-->
    <!--      <g id="LINE-2">-->
    <!--        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="99.8" y1="14.2" x2="71.8" y2="-13.8"/>-->
    <!--      </g>-->
    <!--      <g id="LINE-3">-->
    <!--        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="101.8" y1="0.2" x2="125.8" y2="0.2"/>-->
    <!--      </g>-->
    <!--      <g id="LINE-4">-->
    <!--        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="49.8" y1="0.2" x2="61.8" y2="0.2"/>-->
    <!--      </g>-->
    <!--      <g id="LINE-5">-->
    <!--        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="101.8" y1="8.2" x2="61.8" y2="8.2"/>-->
    <!--      </g>-->
    <!--      <g id="LINE-6">-->
    <!--        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="101.8" y1="-8.3" x2="101.8" y2="8.2"/>-->
    <!--      </g>-->
    <!--      <g id="LINE-7">-->
    <!--        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="61.8" y1="-7.8" x2="101.8" y2="-7.8"/>-->
    <!--      </g>-->
    <!--      <g id="LINE-8">-->
    <!--        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="61.8" y1="8.2" x2="61.8" y2="-7.8"/>-->
    <!--      </g>-->
    <!--      <g id="TEXT">-->
    <!--        <text transform="matrix(4.286264e-17 -0.7 1 6.123234e-17 65.8346 -21.801)" :fill="selected ? '#00DBFF' : color"-->
    <!--              class="st1 st2 st3">U-->
    <!--        </text>-->
    <!--      </g>-->
    <!--    </template>-->
    <circle r="3" :fill="selected ? '#00DBFF' : color" cx="129"/>
  </g>
</template>

<script>
export default {
  name: 'surge_arrester',
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

.st2 {
  font-family: 'ArialMT', sans-serif;
}

.st3 {
  font-size: 14px;
}

.st4 {
  fill: none;
  stroke-width: 3px;
  stroke-linecap: square;
  stroke-linejoin: bevel;
}
</style>
