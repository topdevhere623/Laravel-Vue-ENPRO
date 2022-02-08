<template>
  <g
    :transform="`matrix(1 0 0 1 ${itemData.coords[0].x} ${itemData.coords[0].y})`"
    :style="move ? 'opacity: .2' : ''"
  >
    <rect width="70" height="70" x="0" y=20 fill="transparent"></rect>
    <g id="LINE">
      <line class="st0" :stroke="selected ? '#00DBFF' : 'white'" x1="48.8" y1="44.5" x2="48.8" y2="64.5" />
    </g>
    <g id="LINE-2">
      <line class="st0" :stroke="selected ? '#00DBFF' : 'white'" x1="48.8" y1="64.5" x2="56.8" y2="60.1" />
    </g>
    <g id="LINE-3">
      <line class="st0" :stroke="selected ? '#00DBFF' : 'white'" x1="56.8" y1="48.9" x2="48.8" y2="44.5" />
    </g>
    <g id="LINE-4">
      <line class="st0" :stroke="selected ? '#00DBFF' : 'white'" x1="20.8" y1="70.5" x2="20.8" y2="82.5" />
    </g>
    <g id="LINE-5">
      <line class="st0" :stroke="selected ? '#00DBFF' : 'white'" x1="30.8" y1="64.5" x2="20.8" y2="70.5" />
    </g>
    <g id="LINE-6">
      <line class="st0" :stroke="selected ? '#00DBFF' : 'white'" x1="20.8" y1="70.5" x2="10.8" y2="64.5" />
    </g>
    <g id="LINE-7">
      <line class="st0" :stroke="selected ? '#00DBFF' : 'white'" x1="20.8" y1="70.5" x2="28.8" y2="70.5" />
    </g>
    <g id="LINE-8">
      <line class="st0" :stroke="selected ? '#00DBFF' : 'white'" x1="20.8" y1="32.5" x2="10.8" y2="26.5" />
    </g>
    <g id="LINE-9">
      <line class="st0" :stroke="selected ? '#00DBFF' : 'white'" x1="30.8" y1="26.5" x2="20.8" y2="32.5" />
    </g>
    <g id="LINE-10">
      <line class="st0" :stroke="selected ? '#00DBFF' : 'white'" x1="20.8" y1="32.5" x2="20.8" y2="44.5" />
    </g>
    <g id="CIRCLE">
      <circle class="st0" :stroke="selected ? '#00DBFF' : 'white'" cx="48.8" cy="54.5" r="20" />
    </g>
    <g id="CIRCLE-2">
      <circle class="st0" :stroke="selected ? '#00DBFF' : 'white'" cx="20.8" cy="40.5" r="20" />
    </g>
    <g id="CIRCLE-3">
      <circle class="st0" :stroke="selected ? '#00DBFF' : 'white'" cx="20.8" cy="68.5" r="20" />
    </g>
  </g>
</template>

<script>
  import {mapGetters} from "vuex";

  export default {
    name: "three_phase_transformer",
    props: ['itemData', 'selected', 'instance'],
    data() {
      return {
        move: false
      }
    },
    computed: {
      ...mapGetters(['elementsList', 'selectedElementData', 'manuallyCreating']),
    },
    created() {
      const data = {
        id: this.itemData.id, field: 'coords',
        value: this.itemData.coords.map(e => {
          return {x: e.x, y: e.y}
        })
      };
      this.$store.dispatch('changeData', data);
    },
    mounted() {
      this.instance.$on('move', (data) => {
        this.$emit('moveElement', data);
      });
      this.instance.$on('move-end', (data) => {
        this.$emit('saveTransform', data);
        this.move = false
      });
      this.instance.$on('move-start', (data) => {
       this.move = true
      });
    },
  }
</script>

<style scoped>
.st0 {
  fill: none;
  stroke-linecap: round;
  stroke-linejoin: round;
  stroke-width: 3px;
}
</style>