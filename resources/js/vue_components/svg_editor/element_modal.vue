<template>
  <div>
    <div
        :id="`deleteModalElement-${method}`"
        role="dialog"
        style="z-index: 1701;"
        class="modal modal-blue fade"
    >
      <div id="modal" class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            Вы действительно хотите удалить?
          </div>
          <div style="background-color: rgb(32, 35, 70);" class="modal-footer">
            <button
                type="button"
                @click="deleteElement()"
                class="btn btn-info waves-effect waves-classic">
              Подтвердтить
            </button>
            <button
                type="button"
                id="closeModalElement"
                @click="closeDeleteModal()"
                class="btn btn-info waves-effect waves-classic">
              Закрыть
            </button>
          </div>
        </div>
      </div>
    </div>
    <div
        :class="`modal ${method} fade modal-blue`"
        tabindex="-1"
        role="dialog"
        data-backdrop="static" data-keyboard="false"
    >
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 id="modalMessageTitle" class="modal-title">Кастомный компонент</h4>
            <button type="button" class="close" @click="hideModal" aria-label="Close">
              <span style="font-size: 2rem" aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" style="background-color: rgb(32, 35, 70);">
            <div
                class="alert alert-danger fade"
                :class="errors.length > 0 ? 'show' : 'p-0 m-0'"
                role="alert"
            >
              <li
                  v-for="(error, index) in errors"
                  :key="index">
                {{ error }}
              </li>
            </div>
            <div class="d-flex justify-content-between">
              <div>
                <div class="form-field m-0">
                  <input id="element_name" v-model="elementName" type="text" class="text-field">
                  <label for="element_name" class="form-input-label">Название компонента</label>
                </div>
                <div class="d-flex template_values flex-wrap">
                  <div v-if="method !== 'editElement'" class="d-flex align-items-center">
                    <h5 class="m-0">Допуск</h5>
                    <switcher
                        class="m-0 ml-5"
                        @change="changeTemplate"
                        :options="access"
                        id="access"
                        :value="elementAccess"
                        name="access"
                    />
                  </div>
                  <div v-if="this.elementAccess !== 'between-tire'" class="d-flex align-items-center">
                    <h5 class="m-0">
                      С ошиновкой
                    </h5>
                    <label class="switch ml-5 mb-0">
                      <input type="checkbox" v-model="hasLine">
                      <span class="slider round"></span>
                    </label>
                  </div>
                </div>
                <div class="d-flex">
                  <editor_toolbar class="modal_toolbar" :tools="filteredTools"
                                  :active-tool="activeTool"
                                  :create-element="createElement"/>
                  <svg_area
                      class="template_svg_area"
                      :changeData="changeData"
                      :cellSize="cellSize"
                      :s-v-g-params="SVGParams"
                      :elements-list="templateData.elements"
                      :add-element="addElement"
                      :data="data"
                      :active-tool="activeTool"
                  >
                    <template v-if="this.elementAccess !== 'between-tire'" v-slot:fixed>
                      <polygon :stroke="currentColor" stroke-width="3" stroke-linejoin="round" fill="none"
                               points="0,0.3 140,0.3" class=""></polygon>
                    </template>
                  </svg_area>
                </div>
              </div>
              <div v-if="selectedElementData" id="element-settings-sidebar" class="modal_sidebar_wrapper">
                <div class="form-group">
                  <fields_list :selected-element-data="selectedElementData" :change="changeElement"/>
                  <div class="row">
                    <div class="form-field col-6">
                      <button
                          @click="deleteElement"
                          class="button bordered delete_btn">
                        Удалить
                      </button>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>
          <div class="modal-footer" style="background-color: rgb(32, 35, 70);">
            <button
                v-if="method === 'editElement'"
                @click="saveAsNew"
                type="button"
                class="button waves-effect waves-classic">
              Сохранить как новый шаблон
            </button>
            <button
                @click="saveElement(addTemplateElement)"
                type="button"
                class="button waves-effect waves-classic">
              Сохранить
            </button>
            <button
                @click="hideModal"
                type="button"
                id="closeModal"
                class="button bordered waves-effect waves-classic">
              Закрыть
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

</template>

<script>
import Switcher from './fields/switcher.vue';
import fields_list from './components/fields_list.vue';
import {mapActions, mapGetters} from 'vuex';
import common from '../../mixins/common.js';

