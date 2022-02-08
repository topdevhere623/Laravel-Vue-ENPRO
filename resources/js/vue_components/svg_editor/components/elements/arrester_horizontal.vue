<template>
  <g :transform="`scale(${size})`">
    <g id="LINE">
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="126.3" y1="0.2" x2="130.3" y2="0.2"/>
    </g>
    <g id="LINE-2">
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="66.3" y1="12.2" x2="66.3" y2="-11.8"/>
    </g>
    <g id="LINE-3">
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="66.3" y1="-11.8" x2="114.3" y2="-11.8"/>
    </g>
    <g id="LINE-4">
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="114.3" y1="-11.8" x2="114.3" y2="12.2"/>
    </g>
    <g id="LINE-5">
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="114.3" y1="12.2" x2="66.3" y2="12.2"/>
    </g>
    <g id="LINE-6">
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="78.3" y1="6.2" x2="86.3" y2="0.2"/>
    </g>
    <g id="LINE-7">
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="86.3" y1="0.2" x2="78.3" y2="-5.8"/>
    </g>
    <g id="LINE-8">
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="94.3" y1="0.2" x2="102.3" y2="-5.8"/>
    </g>
    <g id="LINE-9">
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="102.3" y1="6.2" x2="94.3" y2="0.2"/>
    </g>
    <g id="LINE-10">
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="86.3" y1="0.2" x2="50.3" y2="0.2"/>
    </g>
    <g id="LINE-11">
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="94.3" y1="0.2" x2="126.3" y2="0.2"/>
    </g>
    <line class="st1" :stroke="selected ? '#00DBFF' : color" x1="41.8" y1="0.2" x2="50" y2="0.2"/>
    <line class="st1" :stroke="selected ? '#00DBFF' : color" x1="41.8" y1="10.2" x2="41.8" y2="-9.8"/>
    <line class="st1" :stroke="selected ? '#00DBFF' : color" x1="37.7" y1="7.7" x2="37.7" y2="-7.3"/>
    <line class="st1" :stroke="selected ? '#00DBFF' : color" x1="33.6" y1="5.2" x2="33.6" y2="-4.8"/>
    <path class="st1" :stroke="selected ? '#00DBFF' : color" d="M51,11"/>
    <!--    <template v-if="switchedOn">-->
    <!--      <g id="LINE">-->
    <!--        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="126.3" y1="0.2" x2="130.3" y2="0.2"/>-->
    <!--      </g>-->
    <!--      <g id="LINE-2">-->
    <!--        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="66.3" y1="12.2" x2="66.3" y2="-11.8"/>-->
    <!--      </g>-->
    <!--      <g id="LINE-3">-->
    <!--        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="66.3" y1="-11.8" x2="114.3" y2="-11.8"/>-->
    <!--      </g>-->
    <!--      <g id="LINE-4">-->
    <!--        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="114.3" y1="-11.8" x2="114.3" y2="12.2"/>-->
    <!--      </g>-->
    <!--      <g id="LINE-5">-->
    <!--        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="114.3" y1="12.2" x2="66.3" y2="12.2"/>-->
    <!--      </g>-->
    <!--      <g id="LINE-6">-->
    <!--        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="78.3" y1="6.2" x2="86.3" y2="0.2"/>-->
    <!--      </g>-->
    <!--      <g id="LINE-7">-->
    <!--        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="86.3" y1="0.2" x2="78.3" y2="-5.8"/>-->
    <!--      </g>-->
    <!--      <g id="LINE-8">-->
    <!--        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="94.3" y1="0.2" x2="102.3" y2="-5.8"/>-->
    <!--      </g>-->
    <!--      <g id="LINE-9">-->
    <!--        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="102.3" y1="6.2" x2="94.3" y2="0.2"/>-->
    <!--      </g>-->
    <!--      <g id="LINE-10">-->
    <!--        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="86.3" y1="0.2" x2="50.3" y2="0.2"/>-->
    <!--      </g>-->
    <!--      <g id="LINE-11">-->
    <!--        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="94.3" y1="0.2" x2="126.3" y2="0.2"/>-->
    <!--      </g>-->
    <!--      <line class="st1" :stroke="selected ? '#00DBFF' : color" x1="41.8" y1="0.2" x2="50" y2="0.2"/>-->
    <!--      <line class="st1" :stroke="selected ? '#00DBFF' : color" x1="9.2" y1="0.2" x2="17.3" y2="0.2"/>-->
    <!--      <line class="st1" :stroke="selected ? '#00DBFF' : color" x1="9.2" y1="10.2" x2="9.2" y2="-9.8"/>-->
    <!--      <line class="st1" :stroke="selected ? '#00DBFF' : color" x1="5.1" y1="7.7" x2="5.1" y2="-7.3"/>-->
    <!--      <line class="st1" :stroke="selected ? '#00DBFF' : color" x1="1" y1="5.2" x2="1" y2="-4.8"/>-->
    <!--      <line class="st1" :stroke="selected ? '#00DBFF' : color" x1="17.3" y1="0.2" x2="9.2" y2="0.2"/>-->
    <!--      <line class="st1" :stroke="selected ? '#00DBFF' : color" x1="17.3" y1="5.2" x2="17.3" y2="-4.8"/>-->
    <!--      <line class="st1" :stroke="selected ? '#00DBFF' : color" x1="41.8" y1="5.2" x2="41.8" y2="-4.8"/>-->
    <!--      <line class="st1" :stroke="selected ? '#00DBFF' : color" x1="17.3" y1="0.2" x2="41.8" y2="0.2"/>-->
    <!--      <path class="st1" :stroke="selected ? '#00DBFF' : color" d="M51,11"/>-->
    <!--    </template>-->
    <!--    <template v-else>-->
    <!--      <g id="LINE">-->
    <!--        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="126.3" y1="0.2" x2="130.3" y2="0.2"/>-->
    <!--      </g>-->
    <!--      <g id="LINE-2">-->
    <!--        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="66.3" y1="12.2" x2="66.3" y2="-11.8"/>-->
    <!--      </g>-->
    <!--      <g id="LINE-3">-->
    <!--        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="66.3" y1="-11.8" x2="114.3" y2="-11.8"/>-->
    <!--      </g>-->
    <!--      <g id="LINE-4">-->
    <!--        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="114.3" y1="-11.8" x2="114.3" y2="12.2"/>-->
    <!--      </g>-->
    <!--      <g id="LINE-5">-->
    <!--        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="114.3" y1="12.2" x2="66.3" y2="12.2"/>-->
    <!--      </g>-->
    <!--      <g id="LINE-6">-->
    <!--        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="78.3" y1="6.2" x2="86.3" y2="0.2"/>-->
    <!--      </g>-->
    <!--      <g id="LINE-7">-->
    <!--        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="86.3" y1="0.2" x2="78.3" y2="-5.8"/>-->
    <!--      </g>-->
    <!--      <g id="LINE-8">-->
    <!--        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="94.3" y1="0.2" x2="102.3" y2="-5.8"/>-->
    <!--      </g>-->
    <!--      <g id="LINE-9">-->
    <!--        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="102.3" y1="6.2" x2="94.3" y2="0.2"/>-->
    <!--      </g>-->
    <!--      <g id="LINE-10">-->
    <!--        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="86.3" y1="0.2" x2="50.3" y2="0.2"/>-->
    <!--      </g>-->
    <!--      <g id="LINE-11">-->
    <!--        <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="94.3" y1="0.2" x2="126.3" y2="0.2"/>-->
    <!--      </g>-->
    <!--      <line class="st1" :stroke="selected ? '#00DBFF' : color" x1="41.8" y1="0.2" x2="50" y2="0.2"/>-->
    <!--      <line class="st1" :stroke="selected ? '#00DBFF' : color" x1="9.2" y1="0.2" x2="17.3" y2="0.2"/>-->
    <!--      <line class="st1" :stroke="selected ? '#00DBFF' : color" x1="9.2" y1="10.2" x2="9.2" y2="-9.8"/>-->
    <!--      <line class="st1" :stroke="selected ? '#00DBFF' : color" x1="5.1" y1="7.7" x2="5.1" y2="-7.3"/>-->
    <!--      <line class="st1" :stroke="selected ? '#00DBFF' : color" x1="1" y1="5.2" x2="1" y2="-4.8"/>-->
    <!--      <line class="st1" :stroke="selected ? '#00DBFF' : color" x1="17.3" y1="0.2" x2="9.2" y2="0.2"/>-->
    <!--      <line class="st1" :stroke="selected ? '#00DBFF' : color" x1="17.3" y1="5.2" x2="17.3" y2="-4.8"/>-->
    <!--      <line class="st1" :stroke="selected ? '#00DBFF' : color" x1="41.8" y1="5.2" x2="41.8" y2="-4.8"/>-->
    <!--      <line class="st1" :stroke="selected ? '#00DBFF' : color" x1="29.6" y1="10.2" x2="29.6" y2="-9.8"/>-->
    <!--      <path class="st1" :stroke="selected ? '#00DBFF' : color" d="M51,11"/>-->
    <!--    </template>-->
    <circle r="3" :fill="selected ? '#00DBFF' : color" cx="130.3" cy="0.2"/>
  </g>
</template>

<script>
export default {
  name: 'arrester',
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

.st1 {
  fill: none;
  stroke-width: 3px;
  stroke-linecap: square;
  stroke-linejoin: bevel;
}
</style>
