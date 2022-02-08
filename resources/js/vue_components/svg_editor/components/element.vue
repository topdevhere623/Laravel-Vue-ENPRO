<template>
  <g
      :ref="itemData.id"
  >
    <g
        :transform="`matrix(1 0 0 1 ${itemData.coords[0].x} ${itemData.coords[0].y})`"
    >
      <g :style="itemData.hidden === true ? 'opacity: 0.5' : ''">
        <g
            style="transform-box: fill-box;"
            :style="(itemData.rotatePoint && itemData.inverted) ? `transform-origin: ${width/2}px ${rotatePoint}` : `transform-origin: ${width/2}px ${height/2}px`"
            :transform="transformRotateVertical"
        >
          <g
              :style="itemData.inverted ? `transform-origin: ${width/2}px ${height/2}px;transform-box: fill-box;` : ``"
              :transform="transformRotateHorizontal">
            <g
                style="transform-box: fill-box;"
                :style="`transform-origin: ${rotatePoint}px 0px`"
                :transform="itemData.inverted ? `rotate(-90)`: ``">
              <component
                  :selected="selected"
                  :size="elementSizes[itemData.elementName]"
                  :color="currentColor"
                  :is="itemData.elementName"
                  :switched-on="itemData.switchElement"
              />
              <rect
                  :y="itemData.horizontal && -(height/2)"
                  :width="rotateRect ? height : width"
                  :height="rotateRect ? width : height" fill="transparent"/>
            </g>
          </g>
        </g>
      </g>
    </g>
  </g>

</template>

<script>
import grounding from './elements/grounding.vue';
import fuse from './elements/fuse.vue';
import transformer from './elements/transformer.vue';
import arrester from './elements/arrester.vue';
import autoswitch_horizontal from './elements/autoswitch_horizontal.vue';
import autoswitch_vertical from './elements/autoswitch_vertical.vue';
import cable_line from './elements/cable_line.vue';
import disconnector_horizontal from './elements/disconnector_horizontal.vue';
import disconnector_vertical from './elements/disconnector_vertical.vue';
import double_transformer from './elements/double_transformer.vue';
import earthing_transformer_vertical from './elements/earthing_transformer_vertical.vue';
import earthing_transformer_horizontal from './elements/earthing_transformer_horizontal.vue';
import loadswitch from './elements/loadswitch.vue';
import loadswitch_earthing_horizontal from './elements/loadswitch_earthing_horizontal.vue';
import loadswitch_earthing_vertical from './elements/loadswitch_earthing_vertical.vue';
import powerswitch from './elements/powerswitch.vue';
import shortcircuiter_horizontal from './elements/shortcircuiter_horizontal.vue';
import shortcircuiter_vertical from './elements/shortcircuiter_vertical.vue';
import surge_arrester from './elements/surge_arrester.vue';
import fuse_disconnector from './elements/fuse_disconnector.vue';
import arrester_horizontal from "./elements/arrester_horizontal";
import surge_arrester_horizontal from "./elements/surge_arrester_horizontal";
import grounding_vertical from "./elements/grounding_vertical";
import fuse_cutter from "./elements/fuse_cutter";

export default {
  name: 'elementWrapper',
  props: ['itemData', 'instance', 'selected'],
  components: {
    grounding,
    fuse,
    transformer,
    arrester,
    autoswitch_horizontal,
    autoswitch_vertical,
    cable_line,
    disconnector_horizontal,
    disconnector_vertical,
    double_transformer,
    earthing_transformer_vertical,
    earthing_transformer_horizontal,
    loadswitch,
    loadswitch_earthing_horizontal,
    loadswitch_earthing_vertical,
    powerswitch,
    shortcircuiter_horizontal,
    shortcircuiter_vertical,
    surge_arrester,
    fuse_disconnector,
    arrester_horizontal,
    surge_arrester_horizontal,
    grounding_vertical,
    fuse_cutter
  },
  data() {
    return {
      width: 0,
      height: 0,
      rotateRect: false,
      elementSizes: {
        grounding: '1.5',
        fuse: '0.5',
        transformer: '0.5',
        arrester: '0.5',
        autoswitch_horizontal: '0.5',
        autoswitch_vertical: '0.5',
        cable_line: '1',
        disconnector_horizontal: '0.5',
        disconnector_vertical: '0.5',
        double_transformer: '0.5',
        earthing_transformer_vertical: '0.5',
        earthing_transformer_horizontal: '0.5',
        loadswitch: '0.5',
        loadswitch_earthing_horizontal: '0.5',
        loadswitch_earthing_vertical: '0.5',
        powerswitch: '0.5',
        shortcircuiter_horizontal: '0.5',
        shortcircuiter_vertical: '0.5',
        surge_arrester: '0.5',
        fuse_disconnector: '0.5',
        arrester_horizontal: '0.5',
        surge_arrester_horizontal: '0.5',
        grounding_vertical: '1.5',
        fuse_cutter: '0.5'
      },
    };
  },
  watch: {
    itemData: {
      immediate: true,
      deep: true,
      handler(current, old) {
        if (!old && current.inverted) {
          this.rotateRect = true;
        }
      },
    },
  },
  computed: {
    rotatePoint() {
      return (this.itemData.rotatePoint) ? this.itemData.rotatePoint[this.getBrowser] || 0 : 0
    },
    getBrowser() {

      let userAgent = navigator.userAgent;
      let browserName = null;

      if (userAgent.match(/chrome|chromium|crios/i)) {
        browserName = "chrome";
      } else if (userAgent.match(/firefox|fxios/i)) {
        browserName = "firefox";
      } else if (userAgent.match(/safari/i)) {
        browserName = "safari";
      } else if (userAgent.match(/opr\//i)) {
        browserName = "opera";
      } else if (userAgent.match(/edg/i)) {
        browserName = "edge";
      }
      return browserName
    },
    currentColor() {
      if (this.itemData.color !== '') {
        return this.itemData.color;
      } else {
        return '#FFFFFF';
      }
    },
    transformRotateHorizontal() {
      if (this.itemData.rotate !== undefined) {
        return `scale(${this.itemData.rotate.horizontal === 1 ? '-1' : '1'}, 1)`;
      } else {
        return '';
      }
    },
    transformRotateVertical() {
      if (this.itemData.rotate !== undefined) {
        if (this.itemData.rotate.vertical === 1) {
          return `scale(1, -1)`;
        } else {
          return `scale(1, 1)`;
        }
      } else {
        return '';
      }
    },
  },
  mounted() {
    this.instance.$on('move', (data) => {
      this.$emit('moveElement', data);
    });
    this.instance.$on('move-end', (data) => {
      this.$emit('saveTransform', data);
    });
    this.width = this.$refs[this.itemData.id].getBBox().width;
    this.height = this.$refs[this.itemData.id].getBBox().height;
  },
};
</script>

<style scoped></style>
