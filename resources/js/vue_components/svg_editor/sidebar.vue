<template>
  <div>
    <div
        class="sidebar-panel settings-sidebar"
        id="element-settings-sidebar"
        :class="selectedElementData ? 'active' : ''"
    >
      <template v-if="selectedElementData">
        <div class="form-group">
          <fields_list :selected-element-data="selectedElementData" :change="change"/>
        </div>
        <div class="form-field">
          <div class="row">
            <div class="col col-12">
              <input type="button" value="Удалить" @click="deleteElement"
                     class="button bordered px-0">
            </div>
            <div v-if="selectedElementData.hasOwnProperty('templateData')" class="col col-12">
              <input
                  class="button px-0"
                  @click="showModal('editElement')"
                  type="button"
                  value="Редактировать"
              >
            </div>
            <div class="col col-12">
              <input type="button" value="Закрыть" @click="close" class="button px-0">
            </div>
          </div>
        </div>
      </template>
    </div>
    <div v-if="Object.keys(selectedTemplateElement).length > 0">
      <element_modal
          method="editElement"
          :initial-template-data="selectedTemplateElement.templateData"
          :data="data"
          :addTemplateElement="saveTemplate"
      />
    </div>
  </div>
</template>
<script>
import {mapGetters} from 'vuex';
import manipulate from '../../mixins/manipulate';
import del from '../../mixins/delete';
import element_modal from './element_modal.vue';
import fields_list from './components/fields_list.vue';

export default {
  name: 'sidebar',
  mixins: [manipulate, del],
  components: {
    element_modal,
    fields_list,
  },
  props: ['data'],
  watch: {
    selectedElementData: {
      immediate: true,
      handler(val) {
        if (val !== undefined) {
          if (val.hasOwnProperty('templateData')) {
            this.selectedTemplateElement = JSON.parse(JSON.stringify(val));
            this.changeCoordinate(this.selectedTemplateElement.templateData);
          }
        } else {
          return {};
        }
      },
    },
  },
  data() {
    return ({
      selectedTemplate: {},
      selectedTemplateElement: {},
    });
  },
  methods: {
    saveTemplate(data) {
      this.$set(this.selectedTemplateElement, 'templateData', data);
      this.$store.dispatch('changeElement', this.selectedTemplateElement);
    },
    deleteElement() {
      this.triggerDelete(this.selectedElement.id);
    },
    change(value, field) {
      const data = {id: this.selectedElement.id, field, value};
      this.$store.dispatch('changeData', data);
    },
    close() {
      this.$store.dispatch('selectElement', {});
      this.$store.dispatch('waitSelectUpdate', {});
    },
    showModal(method) {
      let modal = document.querySelector(`.modal.${method}`);
      modal.classList.add('show');
      modal.addEventListener('keydown', (e) => {
        if (e.target.tagName !== 'TEXTAREA' && e.target.tagName !== 'INPUT') {
          if (e.key === 'Delete' || e.key === 'Backspace') {
            this.$eventBus.$emit(`deleteElementModal-${method}`)
          } else if (e.key === 'Escape') {
            this.$store.dispatch('selectElement', {});
          }
        }
      })
    },
  },
  computed: {
    ...mapGetters(['selectedElement', 'selectedElementData', 'elementsList', 'allState', 'modelsList']),
  },
  mounted() {
    this.$eventBus.$on('saveTemplateElement', (template) => {
      this.$set(this.selectedTemplateElement, 'templateData', template);
      this.$store.dispatch('changeElement', this.selectedTemplateElement);
    })
    this.$eventBus.$on('editTemplateElement', () => {
      this.showModal('editElement')
    })
  }
};
</script>
<style>
.modal.fade.modal-blue.show {
  visibility: visible;
  opacity: 1;
}
</style>
