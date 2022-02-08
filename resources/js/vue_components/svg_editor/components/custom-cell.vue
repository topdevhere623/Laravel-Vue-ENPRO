<template>
  <g>
    <g
        v-if="itemData.hasOwnProperty('templateData')"
        fill="none"
        :transform="`matrix(1 0 0 1 ${itemData.coords[0].x} ${itemData.coords[0].y})`"
        @dblclick="editTemplate"
    >
      <g
          :ref="itemData.id"
          :transform="!itemData.reverse && itemData.templateData.access !== 'between-tire' && 'scale(1, -1)'"
          :style="move && !moveAdmittance ? `opacity: .2; transform-origin: 0px ${lineHeight.vertical/2}px` : ` transform-origin: 0px ${lineHeight.vertical/2}px`"
      >
        <template v-for="item in itemData.templateData.elements">
          <polygon
              v-if="item.type === 'rect'"
              stroke-width="1"
              :points="`${item.coords[0].x},${item.coords[0].y}
                ${item.coords[0].x + item.coords[1].x - item.coords[0].x},${item.coords[0].y}
                ${item.coords[0].x + item.coords[1].x - item.coords[0].x},${item.coords[0].y + item.coords[1].y - item.coords[0].y}
                ${item.coords[0].x},${item.coords[0].y + item.coords[1].y - item.coords[0].y}
                `"
              :stroke="selected ? '#00DBFF' : 'white'"
          >
          </polygon>
          <polygon
              v-if="item.type === 'line'"
              :stroke="currentColorC"
              stroke-width="1"
              :points="`${item.coords[0].x},${item.coords[0].y}
                        ${item.coords[1].x},${item.coords[1].y}`"
          />
          <rect
              v-if="item.type === 'junction'"
              :x="item.coords[0].x"
              :y="item.coords[0].y"
              :ref="item.id"
              :stroke="currentColorC"
              stroke-width="2"
              stroke-linejoin="round"
              :width="item.inverted ? item.lineHeight : '1'"
              :height="item.inverted ? '1' : item.lineHeight"
              fill="none"
          >
          </rect>
          <svg-element v-if="item.type === 'element' && item.hidden === false" :selected="selected" :instance="instance"
                       :item-data="item"/>
          <g v-if="item.coords && item.coords.length"
             :transform="`translate(${item.coords[0].x} ${item.coords[0].y})`"
          >
            <g ref="caption"
               :transform="item.textCoords ? `translate(${item.textCoords.x} ${item.textCoords.y})` : ''">
              <g
                  style="transform-origin: 0 center;transform-box: fill-box;"
                  :transform="!itemData.reverse && itemData.templateData.access !== 'between-tire' && 'scale(1, -1)'"
              >
                <template v-if="item.caption">
                  <text v-for="(textLine, index) in textRows(item.caption)"
                        :y="index * fontSize(item) * 1.1 - 8"
                        :fill="selected ? '#00DBFF' : 'white'"
                        :font-size="fontSize(item)"
                        :transform="orientation(item) === 'vertical' ? 'rotate(-90)' : ''"
                        :text-anchor="textAlign(item)"
                  >{{ textLine }}
                  </text>
                </template>
              </g>
            </g>
          </g>
        </template>
      </g>
      <rect :width="itemData.templateData.access === 'between-tire' ? lineHeight.horizontal : 1"
            :height="itemData.templateData.access === 'between-tire' ? 1 : lineHeight.horizontal"
            fill="transparent"></rect>
      <g v-if="itemData.templateData.access === 'between-tire'">
        <g v-if="itemData.index !== null && !move">
          <line x1="0" y1="0.5" x2="0" y2="-40"
                :stroke="currentColorC"
                stroke-width="1"/>
          <line x1="140" y1="0.5" x2="140" y2="-40"
                :stroke="currentColorC"
                stroke-width="1"/>
        </g>
        <g v-if="!move">
          <circle v-if="itemData.index !== null && afterLoad" cx="0" cy="-40" r="3" fill="#171636"
                  :stroke="currentColorC"
                  stroke-width="1px"></circle>
          <circle v-if="itemData.index !== null && afterLoad" cx="140" cy="-40" r="3"
                  fill="#171636" :stroke="currentColorC" stroke-width="1px"></circle>
          <text v-if="itemData.index2 !== null && afterLoad" x="0" y="-49" font-size="10" text-anchor="middle"
                fill="white">{{ itemData.index2 }}
          </text>
          <text v-if="itemData.index !== null && afterLoad" x="140" y="-49" font-size="10" text-anchor="middle"
                fill="white">{{ itemData.index }}
          </text>
        </g>
      </g>
      <g v-else>
        <template v-if="itemData.templateData.access === 'top-tire'">
          <circle v-if="itemData.index !== null && afterLoad" cx="0" :cy="itemData.reverse ? 0 : lineHeight.vertical"
                  r="3"
                  fill="#171636"
                  :stroke="currentColorC" stroke-width="1px"></circle>
          <text v-if="itemData.index !== null && afterLoad" :y="itemData.reverse ? -9 : lineHeight.vertical + 18"
                font-size="10"
                text-anchor="middle"
                fill="white">{{ itemData.index }}
          </text>
        </template>
        <template v-else>
          <circle v-if="itemData.index !== null && afterLoad" cx="0" :cy="itemData.reverse ? 0 : lineHeight.vertical" r="3"
                  fill="#171636"
                  :stroke="currentColorC" stroke-width="1px"></circle>
          <text v-if="itemData.index !== null && afterLoad" :y="itemData.reverse ? -10 : lineHeight.vertical + 20" font-size="10"
                text-anchor="middle"
                fill="white">{{ itemData.index }}
          </text>
        </template>
      </g>
      <rect :x="itemData.templateData.access === 'between-tire' ? 0 : -(fullWidth/2)"
            :y="itemData.templateData.access === 'between-tire' ? -(height/2) : 0"
            :width="itemData.templateData.access === 'between-tire' ? lineHeight.horizontal : fullWidth"
            :height="itemData.templateData.access === 'between-tire' ? height : lineHeight.vertical"
            fill="transparent"/>
    </g>
  </g>
</template>

<script>
import {mapGetters} from 'vuex';
import svgElement from './element.vue';

export default {
  name: 'custom-cell',
  data() {
    return ({
      width: 0,
      height: 0,
      moveAdmittance: false,
      move: false,
      admittance: null,
      afterLoad: false,
      lineHeight: {
        vertical: 160,
        horizontal: 140,
      },
    });
  },
  components: {
    svgElement,
  },
  watch: {
    index() {
      this.itemData.index = this.index;
    },
    reverse(val) {
      const data = {
        id: this.itemData.id, field: 'coords',
        value: this.itemData.coords.map(e => val ?
            {x: e.x, y: e.y + this.lineHeight.vertical}
            : {x: e.x, y: e.y - this.lineHeight.vertical}),
      };
      this.$store.dispatch('changeData', data);
    },
    currentColorC: {
      immediate: true,
      handler(val) {
        this.itemData.templateData?.elements.map(el => el.color = val);
      },
    },
  },
  props: ['itemData', 'selected', 'instance'],
  computed: {
    currentColorC() {
      return this.selected ? '#00DBFF' : this.admittance === 'top-tire' ? '#465fb9' : 'white';
    },
    index() {
      return this.itemData.index;
    },
    lowestX() {
      if (this.itemData.hasOwnProperty('templateData')) {
        let lowest = Number.POSITIVE_INFINITY;
        this.itemData.templateData.elements.map(el => {
          el.coords.map(coord => {
            if (coord.x <= lowest) {
              lowest = coord.x;
            }
          });
        });
        return Math.abs(lowest !== Number.POSITIVE_INFINITY ? lowest : 0);
      }
    },
    highestY() {
      if (this.itemData.hasOwnProperty('templateData')) {
        let highest = Number.NEGATIVE_INFINITY;
        this.itemData.templateData.elements.map(el => {
          el.coords.map(coord => {
            if (coord.y >= highest) {
              highest = coord.y;
            }
          });
        });
        return Math.abs(highest);
      }
    },
    line() {
      if (this.itemData.hasOwnProperty('templateData')) {
        let line = this.itemData.templateData.elements.find(el => el.type === 'line');
        return line ? line : null;
      }
      return null;
    },
    fullWidth() {
      let remainder = this.width - this.lowestX;
      if (this.lowestX > remainder) {
        return this.lowestX * 2;
      } else {
        return remainder * 2;
      }
    },
    ...mapGetters(['elementsList', 'selectedElementData', 'manuallyCreating']),
    reverse() {
      return this.itemData.reverse;
    },
  },
  methods: {
    textRows(string) {
      return string.split(/\n/);
    },
    editTemplate() {
      this.$eventBus.$emit('editTemplateElement');
    },
    textAlign(item) {
      return item.textOptions?.alignment || '';
    },
    orientation(item) {
      return item.textOptions?.orientation || '';
    },
    fontSize(item) {
      return item.textOptions?.fontSize || '';
    },
    stickyTransformApply({x, y, target}) {
      if (this.itemData.admittance) {
        target && target.node.removeAttribute('transform');
        const betweenTires = this.itemData.admittance === 'between-tire';
        const tiresList = this.elementsList.filter(el =>
            betweenTires
                ? el.type === 'top-tire'
                || el.type === 'bottom-tire'
                : this.itemData.admittance === el.type);
        let counter = 0;
        let $el = null;

        tiresList.forEach(el => {
          const newX = this.itemData.coords[0].x + x;
          const newY = this.itemData.coords[0].y + y;

          if (this.MacroCollision(el.coords, [
            {x: newX, y: newY},
            {x: newX + this.width, y: newY + this.lineHeight.vertical},
          ])) {
            counter++;
            $el = el;
          }
        });

        if (!tiresList.length) {
          // Если соответствующей шины нет, то элемент может перемещаться свободно по оси X
          this.$emit('saveTransform', {
            target, type: 0,
            x, y: 0,
          });
        } else {
          if (betweenTires && $el?.beforeTire && $el?.beforeTire !== this.itemData.id) {
            toastr.error('Пространство между секциями уже занято');
          } else {
            if (betweenTires && counter > 1 || !betweenTires && !!counter) {
              this.$store.dispatch('clearTireConnection', this.itemData.id);

              if (!betweenTires) {
                $el.connection.push(this.itemData.id);
              } else {
                tiresList.forEach((tire, index) => {
                  if (tire === $el) {
                    const data = {
                      id: tiresList[index - 1].id,
                      field: 'afterTire',
                      value: this.itemData.id,
                    };
                    this.$store.dispatch('changeData', data);
                  }
                });
              }

              const data = {
                id: $el.id,
                field: betweenTires ? 'beforeTire' : 'connection',
                value: betweenTires ? this.itemData.id : $el.connection,
              };

              this.$store.dispatch('changeData', data);
              this.$store.dispatch('changeData', {
                id: this.itemData.id,
                field: 'currentTireID',
                value: $el.id,
              });

              let y = this.itemData.reverse ? $el.coords[0].y : $el.coords[0].y - this.lineHeight.vertical;
              if (this.itemData.hasOwnProperty('templateData') && this.itemData.templateData.access === 'between-tire') {
                y = $el.coords[0].y + 40;
              }
              this.$emit('saveTransform', {
                target, type: 1,
                x: betweenTires
                    ? $el.coords[0].x - this.width + 20
                    : this.itemData.coords[0].x + x, y,
              });

              this.refreshAdmittance();
            }

            if (this.itemData.index === null) {
              this.elementsIndexing();
            }
          }
        }
      }
    },
    elementsIndexing() {
      const admittances = ['bottom-tire', 'top-tire'];

      admittances.forEach(admittance => {
        const tiresList = this.elementsList.filter(el => admittance === el.type);
        tiresList.forEach((tire, tireIndex) => {
          this.elementsList
              .filter(el => tire.connection.includes(el.id) || tire.beforeTire === el.id)
              .sort((a, b) => a.coords[0].x - b.coords[0].x)
              .reduce((prev, curr, index) => {
                if (curr.id === this.itemData.id) {
                  if (typeof curr.index2 !== 'undefined') {
                    const prevTireElements = this.elementsList.filter(el => tiresList[tireIndex - 1].connection.includes(el.id)).sort((a, b) => a.coords[0].x - b.coords[0].x);
                    const prevIndex = prevTireElements[prevTireElements.length - 1]?.index || '0';

                    this.$store.dispatch('changeData', {
                      id: curr.id,
                      field: 'index2',
                      value: prevIndex
                          ? parseInt(prevIndex) || parseInt(prevIndex) === 0 ? parseInt(prevIndex) + 1 : prevTireElements.length : prevTireElements.length,
                    });
                  }

                  this.$store.dispatch('changeData', {
                    id: curr.id,
                    field: 'index',
                    value: prev
                        ? parseInt(prev.index)
                            ? parseInt(prev.index) + 1
                            : ++index
                        : 1,
                  });
                }
                return curr;
              }, null);
        });
      });
      //For update the value of the sidebar fields
      this.$store.dispatch('selectElement', {}).then(e => {
        this.$store.dispatch('selectElement', {
          node: this.$el,
          id: this.itemData.id,
        });
      });
    },
    refreshAdmittance() {
      const admittances = ['bottom-tire', 'top-tire'];

      admittances.forEach(admittance => {
        this.elementsList.filter(el => admittance === el.type).forEach(e => {
          if (e.beforeTire === this.itemData.id) {
            this.admittance = admittance;
          }
        });
      });
    },
    checkAdmittance(data) {
      const betweenTires = this.itemData.admittance === 'between-tire';
      const tiresList = this.elementsList.filter(el =>
          betweenTires
              ? el.type === 'top-tire'
              || el.type === 'bottom-tire'
              : this.itemData.admittance === el.type);
      let counter = 0;

      tiresList.forEach(el => {
        if (this.MacroCollision(el.coords, [{
          x: this.itemData.coords[0].x + data.mx,
          y: this.itemData.coords[0].y + data.my,
        }, {
          x: this.itemData.coords[0].x + data.mx + this.width,
          y: this.itemData.coords[0].y + data.my + this.height,
        }])) {
          counter++;
        }
      });

      this.moveAdmittance = betweenTires ? counter > 1 : !!counter;
    },
    refreshRectParams() {
      this.width = this.$refs[this.itemData.id].getBBox().width;
      this.height = this.$refs[this.itemData.id].getBBox().height;

      let data = {id: this.itemData.id, field: 'elementWidth', value: this.fullWidth || this.width};
      this.$store.dispatch('changeData', data);
      data = {id: this.itemData.id, field: 'elementHeight', value: this.height};
      this.$store.dispatch('changeData', data);
    },
    MacroCollision(a, b) {
      return (a[1].x >= b[0].x) && (a[0].x <= b[1].x) && (a[1].y + 5 >= b[0].y - 5) && (a[0].y - 5 <= b[1].y + 5);
    },
  },
  beforeDestroy() {
    const transformer = this.elementsList.find(el => el.income?.id === this.itemData.id
        || el.outcome?.id === this.itemData.id || el.free_connection?.id === this.itemData.id);

    if (transformer) {
      const connection = transformer.income.id === this.itemData.id ? 'income' : transformer.free_connection.id === this.itemData.id ? 'free_connection' : 'outcome';
      transformer[connection].id = null;
      if (transformer[connection].tire) transformer[connection].tire = null;
      const data = {
        id: transformer.id,
        field: connection,
        value: transformer[connection],
      };
      this.$store.dispatch('changeData', data);
      const connectedTires = transformer.connectedTires.filter(id => id !== this.itemData.currentTireID)
      console.log(connectedTires)
      this.$store.dispatch('changeData', {
        id: transformer.id,
        field: 'connectedTires',
        value: connectedTires
      });
    }
  },
  mounted() {
    if (this.itemData.admittance === 'top-tire' || this.itemData.admittance === 'bottom-tire') {
      this.admittance = this.itemData.admittance;
    }

    this.refreshAdmittance();

    this.instance.$on('move', (data) => {
      this.checkAdmittance(data);
      this.$emit('moveElement', {
        x: data.x,
        y: this.itemData.admittance === 'top-tire' || this.itemData.admittance === 'bottom-tire' ? 0 : data.y,
        target: data.target,
      });
    });
    this.instance.$on('move-start', (data) => {
      this.move = true;
    });
    this.instance.$on('move-end', (data) => {
      this.move = false;
      this.moveAdmittance = false;
      this.stickyTransformApply(data);
    });

    this.refreshRectParams();

    this.$nextTick(() => {
      this.afterLoad = true;
    });

    setTimeout(() => {
      this.stickyTransformApply({x: 0, y: 0});
    }, 5);
  },
};
</script>