export default {
  name: 'element_modal',
  mixins: [common],
  components: {
    Switcher,
    fields_list,
  },
  props: {
    data: {},
    initialTemplateData: {},
    addTemplateElement: Function,
    method: '',
  },
  data() {
    return {
      activeTool: '',
      errors: [],
      elementName: '',
      elementAccess: '',
      hasLine: null,
      access: [
        {
          icon: 'top-tire',
          label: 'Секция высокого напряжения',
          name: 'top-tire',
        },
        {
          icon: 'bottom-tire',
          label: 'Секция низкого напряжения',
          name: 'bottom-tire',
        },
        {
          icon: 'between-tire',
          label: 'Межсекционная ячейка',
          name: 'between-tire',
        },
      ],
      tools: [
        // {
        //     type: 'element',
        // height: 0,
        //     name: 'autoswitch_horizontal',
        //     toolbarSize: '0.5',
        //     tooltip: 'Выключатель автоматический(горизонтальный)',
        //     positionX: 80.5,
        // position: '0, 10px'
        // },
        // {
        //     type: 'element',
        // height: 0,
        //     name: 'disconnector_horizontal',
        //     toolbarSize: '0.5',
        //     tooltip: 'Разъединитель(горизонтальный)',
        //     positionX: 80.5,
        // position: '0, 10px'
        // },
        // {
        //     type: 'element',
        // height: 0,
        //     name: 'earthing_transformer_horizontal',
        //     toolbarSize: '0.4',
        //     tooltip: 'Заземление трансформаторов(горизонтальный)',
        //     positionX: 53.7,
        // position: '0, 10px'
        // },
        // {
        //     type: 'element',
        // height: 0,
        //     name: 'loadswitch_earthing_horizontal',
        //     toolbarSize: '0.25',
        //     tooltip: 'Выключатель нагрузки с заземляющим ножом(горизонтальный)',
        //     positionX: 39.7,
        // position: '0, 10px'
        // },
        // {
        //     type: 'element',
        // height: 0,
        //     name: 'shortcircuiter_horizontal',
        //     toolbarSize: '0.25',
        //     tooltip: 'Короткозамыкатель(горизонтальный)',
        //     positionX: 80.5,
        // position: '0, 10px'
        // },
        // {
        //     type: 'element',
        // height: 0,
        //     name: 'earthing_transformer_vertical',
        //     toolbarSize: '0.4',
        //     tooltip: 'Заземление трансформаторов(вертикальный)',
        //     positionX: 59.7,
        //     positionY: 77.2,
        //     position: '5px, 2px',
        //     disableRotateHorizontal: true,
        //     disableRotateVertical: true,
        // },
        // {
        //     type: 'element',
        // height: 0,
        //     name: 'loadswitch_earthing_vertical',
        //     toolbarSize: '0.25',
        //     tooltip: 'Выключатель нагрузки с заземляющим ножом(вертикальный)',
        //     positionX: 39.7,
        //     positionY: 76,
        //     position: '3px, 2px',
        //     disableRotateHorizontal: true,
        //     disableRotateVertical: true,
        //     markKey: 'loadbreakswitchinfo',
        //     class: 'LoadBreakSwitch'
        // },
        // {
        //     type: 'element',
        // height: 0,
        //     name: 'shortcircuiter_vertical',
        //     toolbarSize: '0.25',
        //     tooltip: 'Короткозамыкатель',
        //     positionX: 65.3,
        //     positionY: 71.3,
        //     switchable: true,
        //     position: '6px, 5px',
        //     markKey: 'disconnectorinfo',
        //     rotatePoint: '1.9px',
        //     enableAccess: ['top-tire', 'bottom-tire', 'between-tire'],
        //     class: 'GrondDisconnector',
        // },
        // {
        //   type: 'element',
        // height: 0,
        //   name: 'surge_arrester',
        //   toolbarSize: '0.4',
        //   tooltip: 'ОПН',
        //   positionX: 62.8,
        //   positionY: 61.2,
        //   position: '6px, 0',
        //   rotatePoint: '14.8px',
        //   enableAccess: ['top-tire', 'bottom-tire'],
        //   class: 'SurgeArrester',
        //   terminalsCount: 2,
        // },
        // {
        //   type: 'element',
        // height: 0,
        //   name: 'arrester',
        //   toolbarSize: '0.3',
        //   tooltip: 'Разрядник',
        //   positionX: 63.7,
        //   positionY: 60.1,
        //   position: '8px, 0',
        //   markKey: 'dischargerinfo',
        //   enableAccess: ['top-tire', 'bottom-tire'],
        //   class: 'SurgeArrester',
        //   terminalsCount: 2,
        // },
        {
          type: 'element',
          hidable: true,
          height: 0,
          name: 'arrester_horizontal',
          toolbarSize: '0.2',
          tooltip: 'Разрядник (горизонтальный)',
          positionX: 5,
          positionY: 60.1,
          position: '0, 15px',
          markKey: 'dischargerinfo',
          enableAccess: ['top-tire', 'bottom-tire'],
          class: 'SurgeArrester',
          terminalsCount: 1,
          horizontal: true,
          // switchable: true,
        },
        {
          type: 'element',
          hidable: true,
          height: 0,
          name: 'surge_arrester_horizontal',
          toolbarSize: '0.2',
          tooltip: 'ОПН (горизонтальный)',
          positionX: 5,
          positionY: 61.2,
          position: '0, 15px',
          enableAccess: ['top-tire', 'bottom-tire'],
          class: 'SurgeArrester',
          terminalsCount: 1,
          horizontal: true,
          // switchable: true,
        },
        {
          name: 'junction',
          type: 'junction',
          tooltip: 'Ошиновка',
          icon: 'line',
          positionX: 69.5,
          positionY: 79.5,
          enableAccess: ['top-tire', 'bottom-tire'],
          class: 'junction',
          terminalsCount: 2,
        },
        {
          tooltip: 'Заземление',
          enableAccess: ['top-tire', 'bottom-tire'],
          position: '2px, 15px',
          toolbarSize: '1.3',
          name: 'grounding',
          tools: [
            {
              type: 'element',
              hidable: true,
              height: 0,
              name: 'grounding',
              tooltip: 'Заземляющий разъединитель',
              toolbarSize: '1.3',
              positionX: 41.5,
              positionY: 89.4,
              switchable: true,
              position: '0px, 10px',
              disableRotateVertical: true,
              enableAccess: ['top-tire', 'bottom-tire'],
              class: 'GroundDisconnector',
              terminalsCount: 1,
              horizontal: true
            },
            {
              type: 'element',
              hidable: true,
              height: 30,
              name: 'grounding_vertical',
              tooltip: 'Заземление (Вертикальный)',
              toolbarSize: '1.3',
              positionX: 65,
              positionY: 89.4,
              switchable: true,
              position: '8px, 0',
              disableRotateVertical: true,
              enableAccess: ['top-tire', 'bottom-tire'],
              class: 'GroundDisconnector',
              terminalsCount: 1,
              cutLine: true,
            },
          ],
        },
        {
          type: 'element',
          hidable: true,
          height: 30,
          name: 'autoswitch_vertical',
          toolbarSize: '0.4',
          tooltip: 'Выключатель автоматический',
          positionX: 62.8,
          positionY: 88.9,
          position: '8px, 0',
          switchable: true,
          markKey: 'circuitbreakerinfo',
          rotatePoint: {
            chrome: '2px',
            firefox: '0.4px'
          },
          enableAccess: ['top-tire', 'bottom-tire', 'between-tire'],
          class: 'breaker',
          terminalsCount: 2,
        },
        {
          type: 'element',
          hidable: true,
          height: 10,
          name: 'cable_line',
          toolbarSize: '1.5',
          tooltip: 'Кабельная линия',
          positionX: 63.6,
          positionY: 78.7,
          position: '3px, 5px',
          markKey: 'cableboxkind',
          enableAccess: ['top-tire', 'bottom-tire'],
          class: 'ACLIneSegment',
          terminalsCount: 2,
        },
        {
          type: 'element',
          hidable: true,
          height: 30,
          name: 'disconnector_vertical',
          toolbarSize: '0.4',
          tooltip: 'Разъединитель',
          positionX: 62.8,
          positionY: 88.9,
          position: '8px, 0',
          switchable: true,
          markKey: 'disconnectorinfo',
          rotatePoint: {
            chrome: '2px',
            firefox: '0.4px'
          },
          enableAccess: ['top-tire', 'bottom-tire', 'between-tire'],
          class: 'Disconnector',
          terminalsCount: 2,
        },
        {
          type: 'element',
          hidable: true,
          height: 70,
          name: 'fuse_disconnector',
          toolbarSize: '0.2',
          tooltip: 'Разъединитель-предохранитель',
          positionX: 62.8,
          positionY: 88.9,
          position: '8px, 0',
          switchable: true,
          markKey: 'disconnectorfuseinfo',
          rotatePoint: {
            chrome: '3.5px',
            firefox: '2.4px'
          },
          enableAccess: ['top-tire', 'bottom-tire', 'between-tire'],
          class: 'CompositeSwitch',
          terminalsCount: 2,
        },
        {
          type: 'element',
          hidable: true,
          height: 45,
          name: 'double_transformer',
          toolbarSize: '0.3',
          tooltip: 'Трансфоматор напряжения',
          positionX: 59.7,
          adminName: true,
          positionY: 52,
          position: '3px, 0',
          cutLine: true,
          disableRotateVertical: true,
          markKey: 'voltagetransformerinfo',
          enableAccess: ['top-tire', 'bottom-tire'],
          class: 'PowerTransformer',
          terminalsCount: 1,
        },
        {
          type: 'element',
          hidable: true,
          height: 30,
          name: 'loadswitch',
          toolbarSize: '0.4',
          tooltip: 'Выключатель нагрузки',
          positionX: 62.8,
          positionY: 88.9,
          position: '8px, 0',
          switchable: true,
          markKey: 'loadbreakswitchinfo',
          rotatePoint: {
            chrome: '2px',
            firefox: '0.4px'
          },
          enableAccess: ['top-tire', 'bottom-tire', 'between-tire'],
          class: 'LoadBreakSwitch',
          terminalsCount: 2,
        },
        {
          type: 'element',
          hidable: true,
          height: 30,
          name: 'powerswitch',
          toolbarSize: '0.4',
          tooltip: 'Выключаель силовой',
          positionX: 62.8,
          positionY: 88.9,
          position: '8px, 0',
          switchable: true,
          markKey: 'breakerinfo',
          rotatePoint: {
            chrome: '2px',
            firefox: '0.4px'
          },
          enableAccess: ['top-tire', 'bottom-tire', 'between-tire'],
          class: 'LoadBreakSwitch',
          terminalsCount: 2,
        },
        {
          type: 'element',
          hidable: true,
          height: 30,
          name: 'fuse',
          toolbarSize: '0.4',
          tooltip: 'Предохранитель',
          positionX: 65.7,
          positionY: 65.1,
          position: '10px, 0',
          markKey: 'fuseinfo',
          switchable: true,
          enableAccess: ['top-tire', 'bottom-tire'],
          class: 'Fuse',
          terminalsCount: 2,
        },
        {
          type: 'element',
          hidable: true,
          height: 30,
          name: 'fuse_cutter',
          toolbarSize: '0.4',
          tooltip: 'предохранитель-рубильник',
          positionX: 65.7,
          positionY: 65.1,
          position: '18px, 0',
          markKey: 'fuseinfo',
          switchable: true,
          enableAccess: ['top-tire', 'bottom-tire'],
          class: 'Fuse',
          terminalsCount: 2,
        },
        {
          type: 'element',
          hidable: true,
          height: 30,
          name: 'transformer',
          toolbarSize: '0.4',
          tooltip: 'Трансформатор',
          positionX: 65.7,
          positionY: 85.9,
          position: '8px, 0',
          markKey: 'currenttransformerinfo',
          rotatePoint: {
            chrome: '2px',
            firefox: '2.4px'
          },
          enableAccess: ['top-tire', 'bottom-tire', 'between-tire'],
          class: 'CurrentTransformer',
          terminalsCount: 1,
        },
      ],
      cellSize: 10,
      SVGParams: {
        width: 140,
        height: 160,
        realWidth: 0,
        realHeight: 0,
      },
      templateData: {},
    };
  },
  computed: {
    ...mapGetters(['selectedElement', 'activeTemplate', 'allState']),
    selectedElementData() {
      if (this.templateData.elements !== undefined) {
        return this.templateData.elements.find(el => el.id === this.selectedElement.id);
      }
    },
    currentColor() {
      if (this.elementAccess === 'top-tire') {
        return '#465fb9';
      } else {
        return '#FFFFFF';
      }
    },
    filteredTools() {
      return this.tools.filter(tool => tool.enableAccess.includes(this.elementAccess));
    },
  },
  watch: {
    initialTemplateData: {
      immediate: true,
      deep: true,
      handler(val) {
        if (val && Object.keys(val).length > 0) {
          this.templateData = JSON.parse(JSON.stringify(val));
          this.elementName = JSON.parse(JSON.stringify(val.name));
          this.elementAccess = JSON.parse(JSON.stringify(val.access));
          this.hasLine = JSON.parse(JSON.stringify(val.hasLine));
        }
      },
    },
    hasLine: {
      deep: true,
      immediate: true,
      handler(val) {
        if (val) {
          let line = this.templateData.elements.find(el => el.type === 'line');
          if (!line) {
            this.addLine();
          }
        } else {
          this.templateData.elements = this.templateData.elements?.filter(el => el.type !== 'line');
        }
      },
    },
    templateData: {
      deep: true,
      immediate: true,
      handler(val) {
        let line = val.elements?.find(el => el.type === 'line');
        let cutLineEl = val.elements?.find(el => el.cutLine === true);
        const initialLineHeight = this.SVGParams.height;
        const initialLineWidth = this.SVGParams.width;
        if (line) {
          if (cutLineEl) {
            if (this.elementAccess === 'between-tire') {
              line.coords[1].x = cutLineEl.coords[0].x + 10;
            }
                // else if (this.elementAccess === 'top-tire') {
                //   line.coords[0].y = cutLineEl.coords[0].y;
                //   cutLineEl.connectBottom = true;
            // }
            else {
              line.coords[1].y = cutLineEl.coords[0].y;
              // cutLineEl.connectBottom = false;
            }
          } else {
            if (this.elementAccess === 'between-tire') {
              line.coords[1].x = initialLineWidth;
            }
                // else if (this.elementAccess === 'top-tire') {
                //   line.coords[0].y = 0;
                //   line.coords[1].y = initialLineHeight;
            // }
            else {
              line.coords[1].y = initialLineHeight;
            }
          }
        }
        this.$store.dispatch('defineActiveTemplate', val);
      },
    },
    errors: {
      immediate: true,
      handler(val) {
        if (val.length > 0) {
          setTimeout(() => this.errors.shift(), 2000);
        }
      },
    },
    elementAccess(current, old) {
      let newCoords = null;
      if (current === 'between-tire') {
        newCoords = [
          {'x': 0, 'y': this.SVGParams.height / 2},
          {
            'x': this.SVGParams.width,
            'y': this.SVGParams.height / 2,
          },
        ];
        this.hasLine = true;
        this.templateData.elements.map(el => this.putOnLine(el));
      } else {
        newCoords = [
          {'x': this.SVGParams.width / 2, 'y': 0},
          {
            'x': this.SVGParams.width / 2,
            'y': this.SVGParams.height,
          },
        ];
        this.templateData.elements.map(el => {
          this.putOnLine(el)
          if (el.rotate?.horizontal === 1) {
            el.coords.map(coord => {
              coord.x = ((this.SVGParams.width / 2) - coord.x) + (this.SVGParams.width / 2);
            })
          }
        });
      }
      let line = this.templateData.elements.find(el => el.type === 'line');
      if (line) {
        line.coords = newCoords;
      } else {
        this.addLine();
        this.templateData.elements.find(el => el.type === 'line').coords = newCoords;
      }
      this.hasLine = true;
      this.templateData.elements.map(el => {
        let tool = null
        this.tools.find(item => {
          if (item.tools) {
            item.tools.map(childTool => {
              if (childTool.name === el.elementName) {
                tool = childTool
              }
            })
          } else if (el.type === 'junction' && item.type === el.type) {
            tool = item
          } else if (item.name === el.elementName) {
            tool = item
          }
        });
        if (el.type !== 'line') {
          if (!tool?.enableAccess.includes(this.elementAccess)) {
            const index = this.templateData.elements.indexOf(el);
            this.templateData.elements.splice(index, 1);
          } else {
            if (current === 'between-tire' && old !== 'between-tire') {
              el.coords.map(coord => coord.x = coord.y);
            } else if (current !== 'between-tire' && old === 'between-tire') {
              el.coords.map(coord => coord.y = coord.x);
            }
            // if (el.type === 'junction') {
            //   el.coords[0].y = Math.abs(this.SVGParams.height - (el.coords[0].y + el.lineHeight))
            // } else {
            //   el.coords[0].y = Math.abs(this.SVGParams.height - (el.coords[0].y + el.height))
            // }
          }
        }
        this.changeAccess(el);
      });
      if (this.templateData.elements?.length > 1) {
        this.templateData.elements.sort(this.sortByCoord);
      }
    },
  },
  methods: {
    ...mapActions(['defineActiveTemplate']),
    addLine() {
      const line = this.initialTemplateData.elements?.find(item => item.type === 'line');
      if (!line) {
        let line = {
          id: this.generateId(),
          type: 'line',
          color: '',
          interactable: false,
          disableSelection: true,
          coords: [
            {'x': this.SVGParams.width / 2, 'y': 0},
            {
              'x': this.SVGParams.width / 2,
              'y': this.SVGParams.height,
            },
          ],
        };
        this.changeAccess(line);
        this.templateData.elements.unshift(line);
      }
    },
    putOnLine(data) {
      this.tools.map(tool => {
        if (data.type === 'element') {
          if (tool.tools) {
            tool.tools.map(childTool => {
              if (childTool.name === data.elementName) {
                if (this.elementAccess === 'between-tire') {
                  data.coords.map(coord => {
                    coord.y = childTool.positionY;
                  });
                } else {
                  data.coords.map(coord => {
                    if (data.rotate?.horizontal === 1) {
                      coord.x = ((this.SVGParams.width / 2) - childTool.positionX) + (this.SVGParams.width / 2);
                    } else {
                      coord.x = childTool.positionX;
                    }
                  });
                }
              }
            })
          } else if (tool.name === data.elementName) {
            if (this.elementAccess === 'between-tire') {
              data.coords.map(coord => {
                coord.y = tool.positionY;
              });
            } else {
              data.coords.map(coord => {
                if (data.rotate?.horizontal === 1) {
                  coord.x = ((this.SVGParams.width / 2) - tool.positionX) + (this.SVGParams.width / 2);
                } else {
                  coord.x = tool.positionX;
                }
              });
            }
          }
        } else {
          if (data.type === tool.type) {
            if (this.elementAccess === 'between-tire') {
              data.coords.map(coord => {
                coord.y = tool.positionY;
              });
            } else {
              data.coords.map(coord => {
                if (data.rotate?.horizontal === 1) {
                  coord.x = ((this.SVGParams.width / 2) - tool.positionX) + (this.SVGParams.width / 2);
                } else {
                  coord.x = tool.positionX;
                }
              });
            }
          }
        }
      });
    },
    changeData(data) {
      let element = this.templateData.elements.find(el => el.id === data.id);
      if (element) {
        element[data.field] = data.value;
        this.putOnLine(element);
      }
      if (this.templateData.elements?.length > 1) {
        this.templateData.elements?.sort(this.sortByCoord);
      }
    },
    sortByCoord(a, b) {
      if (a.type !== 'line' && b.type !== 'line') {
        return this.elementAccess === 'between-tire' ? a.coords[0].x - b.coords[0].x : a.coords[0].y - b.coords[0].y;
      }
    },
    changeElement(value, field) {
      const data = {id: this.selectedElement.id, field, value};
      this.templateData.elements.find(el => el.id === data.id)[data.field] = data.value;

      this.templateData.elements.map(el => {
        this.tools.map(tool => {
          if (tool.tools) {
            tool.tools.map(childTool => {
              if (el?.rotate !== undefined) {
                if (el.rotate.horizontal === 1) {
                  el.coords.map(coord => {
                    if (this.elementAccess !== 'between-tire') {
                      coord.x = ((this.SVGParams.width / 2) - childTool.positionX) + (this.SVGParams.width / 2);
                    }
                  });
                } else {
                  this.putOnLine(el);
                }
              }
            })
          } else {
            if (el?.rotate !== undefined) {
              if (el.rotate.horizontal === 1) {
                el.coords.map(coord => {
                  if (this.elementAccess !== 'between-tire') {
                    coord.x = ((this.SVGParams.width / 2) - tool.positionX) + (this.SVGParams.width / 2);
                  }
                });
              } else {
                this.putOnLine(el);
              }
            }
          }
        })
      })
    },
    changeTemplate(field, value) {
      this.templateData[field] = value;
      this.elementAccess = this.templateData.access;
    },
    changeAccess(item) {
      item.color = this.currentColor
      item.inverted = this.elementAccess === 'between-tire';
      // item.inverted180 = this.elementAccess === 'top-tire'
    },
    deleteElement() {
      if (this.templateData.elements) {
        const i = this.templateData.elements.map(item => item.id).indexOf(this.selectedElement.id);
        i >= 0 && this.templateData.elements.splice(i, 1);
        if (this.templateData.elements?.length > 1) {
          this.templateData.elements?.sort(this.sortByCoord);
        }
      }
      this.closeDeleteModal()
    },
    addElement(data) {
      this.tools.map(el => {
        if (el.tools) {
          el.tools.map(tool => {
            this.putOnLine(data);
            if (tool.name === data.elementName) {
              data.rotate = {
                horizontal: 0,
                vertical: 0,
              };
              data.height = tool.height ? tool.height : 0
              if (tool.disableRotateHorizontal) delete data.rotate?.horizontal;
              if (tool.disableRotateVertical) delete data.rotate?.vertical;
              if (tool.cutLine) data.cutLine = true;
              if (tool.hidable) data.hidden = false;
              if (tool.adminName) data.adminName = '';
              if (tool.switchable) data.normalOpen = false;
              if (tool.switchable) data.switchElement = false;
              if (tool.markKey) data.mark = {key: tool.markKey, value: null};
              if (tool.rotatePoint) data.rotatePoint = tool.rotatePoint;
              if (tool.horizontal) data.horizontal = true;
              if (tool.class) data.class = tool.class.charAt(0).toUpperCase() + tool.class.slice(1);
              if (tool.terminalsCount) {
                data.terminals = [];
                for (let i = 1; i <= tool.terminalsCount; i++) {
                  data.terminals.push({
                    id: this.generateId(),
                    number: i,
                  });
                }
              }
              data.additionalEquipment = {
                elements: [],
                key: tool.markKey
              };
            }
          })
        } else {
          let tool = el
          this.putOnLine(data);
          if (tool.name === data.elementName) {
            data.rotate = {
              horizontal: 0,
              vertical: 0,
            };
            data.height = tool.height ? tool.height : 0
            if (tool.disableRotateHorizontal) delete data.rotate?.horizontal;
            if (tool.disableRotateVertical) delete data.rotate?.vertical;
            if (tool.cutLine) data.cutLine = true;
            if (tool.hidable) data.hidden = false;
            if (tool.adminName) data.adminName = '';
            if (tool.switchable) data.normalOpen = false;
            if (tool.switchable) data.switchElement = false;
            if (tool.markKey) data.mark = {key: tool.markKey, value: null};
            if (tool.rotatePoint) data.rotatePoint = tool.rotatePoint;
            if (tool.horizontal) data.horizontal = true;
            if (tool.class) data.class = tool.class.charAt(0).toUpperCase() + tool.class.slice(1);
            if (tool.terminalsCount) {
              data.terminals = [];
              for (let i = 1; i <= tool.terminalsCount; i++) {
                data.terminals.push({
                  id: this.generateId(),
                  number: i,
                });
              }
            }
            data.additionalEquipment = {
              elements: [],
              key: tool.markKey
            };
          }
        }

      });
      if (data.type === 'junction') {
        data.lineHeight = 30;
        let tool = this.tools.find(tool => tool.type === 'junction');
        data.class = tool.class.charAt(0).toUpperCase() + tool.class.slice(1);
      }
      data.textOptions.fontSize = 11;
      this.changeAccess(data);
      data.gridDisabled = false;
      this.templateData.elements.push(data);
      if (this.templateData.elements?.length > 1) {
        this.templateData.elements?.sort(this.sortByCoord);
      }
      this.$store.dispatch('selectElement', data);
      this.clearTool();
    },
    saveAsNew() {
      this.saveElement((template) => {
        const result = {
          ...template,
          id: this.generateId(),
        };
        this.$store.dispatch('addTemplateElement', result)
        this.$eventBus.$emit('saveTemplateElement', template)
      })
    },
    saveElement(funSave) {
      if (this.elementName === '') {
        this.errors.push('Заполните название элемента');
      } else if (this.elementAccess === '') {
        this.errors.push('Выберите допуск элемента');
      } else {
        funSave(this.changeCoordinate({
          ...this.templateData,
          name: this.elementName,
          access: this.elementAccess,
          hasLine: this.hasLine,
        }),)
        let modal = document.querySelector(`.modal.${this.method}`);
        modal.classList.remove('show');
        this.clearElement();
      }

      this.$nextTick(() => {
        fetch(`/api/substation/schemeTemplate`, {
          method: 'PUT',
          body: JSON.stringify({
            templatesList: this.allState.templatesList,
          }),
        });
      });
    },
    hideModal() {
      if (this.method !== 'add') {
        this.addTemplateElement(
            this.changeCoordinate({
              ...this.templateData,
              name: this.elementName,
              access: this.elementAccess,
              hasLine: this.hasLine,
            }),
        );
      }
      this.clearElement();
      this.clearTool();
      let modal = document.querySelector(`.modal.${this.method}`);
      modal.classList.remove('show');
    },
    clearTool() {
      this.activeTool = '';
    },
    changeCoordinate(template) {
      if (template.access === 'between-tire') {
        template.elements.map((element) => {
          element.coords.map((coord) => {
            coord.y -= 80;
          });
        });
      } else {
        template.elements.map((element) => {
          element.coords.map((coord) => {
            coord.x -= 70;
          });
          // element.inverted180 = this.elementAccess === 'top-tire'
          // if (template.access === 'top-tire') {
          //   if (element.type === 'line') {
          //     element.coords[0].y = this.SVGParams.height - element.coords[1].y
          //     element.coords[1].y = this.SVGParams.height
          //   } else if (element.type === 'junction') {
          //     element.coords[0].y = this.SVGParams.height - (element.coords[0].y + element.lineHeight)
          //   } else {
          //     element.coords[0].y = this.SVGParams.height - (element.coords[0].y + element.height)
          //     element.inverted180 = this.elementAccess === 'top-tire'
          //   }
          // }
        });
      }
      return template;
    },
    clearElement() {
      if (this.initialTemplateData && Object.keys(this.initialTemplateData).length > 0) {
        this.elementName = JSON.parse(JSON.stringify(this.initialTemplateData.name));
        this.elementAccess = JSON.parse(JSON.stringify(this.initialTemplateData.access));
        this.hasLine = JSON.parse(JSON.stringify(this.initialTemplateData.hasLine));
        this.templateData = JSON.parse(JSON.stringify(this.initialTemplateData));
        this.defineActiveTemplate(this.templateData);
        const line = this.templateData.elements?.find(item => item.type === 'line');
        if (this.hasLine) {
          if (!line) {
            let line = {
              id: this.generateId(),
              type: 'line',
              color: '',
              interactable: false,
              disableSelection: true,
              coords: [
                {'x': this.SVGParams.width / 2, 'y': 0},
                {
                  'x': this.SVGParams.width / 2,
                  'y': this.SVGParams.height,
                },
              ],
            };
            this.changeAccess(line);
            this.templateData.elements.unshift(line);
          }
        }
      }
    },
    createElement(element, type = '') {
      if (this.activeTool === '') {
        if (type !== '') {
          this.activeTool = type + ' ' + element;
        } else {
          this.activeTool = element;
        }
      } else {
        this.clearTool();
      }
    },
    closeDeleteModal() {
      document.querySelector(`#deleteModalElement-${this.method}`).classList.remove('show')
    },
  },
  mounted() {
    this.$eventBus.$on(`deleteElementModal-${this.method}`, () => {
      document.querySelector(`#deleteModalElement-${this.method}`).classList.add('show')
    })
  },
};
</script>

