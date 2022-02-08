<template>
  <g :class="waitSelect.id ? 'lowlight' : ''">
    <g v-if="topLineCoords.length > 1 && !move">
      <line
          v-for="c in connectionLine"
          :x1="c.x1"
          :y1="c.y1"
          :x2="c.x2"
          :y2="c.y2"
          stroke="#465fb9" stroke-width="1"
          style="pointer-events: none"
          :stroke-dasharray="itemData.income.connection === 'tire' ? 0 : 4"
      />
      <line
          :x1="itemData.coords[0].x"
          :y1="itemData.coords[0].y"
          :x2="itemData.coords[0].x"
          :y2="itemData.coords[0].y -10"
          stroke-width="1" stroke="#465fb9"></line>
      <circle :cx="itemData.coords[0].x" :cy="itemData.coords[0].y -10" r="3" fill="#465fb9"/>
    </g>
    <g v-if="freeLineCoords.length > 1 && !move">
      <g v-if="itemData.free_connection.tire === 'top-tire'">
        <line
            v-for="c in connectionLineFree"
            :x1="c.x1"
            :y1="c.y1"
            :x2="c.x2"
            :y2="c.y2"
            stroke="#465fb9" stroke-width="1"
            style="pointer-events: none"
            :stroke-dasharray="itemData.free_connection.connection === 'tire' ? 0 : 4"
        />
        <line
            :x1="itemData.coords[0].x + 56"
            :y1="itemData.coords[0].y + 50"
            :x2="itemData.coords[0].x + 80"
            :y2="itemData.coords[0].y + 50"
            stroke-width="1" stroke="white"></line>
      </g>
      <g v-else>
        <line
            v-for="c in connectionLineFree"
            :x1="c.x1"
            :y1="c.y1"
            :x2="c.x2"
            :y2="c.y2"
            stroke="white" stroke-width="1"
            style="pointer-events: none"
            :stroke-dasharray="itemData.free_connection.connection === 'tire' ? 0 : 4"
        /><!--          x1:234, y1:490, x2:260, y2:490-->
        <line
            :x1="itemData.coords[0].x + 56"
            :y1="itemData.coords[0].y + 50"
            :x2="itemData.coords[0].x + 80"
            :y2="itemData.coords[0].y + 50"
            stroke-width="1" stroke="white"></line>
      </g>
      <circle :cx="itemData.coords[0].x + 80" :cy="itemData.coords[0].y + 50" r="3" fill="white"/>
    </g>
    <g v-if="bottomLineCoords.length > 1 && !move">
      <line
          v-for="c in connectionLineBottom"
          :x1="c.x1"
          :y1="c.y1"
          :x2="c.x2"
          :y2="c.y2"
          stroke="white" stroke-width="1"
          style="pointer-events: none"
          :stroke-dasharray="itemData.outcome.connection === 'tire' ? 0 : 4"
      />
      <line
          :x1="itemData.coords[0].x"
          :y1="itemData.coords[0].y + 100"
          :x2="itemData.coords[0].x"
          :y2="itemData.coords[0].y + 110"
          stroke-width="1" stroke="white"></line>
      <circle :cx="itemData.coords[0].x" :cy="itemData.coords[0].y + 110" r="3" fill="white"/>
    </g>
  </g>
</template>

<script>
import {mapGetters} from "vuex";

