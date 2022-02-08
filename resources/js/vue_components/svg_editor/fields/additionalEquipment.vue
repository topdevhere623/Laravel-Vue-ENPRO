<template>
  <div class="form-field coords additional-equipment mb-15 d-flex flex-column">
    <div class="caption">{{ label }}:</div>
    <template v-for="(group) in elements">
      <div class="group">
        <template v-for="(field) in group.fields">
          <template v-if="field.fieldParams">
            <component
              @change="change"
              @deleteField="deleteElement"
              :is="field.fieldParams.fieldType"
              :label="field.fieldParams.label"
              :id="field.key"
              :value="field.value"
              :name="field.fieldParams.name"
              :field-id="group.fields[0].value || null"
              :options="modelsList[value.key] || []"
              :loading="!modelsList[value.key]"
            />
          </template>
        </template>
      </div>
    </template>
    <button @click="addElement" class="button small mt-10">
      <span class="icon icon-add"></span>
      <span>Добавить оборудование</span>
    </button>
  </div>
</template>
<script>

  import {mapGetters} from "vuex";
  import input from "./input";
  import select from "./select";
  import common from '../../../mixins/common';

  export default {
    name: 'additional-equipment',
    mixins: [common],
    data() {
      return ({
        active: false,
        fieldsData: [
          {
            name: 'name',
            label: 'Наименование',
            fieldType: 'text-field'
          }, {
            name: 'mark',
            label: 'Марка',
            fieldType: 'custom-select',
            options: [],
            dataFetchable: true,
            isLoading: false,
          }
        ]
      })
    },
    components: {
      'text-field': input,
      'custom-select': select
    },
    props: ["value", "label", "name", 'loading'],
    computed: {
      ...mapGetters(['elementsList', 'modelsList', 'selectedElement', 'selectedElementData', 'waitSelect', 'findById']),
      valueID() {
        return JSON.parse(JSON.stringify(this.value?.id))
      },
      elements() {
        const value = JSON.parse(JSON.stringify(this.value));
        const fields = [];
        value.elements.forEach(el => {
          el.fields = Object.entries(el).map(el => {
            return {
              key: el[0],
              value: el[1],
              fieldParams: this.fieldsData.find(obj => {
                return obj.name === el[0];
              }),
            };
          });
          fields.push(el);
        });

        return fields;
      },
    },
    methods: {
      change(value, field) {
        const val = JSON.parse(JSON.stringify(this.value))
        if (value.id) {
          let i = val.elements.indexOf(val.elements?.find(el => el.id === value.id))
          val.elements[i][field] = value.value
        } else {
          val[field] = value;
        }
        this.$emit('change', val, this.name)
      },
      addElement() {
        const elements = this.value?.elements
        elements.push({id: this.generateId(), name: '', mark: {value: null}});
        this.change(elements, 'elements');
      },
      deleteElement(id) {
        const elements = this.value?.elements
        this.change(elements.filter(el => el.id !== id), 'elements');
      }
    },
  }
</script>