<style scoped>
.modal.fade.modal-blue {
  display: block;
  visibility: hidden;
  opacity: 0;
  overflow: scroll;
  margin: 0;
  width: 100%;
  height: 100%;
}

.modal.fade.modal-blue:before {
  content: '';
  position: absolute;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  background: rgba(0, 0, 0, 0.5);
  opacity: 1;
  transition: all 0.5s;
  -webkit-transition: all 0.5s;
  -moz-transition: all 0.5s;
}

.modal.fade.modal-blue::-webkit-scrollbar {
  display: none;
}

.modal.fade.modal-blue.show {
  visibility: visible;
  opacity: 1;
}

.template_svg_area {
  max-width: 360px;
  max-height: 380px;
  margin-top: 20px;
}

.modal_toolbar {
  margin: 20px 5px 0 0;
  max-width: 90px;
  max-height: 300px;
  /*overflow-y: scroll;*/
  /*overflow-x: hidden;*/
}

/*.modal_toolbar::-webkit-scrollbar {*/
/*  width: 4px;*/
/*}*/

/*.modal_toolbar::-webkit-scrollbar-thumb {*/
/*  background: #273661;*/
/*  border-radius: 10px;*/
/*}*/

.modal_sidebar_wrapper {
  width: 40%;
}

.template_values {
  max-width: 395px;
  width: 100%;
  padding-top: 10px;
  justify-content: space-between;
}

.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input {
  display: none;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #465fb9;
}

input:focus + .slider {
  box-shadow: 0 0 1px #465fb9;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}

.delete_btn {
  margin-top: 20px;
}
</style>
