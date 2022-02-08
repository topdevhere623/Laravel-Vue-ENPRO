<template>
  <g
      :ref="'id-' + itemData.id"
      @mousedown="select"
      :class="elementMoveFlag ? 'move' : ''"
  >
    <component
        ref="component"
        @saveTransform="saveTransform"
        @moveElement="moveElement"
        :instance="instance"
        :is="`svg-${itemData.type}`"
        :itemData="itemData"
        :selected="selected"
        :move="elementMoveFlag"
        :class="highlight === 2 ? 'highlight' : highlight === 1 ? 'lowlight' : ''"
    />
    <g v-if="itemData.coords && itemData.coords.length"
       :transform="`translate(${itemData.coords[0].x} ${itemData.coords[0].y})`"
    >
      <g ref="caption"
         :transform="itemData.textCoords ? `translate(${itemData.textCoords.x} ${itemData.textCoords.y})` : ''">
        <g style="transform-origin: 0 center;transform-box: fill-box;"
           :transform="textReversed">
          <text v-for="(textLine, index) in textRows"
                :y="index * fontSize * 1.1 - 10"
                :fill="selected ? '#00DBFF' : 'white'"
                :font-size="fontSize"
                :transform="orientation === 'vertical' ? 'rotate(-90)' : ''"
                :text-anchor="textAlign"
                :style="textReversed"
          >{{ textLine }}
          </text>
        </g>
      </g>
    </g>
  </g>
</template>

<script>
import svgJunction from './junction.vue';
import svgText from './text';
import svgLine from './line';
import svgRect from './rect';
import svgTopTire from './top-tire';
import svgBottomTire from './bottom-tire';
import svgTopCustomCell from './custom-cell';
import svgElement from './element.vue';
import manipulate from '../../../mixins/manipulate';
import del from '../../../mixins/delete';
import {mapGetters} from 'vuex';
import transformer from './transformer';
import three_phase_transformer from "./three_phase_transformer";