export default {
  name: 'transformer-connect',
  data() {
    return ({
      topLineCoords: [],
      freeLineCoords: [],
      bottomLineCoords: [],
      move: false,
      offsetTolerances: [3, -3, 7, -7, 10, -10]
    })
  },
  props: ['itemData'],
  methods: {
    refreshTopLineCoords() {
      this.topLineCoords = [];
      let topComponent = this.findById(this.itemData.income.id);

      if (topComponent) {
        const coordsStart = {
          x: topComponent.coords[0].x,
          y: topComponent.coords[0].y + topComponent.elementHeight,
        }
        this.topLineCoords.push(coordsStart);
        this.topLineCoords.push(this.itemData.coords[0]);
      } else {
        this.topLineCoords.splice(0, this.topLineCoords.length);
      }
    },
    refreshBottomLineCoords() {
      this.bottomLineCoords = [];
      let bottomComponent = this.findById(this.itemData.outcome.id);
      if (bottomComponent) {
        const coordsStart = {
          x: bottomComponent.coords[0].x,
          y: bottomComponent.coords[0].y,
        }
        this.bottomLineCoords.push(coordsStart);
        this.bottomLineCoords.push(this.itemData.coords[0]);
      } else {
        this.bottomLineCoords.splice(0, this.bottomLineCoords.length);
      }
    },
    refreshFreeLineCoords() {
      this.freeLineCoords = [];

      let freeComponent = this.itemData.free_connection ? this.findById(this.itemData.free_connection.id) : null;
      if (freeComponent) {
        let coordsStart = {
          x: freeComponent.coords[0].x,
          y: freeComponent.coords[0].y,
        }
        if (this.itemData.free_connection.tire === 'top-tire') {
          coordsStart = {
            x: freeComponent.coords[0].x,
            y: freeComponent.coords[0].y + freeComponent.elementHeight,
          }
        }
        this.freeLineCoords.push(coordsStart);
        this.freeLineCoords.push({
          x: this.itemData.coords[0].x + 80,
          y: this.itemData.coords[0].y + 50,
        });
      } else {
        this.freeLineCoords.splice(0, this.freeLineCoords.length);
      }
    }
  },
  watch: {
    //Top line
    incomeCoords: {
      deep: true,
      handler(val) {
        if (val) {
          this.refreshTopLineCoords();
        }
      }
    },
    incomeConnect(current, old) {
      if (old && old !== current && current) {
        const updateSelected = {id: old, field: 'reverse', value: false}
        this.$store.dispatch('changeData', updateSelected);
      }

      if (current) {
        this.refreshTopLineCoords();
      } else {
        this.topLineCoords = [];
      }
    },
    coords() {
      if (this.topLineCoords.length > 1) {
        this.topLineCoords.splice(1, 1);
        this.topLineCoords.push(this.itemData.coords[0]);
      }
      if (this.bottomLineCoords.length > 1) {
        this.bottomLineCoords.splice(1, 1);
        this.bottomLineCoords.push(this.itemData.coords[0]);
      }
      if (this.freeLineCoords.length > 1) {
        this.freeLineCoords.splice(1, 1);
        this.freeLineCoords.push({
          x: this.itemData.coords[0].x + 80,
          y: this.itemData.coords[0].y + 50,
        });
      }
    },
    //Bottom line
    outcomeCoords: {
      deep: true,
      handler(val) {
        if (val) {
          this.refreshBottomLineCoords();
        }
      }
    },
    outcomeConnect(current, old) {
      if (old && old !== current && current) {
        const updateSelected = {id: old, field: 'reverse', value: true}
        this.$store.dispatch('changeData', updateSelected);
      }

      if (current) {
        this.refreshBottomLineCoords();
      } else {
        this.bottomLineCoords = [];
      }
    },
    //Free Connection line
    freeCoords: {
      deep: true,
      handler(val) {
        if (val) {
          this.refreshFreeLineCoords();
        }
      }
    },
    freeConnect(current, old) {
      if (old && old !== current && current) {
        const updateSelected = {id: old, field: 'reverse', value: false}
        this.$store.dispatch('changeData', updateSelected);
      }

      if (current) {
        this.refreshFreeLineCoords();
      } else {
        this.freeLineCoords = [];
      }
    },
  },
  mounted() {
    this.refreshTopLineCoords();
    this.refreshBottomLineCoords();
    this.refreshFreeLineCoords();
  },
  computed: {
    ...mapGetters(['findById', 'elementsList', 'waitSelect']),
    transformers() {
      return this.elementsList.filter(el => el.type === 'transformer');
    },
    currentTransformerIndex() {
      let currentTransformerIndex = 0;
      this.transformers.forEach((el, i) => {
        if (el.id === this.itemData.id) {
          currentTransformerIndex = i;
        }
      });
      return currentTransformerIndex;
    },

    coords() {
      return this.itemData.coords;
    },

    //Top line
    incomeCoords() {
      if (this.itemData.income.id) {
        const topElement = this.elementsList.find(el => {
          return el.id === this.itemData.income.id
        })
        if (topElement) {
          return topElement.coords;
        }
        return null
      }
    },
    incomeConnect() {
      return this.itemData.income.id;
    },
    connectionLine() {
      const coords = [];
      const offset = this.offsetTolerances[this.currentTransformerIndex] ? this.offsetTolerances[this.currentTransformerIndex] : 0;
      if (this.topLineCoords.length === 2) {
        coords.push(this.topLineCoords[0]);
        coords.push({x: this.topLineCoords[0].x, y: this.topLineCoords[1].y - 20 + offset})
        coords.push({x: this.topLineCoords[1].x, y: this.topLineCoords[1].y - 20 + offset});
        coords.push(this.topLineCoords[1]);
      }

      return coords.map((c, i) => {
        if (i < coords.length - 1) {
          return {
            x1: c.x,
            y1: c.y,
            x2: coords[i + 1].x,
            y2: coords[i + 1].y,
          }
        }
      }).filter(el => !!el);
    },

    //BottomLine
    outcomeCoords() {
      if (this.itemData.outcome.id) {
        const bottomElement = this.elementsList.find(el => {
          return el.id === this.itemData.outcome.id
        })
        if (bottomElement) {
          return bottomElement.coords;
        }
        return null
      }
    },
    outcomeConnect() {
      return this.itemData.outcome.id;
    },
    connectionLineBottom() {
      const coords = [];
      const offset = this.offsetTolerances[this.currentTransformerIndex] ? this.offsetTolerances[this.currentTransformerIndex] : 0;
      if (this.bottomLineCoords.length === 2) {
        coords.push(this.bottomLineCoords[0]);
        coords.push({x: this.bottomLineCoords[0].x, y: this.bottomLineCoords[1].y + 120 + offset})
        coords.push({x: this.bottomLineCoords[1].x, y: this.bottomLineCoords[1].y + 120 + offset});
        coords.push({x: this.bottomLineCoords[1].x, y: this.bottomLineCoords[1].y + 100});
      }

      return coords.map((c, i) => {
        if (i < coords.length - 1) {
          return {
            x1: c.x,
            y1: c.y,
            x2: coords[i + 1].x,
            y2: coords[i + 1].y,
          }
        }
      }).filter(el => !!el);
    },

    //Free Connection line
    freeCoords() {
      if (this.itemData.free_connection?.id) {
        const freeElement = this.elementsList.find(el => {
          return el.id === this.itemData.free_connection.id
        })
        if (freeElement) {
          return freeElement.coords;
        }
        return null
      }
    },
    freeConnect() {
      return this.itemData.free_connection?.id;
    },
    connectionLineFree() {
      const coords = [];
      const offset = this.offsetTolerances[this.currentTransformerIndex] ? this.offsetTolerances[this.currentTransformerIndex] + 10 : 10;
      if (this.freeLineCoords.length === 2) {
        if (this.itemData.free_connection.tire === 'top-tire') {
          coords.push(this.freeLineCoords[0]);
          coords.push({x: this.freeLineCoords[0].x, y: this.freeLineCoords[1].y - 70 + offset})
          coords.push({x: this.freeLineCoords[1].x, y: this.freeLineCoords[1].y - 70 + offset});
          coords.push(this.freeLineCoords[1]);
        } else if (this.itemData.free_connection.tire === 'bottom-tire') {
          coords.push(this.freeLineCoords[0]);
          coords.push({x: this.freeLineCoords[0].x, y: this.freeLineCoords[1].y + 70 + offset})
          coords.push({x: this.freeLineCoords[1].x, y: this.freeLineCoords[1].y + 70 + offset});
          coords.push(this.freeLineCoords[1]);
        }
      }

      return coords.map((c, i) => {
        if (i < coords.length - 1) {
          return {
            x1: c.x,
            y1: c.y,
            x2: coords[i + 1].x,
            y2: coords[i + 1].y,
          }
        }
      }).filter(el => !!el);
    },
  }
}
</script>

<style scoped>
.lowlight {
  opacity: .2;
  pointer-events: none
}
</style>
