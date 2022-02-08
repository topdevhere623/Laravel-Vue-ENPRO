<template>
  <g :transform="`scale(${size})`" id="линия_эл" data-name="линия эл">
    <!--        <template v-if="connectBottom">-->
    <!--            <rect width="3" height="70" fill="#171636" x="19" y="-1"></rect>-->
    <!--            <g id="LINE">-->
    <!--                <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="48.5" y1="24.5" x2="48.5" y2="44.5" />-->
    <!--            </g>-->
    <!--            <g id="LINE-2">-->
    <!--                <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="48.5" y1="44.5" x2="56.5" y2="40.1" />-->
    <!--            </g>-->
    <!--            <g id="LINE-3">-->
    <!--                <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="56.5" y1="28.9" x2="48.5" y2="24.5" />-->
    <!--            </g>-->
    <!--            <g id="LINE-4">-->
    <!--                <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="20.5" y1="50.5" x2="20.5" y2="62.5" />-->
    <!--            </g>-->
    <!--            <g id="LINE-5">-->
    <!--                <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="30.5" y1="44.5" x2="20.5" y2="50.5" />-->
    <!--            </g>-->
    <!--            <g id="LINE-6">-->
    <!--                <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="20.5" y1="50.5" x2="10.5" y2="44.5" />-->
    <!--            </g>-->
    <!--            <g id="LINE-7">-->
    <!--                <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="20.5" y1="50.5" x2="28.5" y2="50.5" />-->
    <!--            </g>-->
    <!--            <g id="LINE-8">-->
    <!--                <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="20.5" y1="12.5" x2="10.5" y2="6.5" />-->
    <!--            </g>-->
    <!--            <g id="LINE-9">-->
    <!--                <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="30.5" y1="6.5" x2="20.5" y2="12.5" />-->
    <!--            </g>-->
    <!--            <g id="LINE-10">-->
    <!--                <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="20.5" y1="12.5" x2="20.5" y2="24.5" />-->
    <!--            </g>-->
    <!--            <g id="CIRCLE">-->
    <!--                <circle class="st0" :stroke="selected ? '#00DBFF' : color" cx="48.5" cy="34.5" r="20" />-->
    <!--            </g>-->
    <!--            <g id="CIRCLE-2">-->
    <!--                <circle class="st0" :stroke="selected ? '#00DBFF' : color" cx="20.5" cy="20.5" r="20" />-->
    <!--            </g>-->
    <!--            <g id="CIRCLE-3">-->
    <!--                <circle class="st0" :stroke="selected ? '#00DBFF' : color" cx="20.5" cy="48.5" r="20" />-->
    <!--            </g>-->
    <!--            <line class="st0" style="stroke-width: 3px;" :stroke="selected ? '#00DBFF' : color" x1="20.5" y1="68.7"-->
    <!--                  x2="20.5" y2="88.7" />-->
    <!--            <circle r="3" :fill="selected ? '#00DBFF' : color" cx="20.5" cy="88.7" />-->
    <!--        </template>-->
    <line style="stroke-width: 3px !important;" stroke="#171636" x1="20.8"
          y1="90" x2="20.8"
          y2="0"/>
    <circle r="3" :fill="selected ? '#00DBFF' : color" cx="20.8"/>
    <g id="LINE">
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="48.8" y1="44.5" x2="48.8" y2="64.5"/>
    </g>
    <g id="LINE-2">
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="48.8" y1="64.5" x2="56.8" y2="60.1"/>
    </g>
    <g id="LINE-3">
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="56.8" y1="48.9" x2="48.8" y2="44.5"/>
    </g>
    <g id="LINE-4">
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="20.8" y1="70.5" x2="20.8" y2="82.5"/>
    </g>
    <g id="LINE-5">
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="30.8" y1="64.5" x2="20.8" y2="70.5"/>
    </g>
    <g id="LINE-6">
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="20.8" y1="70.5" x2="10.8" y2="64.5"/>
    </g>
    <g id="LINE-7">
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="20.8" y1="70.5" x2="28.8" y2="70.5"/>
    </g>
    <g id="LINE-8">
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="20.8" y1="32.5" x2="10.8" y2="26.5"/>
    </g>
    <g id="LINE-9">
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="30.8" y1="26.5" x2="20.8" y2="32.5"/>
    </g>
    <g id="LINE-10">
      <line class="st0" :stroke="selected ? '#00DBFF' : color" x1="20.8" y1="32.5" x2="20.8" y2="44.5"/>
    </g>
    <g id="CIRCLE">
      <circle class="st0" :stroke="selected ? '#00DBFF' : color" cx="48.8" cy="54.5" r="20"/>
    </g>
    <g id="CIRCLE-2">
      <circle class="st0" :stroke="selected ? '#00DBFF' : color" cx="20.8" cy="40.5" r="20"/>
    </g>
    <g id="CIRCLE-3">
      <circle class="st0" :stroke="selected ? '#00DBFF' : color" cx="20.8" cy="68.5" r="20"/>
    </g>
    <line class="st0" style="stroke-width: 2px !important;" :stroke="selected ? '#00DBFF' : color" x1="20.8"
          y1="20" x2="20.8"
          y2="0"/>
  </g>
</template>

<script>
  export default {
    name: 'double_transformer',
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
</style>
