<template>
  <div>
    <div v-if="isLoading">
      <img
        src="/public/uploads/loading.gif"
        style="
          width: 150px;
          position: fixed;
          margin: auto;
          top: 0;
          bottom: 0;
          left: 0;
          right: 0;
          z-index: 9999;
        "
        alt=""
      />
    </div>
    <svg_area
      :cellSize="cellSize"
      :s-v-g-params="SVGParams"
      :elements-list="elementsList"
      :add-element="addElement"
      :data="data"
      :changeData="changeData"
      :active-tool="activeTool"
      class="main_svg_area"
      tabindex="-1"
    />
    <div class="tools">
      <editor_toolbar class="base-elements" :tools="tools" :active-tool="activeTool"
                      :create-element="createElement"/>
      <div class="position-relative">
        <div @click="templatesListActive = !templatesListActive" class="other-button"
             title="Пользовательские элементы">
          <svg class="icon">
            <use :xlink:href="`/public/assets/backend/icons/sprite.svg#dots`"></use>
          </svg>
          <div class="badge" style="z-index: 3;">{{ templatesList.length }}</div>
        </div>
        <div tabindex="0" @blur="templatesListActive = false" class="templates_list d-flex"
             :class="templatesListActive && 'active'">
          <template
            v-if="templatesList.length > 0"
          >
            <div
              v-for="(value, name) in orderedTemplates"
            >
              <div
                class="d-flex flex-column align-items-center px-1"
                :style="Object.keys(orderedTemplates).indexOf(name) < Object.keys(orderedTemplates).length - 1 && `border-right: #CDCDCD solid 1px;`"
              >
                <h5 class="my-1">{{ value.title }}</h5>
                <div
                  v-for="tool in value.items"
                  class="template_tool"
                  :style="activeTool === tool.id ? 'border: solid white 2px' : 'border: solid transparent 2px'"
                >
                  <div @click="createElement(tool.id)"
                       style="white-space: nowrap; width: 100px; overflow: hidden; text-overflow: ellipsis;">
                    {{ tool.name }}
                  </div>
                  <div class="buttons">
                    <div
                      class="badge-button"
                      data-toggle="modal"
                      @click="showModal('editTemplate', tool)"
                    >
                      <i class="fas fa-pencil"></i>
                    </div>
                    <div
                      data-toggle="modal"
                      data-target="#deleteModal"
                      class="badge-button"
                      @click="selectedTemplate = tool"
                    >
                      <i class="fas fa-close"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </template>
          <template v-else>
            Нет пользовательских элементов
          </template>
        </div>
      </div>
      <div class="add-button"
           @click="!isLoading && showModal('add')"
           title="Добавить Элемент">
        <svg class="icon">
          <use :xlink:href="`/public/assets/backend/icons/sprite.svg#add`"></use>
        </svg>
      </div>
    </div>
    <div
      id="deleteModal"
      role="dialog"
      class="modal modal-blue fade"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            Вы действительно хотите удалить?
          </div>
          <div style="background-color: rgb(32, 35, 70);" class="modal-footer">
            <button
              type="button"
              data-dismiss="modal"
              @click="deleteElement(selectedTemplate.id)"
              class="btn btn-info waves-effect waves-classic">
              Подтвердтить
            </button>
            <button
              data-dismiss="modal"
              type="button"
              id="closeModal"
              class="btn btn-info waves-effect waves-classic">
              Закрыть
            </button>
          </div>
        </div>
      </div>
    </div>
    <element_modal
      method="editTemplate"
      :initial-template-data="selectedTemplate"
      :data="data"
      :addTemplateElement="saveTemplate"
    />
    <element_modal
      method="add"
      :initial-template-data="initialTemplateData"
      :data="data"
      :add-template-element="addTemplate"
    />
    <sidebar :data="data"/>
  </div>
</template>

