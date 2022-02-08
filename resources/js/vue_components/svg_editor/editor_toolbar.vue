<template>
  <div>
    <div class="editor-toolbar flex-wrap">
      <template
          v-for="tool in tools"
      >
        <template v-if="tool.tools && tool.tools.length > 0">
          <div
              @click="toggleDropdown(tool.name)"
              class="item item-multiple position-relative"
              :title="tool.tooltip">
            <svg class="icon">
              <g :style="`transform: translate(${tool.position})`">
                <component :switched-on="false" :size="tool.toolbarSize" color="#FFFFFF"
                           :is="tool.name"/>
              </g>
            </svg>
            <div
                :ref="`${tool.name}-toolList`"
                class="tool-dropdown"
            >
              <div
                  v-for="childTool in tool.tools"
                  :style="activeTool === (childTool.type + ' ' + childTool.name) ? 'border: solid white 2px' : 'border: solid transparent 2px'"
                  class="item" :title="childTool.tooltip" @click="createElement(childTool.name, childTool.type)"
                  style="width: 38px;height: 38px;"
                  placement="right">
                <svg class="icon">
                  <g :style="`transform: translate(${childTool.position})`">
                    <component :switched-on="false" :size="childTool.toolbarSize" color="#FFFFFF"
                               :is="childTool.name"/>
                  </g>
                </svg>
              </div>
            </div>
          </div>
        </template>
        <template v-else>
          <div
              v-if="tool.type === 'element'"
              :style="activeTool === (tool.type + ' ' + tool.name) ? 'border: solid white 2px' : 'border: solid transparent 2px'"
              class="item" :title="tool.tooltip" @click="createElement(tool.name, tool.type)" placement="right">
            <svg class="icon">
              <g :style="`transform: translate(${tool.position})`">
                <component :switched-on="false" :size="tool.toolbarSize" color="#FFFFFF"
                           :is="tool.name"/>
              </g>
            </svg>
          </div>
          <div
              v-else
              :style="activeTool === tool.name ? 'border: solid white 2px' : 'border: solid transparent 2px'"
              class="item" :title="tool.tooltip" @click="createElement(tool.name)" placement="right"
          >
            <svg class="icon">
              <use :xlink:href="`../../../../public/assets/backend/icons/sprite.svg#${tool.icon}`"></use>
            </svg>
          </div>
        </template>
      </template>

    </div>
  </div>
</template>

<script>
import grounding from './components/elements/grounding.vue';
import fuse from './components/elements/fuse.vue';
import transformer from './components/elements/transformer.vue';
import arrester from './components/elements/arrester.vue';
import autoswitch_horizontal from './components/elements/autoswitch_horizontal.vue';
import autoswitch_vertical from './components/elements/autoswitch_vertical.vue';
import cable_line from './components/elements/cable_line.vue';
import disconnector_horizontal from './components/elements/disconnector_horizontal.vue';
import disconnector_vertical from './components/elements/disconnector_vertical.vue';
import double_transformer from './components/elements/double_transformer.vue';
import earthing_transformer_vertical from './components/elements/earthing_transformer_vertical.vue';
import earthing_transformer_horizontal from './components/elements/earthing_transformer_horizontal.vue';
import loadswitch from './components/elements/loadswitch.vue';
import loadswitch_earthing_horizontal from './components/elements/loadswitch_earthing_horizontal.vue';
import loadswitch_earthing_vertical from './components/elements/loadswitch_earthing_vertical.vue';
import powerswitch from './components/elements/powerswitch.vue';
import shortcircuiter_horizontal from './components/elements/shortcircuiter_horizontal.vue';
import shortcircuiter_vertical from './components/elements/shortcircuiter_vertical.vue';
import surge_arrester from './components/elements/surge_arrester.vue';
import fuse_disconnector from './components/elements/fuse_disconnector.vue';
import arrester_horizontal from "./components/elements/arrester_horizontal";
import surge_arrester_horizontal from "./components/elements/surge_arrester_horizontal";
import grounding_vertical from "./components/elements/grounding_vertical";
import fuse_cutter from "./components/elements/fuse_cutter";

export default {
  name: 'editor_toolbar',
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
  props: {
    activeTool: String,
    createElement: Function,
    tools: Array,
  },
  methods: {
    toggleDropdown(toolName) {
      let element = this.$refs[`${toolName}-toolList`]
      if (element && element.length > 0) element[0].classList.toggle('active')
    }
  }
};
</script>

<style scoped>
.item.item-multiple:after {
  content: '';
  width: 0;
  height: 0;
  border-style: solid;
  border-width: 0 0 10px 10px;
  border-color: transparent transparent #171636 transparent;
  right: 1px;
  bottom: 1px;
  position: absolute;
}

.tool-dropdown {
  display: flex;
  transition: visibility 0.3s linear, opacity 0.3s linear;
  visibility: hidden;
  opacity: 0;
  min-width: 40px;
  height: 40px;
  background: #273661;
  position: absolute;
  left: 40px;
  top: 0;
  bottom: 0;
  z-index: 999;
}

.tool-dropdown.active {
  visibility: visible;
  opacity: 1;
}
</style>