export default {
  name: 'svg_component',
  mixins: [manipulate, del],
  components: {
    'svg-text': svgText,
    'svg-line': svgLine,
    'svg-rect': svgRect,
    'svg-top-tire': svgTopTire,
    'svg-bottom-tire': svgBottomTire,
    'svg-custom-cell': svgTopCustomCell,
    'svg-transformer': transformer,
    'svg-element': svgElement,
    'svg-junction': svgJunction,
    'svg-three_phase_transformer': three_phase_transformer,
  },
  data() {
    return (
        {
          elementMoveFlag: false,
          instance: new Vue,
          selected: false,
          highlight: 0, // 0 - normal, 1 - lower, 2 - highlighted
          componentWidth: 0,
          componentHeight: 0,
        }
    );
  },
  computed: {
    ...mapGetters(['selectedElement', 'selectedElement', 'activeTemplate', 'selectedElementData', 'waitSelect', 'waitSelect', 'findById']),
    activeTemplateElementData() {
      if (this.activeTemplate.elements !== undefined) {
        return this.activeTemplate.elements.find(el => el.id === this.selectedElement.id);
      }
    },
    textReversed() {
      return this.itemData.textOptions?.reversed ? 'scale(1, -1)' : '';
    },
    textRows() {
      return this.itemData.caption?.split(/\n/);
    },
    textAlign() {
      return this.itemData.textOptions?.alignment || '';
    },
    orientation() {
      return this.itemData.textOptions?.orientation || '';
    },
    fontSize() {
      return this.itemData.textOptions?.fontSize || '';
    },
    reverse() {
      return this.itemData.reverse;
    },
  },
  mounted() {
    this.$nextTick(() => {
      this.$refs['caption'] && this.makeInteractable(this.$refs['caption'], {gridDisabled: true}, 'text');
      this.itemData.interactable && this.makeInteractable(this.$refs['component'].$el);
    });

    if (this.itemData.interactable) {
      this.$on('move-end', this.onMoveEnd);
      this.$on('move-start', this.onMoveStart);
      this.$on('move', this.onMove);
      this.$on('move-point', this.onMovePoint)
    }

    this.$on('move-end-text', this.onMoveEndText);
    this.$on('move-text', this.onMoveText);
    this.componentWidth = this.$refs['id-' + this.itemData.id].getBBox().width;
    this.componentHeight = this.$refs['id-' + this.itemData.id].getBBox().height;
    const coordX = this.itemData.coords[this.itemData.coords.length - 1].x;
    const coordY = this.itemData.coords[this.itemData.coords.length - 1].y;
    let itemDataCoordsRemainder = JSON.parse(JSON.stringify(this.itemData.coords));
    itemDataCoordsRemainder.pop();

    if ((coordX + this.componentWidth) >= this.SVGParams.width) {
      const data = {
        id: this.itemData.id,
        field: 'coords',
        value: [
          ...itemDataCoordsRemainder,
          {
            x: this.SVGParams.width - this.componentWidth,
            y: coordY,
          },
        ],
      };
      this.changeData(data);
    }
    if ((coordY + this.componentHeight) >= this.SVGParams.height) {
      const data = {
        id: this.itemData.id,
        field: 'coords',
        value: [
          ...itemDataCoordsRemainder,
          {
            x: coordX,
            y: this.SVGParams.height - this.componentHeight,
          },
        ],
      };
      this.changeData(data);
    }
  },
  props: [
    'itemData',
    'parentRefs',
    'cellSize',
    'SVGParams',
    'changeData',
  ],
  watch: {
    waitSelect(current) {
      this.highlight = current.id ?
          !current.connectedTires.includes(this.itemData.currentTireID) &&
          (current.field === 'income' && this.itemData.admittance === 'top-tire' && this.itemData.reverse === false
              || current.field === 'outcome' && this.itemData.admittance === 'bottom-tire' && this.itemData.reverse === true
              || current.field === 'free_connection' && this.itemData.admittance === 'top-tire' && this.itemData.reverse === false
              || current.field === 'free_connection' && this.itemData.admittance === 'bottom-tire' && this.itemData.reverse === true)
              ? 2 : 1
          : 0;
    },
    selectedElement: {
      immediate: true,
      handler(current, old) {
        if (current.id === this.itemData.id) {
          this.selected = true;
          this.$el && this.makeEditable(this.$el);

          const element = {
            node: this.$el,
            id: this.itemData.id,
          };

          const unselect = (e) => {
            if (!this.waitSelect.id) {
              const target = e.target;

              if (!this.checkClosest(target, element.node)
                  && !this.checkClosest(target, target.closest('#element-settings-sidebar'))
                  && !this.checkClosest(target, target.closest('#modal'))) {
                if (element.id === this.itemData.id) {
                  this.$store.dispatch('selectElement', {});
                }
                window.removeEventListener('mousedown', unselect);
              }
            }
          };
          window.addEventListener('mousedown', unselect);

        } else if (old?.id === this.itemData.id) {
          this.destroyEditable(this.$el);
          this.selected = false;
        }
      },
    },
    reverse: {
      immediate: true,
      handler(val) {
        if (this.itemData.hasOwnProperty('templateData') && this.itemData.templateData.access !== 'between-tire') {
          this.itemData.templateData.elements.sort((a, b) => {
            if (a.type !== 'line' && b.type !== 'line') {
              if (!val) {
                return b.coords[0].y - a.coords[0].y;
              } else {
                return a.coords[0].y - b.coords[0].y;
              }
            }
          });
          this.itemData.templateData.elements.map(el => {
            el.terminals?.sort((a, b) => {
              if (!val) {
                return b.number - a.number;
              } else {
                return a.number - b.number;
              }
            });
          });
        }
      },
    },
  },
  methods: {
    select() {
      if (this.waitSelect.id && !this.waitSelect.connectedTires.includes(this.itemData.currentTireID)) {
        if (this.waitSelect.field === 'income' && this.itemData.admittance === 'top-tire'
            || this.waitSelect.field === 'outcome' && this.itemData.admittance === 'bottom-tire' || this.waitSelect.field === 'free_connection') {
          const value = this.findById(this.waitSelect.id)?.[this.waitSelect.field];
          value.id = this.itemData.id;
          if (this.waitSelect.field === 'free_connection') value.tire = this.itemData.admittance
          const data = {id: this.waitSelect.id, field: this.waitSelect.field, value: value};
          this.$store.dispatch('changeData', data).then(() => {
            const updateSelected = {
              id: this.itemData.id,
              field: 'reverse',
              value: this.itemData.admittance === 'top-tire',
            };
            this.$store.dispatch('changeData', updateSelected);
            this.$store.dispatch('waitSelectUpdate', {});
          });
          this.$store.dispatch('changeData', {
            id: this.waitSelect.id,
            field: 'connectedTires',
            value: [...this.waitSelect.connectedTires, this.itemData.currentTireID]
          })
        } else {
          alert('Для соединения нельзя выбрать данный объект');
        }
      } else {
        setTimeout(() => {
          const element = {
            node: this.$el,
            id: this.itemData.id,
          };

          if (this.selectedElement.id !== element.id && !this.itemData.disableSelection) {
            this.$store.dispatch('selectElement', element);
          }
        });
      }
    },
    onMove(data) {
      this.instance.$emit('move', data);
    },
    onMoveEnd(data) {
      this.elementMoveFlag = false;
      this.instance.$emit('move-end', data);
    },
    onMoveStart() {
      this.elementMoveFlag = true;
      this.instance.$emit('move-start');
    },
    onMovePoint(data) {
      this.instance.$emit('move-point', data);
    },
    onMoveText(data) {
      this.moveElement(data);
    },
    onMoveEndText(data) {
      this.saveTextTransform(data);
    },
    moveElement({x, y, target}) {
      target.dmove(x, y);
    },
    saveTextTransform({x, y, target, index, type}) {
      target.node.removeAttribute('transform');
      const data = {
        id: this.selectedElement.id,
        field: 'textCoords',
        value: {
          x: this.itemData.textCoords?.x + x,
          y: this.itemData.textCoords?.y + y,
        },
      };
      this.changeData(data);
    },
    saveTransform({x, y, target, index, type}) {
      target && target.node.removeAttribute('transform');

      const customTemplate = !!this.activeTemplateElementData;
      const currentArr = this.activeTemplateElementData
          ? this.activeTemplateElementData.coords :
          this.itemData ? this.itemData.coords : [];

      // todo adaptation for Rect
      const data = {
        id: this.itemData.id,
        field: 'coords',
        value: currentArr.map((el, i) => {
          return i === index || typeof index === 'undefined' ? {
            x: this.itemData.gridDisabled
                ? type === 1 ? x : el.x + x
                : Math.round((type === 1 ? x : el.x + x) / this.cellSize) * this.cellSize,
            y: this.itemData.gridDisabled
                ? type === 1 ? y : el.y + y
                : Math.round((type === 1 ? y : el.y + y) / this.cellSize) * this.cellSize,
          } : {
            x: el.x,
            y: el.y,
          };
        }),
      };
      if (customTemplate) {
        this.changeData(data);
        this.destroyEditable(this.$el);
        this.makeEditable(this.$el);
      } else {
        this.$store.dispatch('changeData', data).then(() => {
          this.destroyEditable(this.$el);
          this.makeEditable(this.$el);
        });
      }

    },
  },
};

</script>

<style scoped>
.highlight {
  filter: url(#dropshadow);
  cursor: pointer !important
}

.lowlight {
  opacity: .2;
  pointer-events: none
}
</style>
