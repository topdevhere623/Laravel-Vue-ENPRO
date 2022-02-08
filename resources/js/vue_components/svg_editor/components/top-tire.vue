<template>
  <g>
    <polygon
        id="tire"
        :class="itemData.interactable ? 'editable' : ''"
        :points="`${currentPosition[0].x},${currentPosition[0].y} ${currentPosition[1].x},${currentPosition[1].y}`"
        fill="none"
        :stroke="selected ? '#00DBFF' : '#465fb9'"
        stroke-width="6"/>
  </g>
</template>

<script>
import {mapGetters} from 'vuex';

export default {
  name: 'svg-tire',
  data() {
    return ({
      defaultWidth: 100,
      defaultGap: 60,
      startX: 100,
      startY: 260,
      connectorsGap: 50,
      defaultElementWidth: 100,

      x1: null,
      x2: null,
      y1: null,
      y2: null,
    });
  },
  watch: {
    itemData: {
      deep: true,
      handler(val) {
        if (val.coords.length > 0) {
          this.x1 = val.coords[0].x
          this.x2 = val.coords[1].x
        }
      }
    }
  },
  props: ['itemData', 'selected', 'instance'],
  computed: {
    ...mapGetters(['SVGParams', 'elementsList', 'manuallyCreating', 'findById']),
    currentPosition() {
      return [{x: this.x1 || 0, y: this.y1 || 0}, {x: this.x2 || 0, y: this.y2 || 0}];
    },
  },
  methods: {
    setCurrentPosition() {
      if (this.itemData.coords.length === 0) {
        const topTiresList = this.elementsList.filter(el => this.itemData.type === el.type);
        const idsList = topTiresList.map(el => el.id);
        const index = idsList.indexOf(this.itemData.id);

        this.x1 = this.startX + index * this.defaultWidth + index * this.defaultGap;
        this.y1 = this.startY;
        this.x2 = this.x1 + this.defaultWidth;
        this.y2 = this.startY;

        if (index > 0) {
          this.x1 = topTiresList[index - 1].coords[1].x + this.defaultWidth;
          this.x2 = this.x1 + this.defaultWidth;
        }

        const data = {
          id: this.itemData.id,
          field: 'coords',
          value: [{x: this.x1, y: this.y1}, {x: this.x2, y: this.y2}],
        };

        this.$store.dispatch('changeData', data);
      } else {
        this.x1 = this.itemData.coords[0].x
        this.y1 = this.itemData.coords[0].y
        this.x2 = this.itemData.coords[1].x
        this.y2 = this.itemData.coords[1].y
      }
    },
    onMovePoint({x, y, target, index}) {
      if (index > 0) {
        let tireWidth = (this.x2 + x) - this.x1

        if (tireWidth > 100) {
          this.x2 += x
          target.x.baseVal.value = this.x2;
          this.$store.dispatch('changeData', {
            id: this.itemData.id,
            field: 'coords',
            value: [
              {
                x: this.x1,
                y: this.y1,
              },
              {
                x: this.x2,
                y: this.y2,
              },
            ]
          })
          const i = this.elementsList.filter(el => el.type === this.itemData.type).map(el => el.id).indexOf(this.itemData.id)
          let tires = this.elementsList.filter(el => el.type === this.itemData.type)
          this.moveNextTires(x, tires.slice(i+1))
        }

      }
    },
    onMoveTire(id, x) {
      let element = this.findById(id)
      let coords = element.coords
      coords.map(coord => {
        coord.x += x
      })
      this.$store.dispatch('changeData', {
        id: id,
        field: 'coords',
        value: coords
      })
    },
    moveNextTires(x, tires) {
      let allTiresIds = tires.map(el => el.id)
      this.elementsList.map(el => {
        if (el.currentTireID && allTiresIds.includes(el.currentTireID)) {
          this.onMoveTire(el.id, x)
        }
      })
      let otherTiresIds = allTiresIds.filter(id => id !== this.itemData.id)
      otherTiresIds.map(id => {
        this.onMoveTire(id, x)
      })
    }
  },
  beforeDestroy() {
    this.itemData.connection.forEach(id => {
      this.$store.dispatch('deleteElement', id);
    });
  },
  mounted() {
    this.setCurrentPosition()
    this.instance.$on('move-point', this.onMovePoint);
    this.instance.$on('move', ({x, target}) => {
      this.$emit('moveElement', {x, y: 0, target});
      let tires = this.elementsList.filter(el => el.type === this.itemData.type)
      this.moveNextTires(x, tires)
    });
    this.instance.$on('move-end', ({x, target}) => {
      if (target.type === 'g') {
        this.$emit('saveTransform', {x, y: 0, target});
      }
    });
    let pointToBeHidden = document.querySelector('.point-handle[data-index="0"]')
    // console.log(pointToBeHidden)
    // console.log(this.$el.children)
  },
};
</script>