<script>


  import sidebar from './svg_editor/sidebar';
  import {mapActions, mapGetters} from 'vuex';
  import del from '../mixins/delete.js';
  import common from '../mixins/common';
  import element_modal from './svg_editor/element_modal.vue';
  import manipulate from "../mixins/manipulate";


  export default {
    mixins: [del, common, manipulate],
    components: {
      'sidebar': sidebar,
      element_modal,
    },
    props: ['data'],
    computed: {
      ...mapGetters(['elementsList', 'templatesList', 'selectedElementData', 'allState']),
      orderedTemplates() {
        let access = [{key: 'top-tire', title: 'ВН'}, {key: 'bottom-tire', title: 'НН'}, {
          key: 'between-tire',
          title: 'МН'
        }]
        let result = {}
        access.map(item => {
          let toolsList = []
          this.templatesList.map(template => {
            if (item.key === template.access) {
              toolsList.push(template)
            }
          })
          if (toolsList.length > 0) {
            result[item.key] = {}
            result[item.key]['title'] = item.title
            result[item.key]['items'] = toolsList
          }
        })
        return result
      },
    },
    mounted() {
      this.isLoading = true
      fetch(`/api/substation/schemeTemplate`).then(res => res.json()).then(schemeTemplate => {
        fetch(`/api/substation/scheme/${pageData.id}`).then(res => res.json()).then(data => {
          console.log(data)
          if (data.state) {
            if (schemeTemplate.templatesList) {
              data.state.templatesList = schemeTemplate.templatesList;
            }
            this.applyState(data.state);
            toastr.success('Данные загружены')
          } else {
            this.applyTemplates(schemeTemplate.templatesList)
            toastr.success('Данные загружены')
          }
          this.isLoading = false
        })
      });
      let svgArea = document.querySelector('.main_svg_area')
      if (svgArea) {
        svgArea.addEventListener('keydown', (e) => {
          if (e.key === 'Delete' || e.key === 'Backspace') {
            this.triggerDelete(this.selectedElementData.id);
          } else if (e.key === 'Escape') {
            this.$store.dispatch('selectElement', {});
          }
        })
      }
      window.addEventListener('keydown', (e) => {
        if (e.ctrlKey && e.key === 's') {
          e.preventDefault()
          this.$eventBus.$emit('saveScheme')
        }
      });

      this.$eventBus.$on('showLoading', () => this.isLoading = true)
      this.$eventBus.$on('hideLoading', () => this.isLoading = false)
    },
    data() {
      return {
        activeTool: '',
        tools: [
          {
            name: 'line',
            tooltip: 'Линия',
            icon: 'line',
          },
          {
            name: 'rect',
            tooltip: 'Прямоугольник',
            icon: 'rectangle',
          },
          {
            name: 'text',
            tooltip: 'Текст',
            icon: 'text',
          },
          {
            name: 'top-tire',
            tooltip: 'Создать секцию высокого напряжения',
            icon: 'top-tire',
          },
          {
            name: 'bottom-tire',
            tooltip: 'Создать секцию низкого напряжения',
            icon: 'bottom-tire',
          },
          {
            name: 'transformer',
            tooltip: 'Трансформатор',
            icon: 'transformer',
          },
          // {
          //   name: 'three_phase_transformer',
          //   tooltip: 'Трёхобмоточный трансформатор',
          //   icon: 'three-phase-transformer',
          // },
        ],
        cellSize: 20,
        SVGParams: {
          width: 1600,
          height: 1020,
          realWidth: 0,
          realHeight: 0,
        },
        initialTemplateData: {
          id: '',
          name: '',
          mark: '',
          hasLine: true,
          access: 'top-tire',
          elements: [],
        },
        selectedTemplate: {},
        templatesListActive: false,
        isLoading: false,
      };
    },
    methods: {
      ...mapActions(['applyState', 'applyTemplates', 'addTemplateElement', 'deleteTemplateElement']),
      deleteElement(id) {
        this.deleteTemplateElement(id);

        this.$nextTick(() => {
          fetch(`/api/substation/schemeTemplate`, {
            method: 'PUT',
            body: JSON.stringify({
              templatesList: this.allState.templatesList
            })
          })
        });
      },
      saveTemplate(data) {
        this.$store.dispatch('changeTemplateElement', data);
      },
      showModal(method, tool = null) {
        let modal = document.querySelector(`.modal.${method}`);
        if (tool) {
          this.selectedTemplate = tool;
          this.changeCoordinate(this.selectedTemplate)
        }
        if (modal) {
          modal.addEventListener('keydown', (e) => {
            if (e.target.tagName !== 'TEXTAREA' && e.target.tagName !== 'INPUT') {
              if (e.key === 'Delete' || e.key === 'Backspace') {
                this.$eventBus.$emit(`deleteElementModal-${method}`)
              } else if (e.key === 'Escape') {
                this.$store.dispatch('selectElement', {});
              }
            }
          })
          modal.classList.add('show');
        }

      },
      addTemplate(data) {
        const template = {
          ...data,
          id: this.generateId(),
        };
        this.addTemplateElement(template);
      },
      addElement(data) {
        this.$store.dispatch('addElement', data).then(() => {
          this.$store.dispatch('selectElement', data);
        });
        this.clearTool();
      },
      clearTool() {
        this.activeTool = '';
      },
      createElement(element, type = '') {
        if (this.activeTool === '') {
          this.activeTool = element;
          this.templatesListActive = false
        } else {
          this.clearTool();
        }
      },
      deleteTemplate(id) {
        this.$store.dispatch('deleteTemplateElement', id)
      },
      changeData(data) {
        this.$store.dispatch('changeData', data);
      }
    },
  };
</script>
<style scoped>
.template-toolbar {
  margin: 0 -5px;
  display: flex;
  flex-wrap: wrap;
}

.template_tool {
  background-color: rgba(255, 255, 255, .1);
  width: max-content;
  height: 30px;
  padding: 0 5px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin: 2px;
  cursor: pointer;
}

.template_tool .buttons {
  display: flex;
  margin-left: 10px;
}

.template_tool .badge-button {
  position: relative;
  width: 20px;
  height: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(255, 255, 255, .1);
  transition: all .3s;
  margin-left: 3px;
}

.template_tool .badge-button:hover {
  background: rgba(255, 255, 255, .2);
}

.button.bordered {
  height: 40px;
  width: max-content;
  display: flex;
  align-items: center;
}

.templates_list {
  background-color: #273661;
  padding: 5px;
  position: absolute;
  left: 42px;
  top: 5px;
  transition: visibility 0.3s linear, opacity 0.3s linear;
  visibility: hidden;
  opacity: 0;
}

.templates_list.active {
  visibility: visible;
  opacity: 1;
}

.modal.fade.modal-blue.show {
  visibility: visible;
  opacity: 1;
}
</style>
