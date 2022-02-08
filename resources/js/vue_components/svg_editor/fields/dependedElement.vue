<template>
  <div class="form-field coords depended-element">
    <div class="caption">{{ label }}:</div>
    <div>
      <b v-if="!valueID"
         class="button small"
         :class="active ? '' : 'bordered'"
         @click="addWaitSelect">Выбрать</b>
      <b v-else
         class="button small"
         @click="clear">Сбросить</b>
    </div>
    <template v-for="(field, index) in fields">
      <template v-if="field.fieldParams">
        <component
          @change="change"
          :is="field.fieldParams.fieldType"
          :label="field.fieldParams.label"
          :options="field.fieldParams.options"
          :id="field.key"
          :value="field.value"
          :name="field.fieldParams.name"
          v-if="checkCondition(field) && valueID"
        />
      </template>
    </template>
  </div>
</template>
<script>

  import {mapGetters} from "vuex";
  import switcher from "./switcher";
  import input from "./input";
  import select from "./select";

  export default {
    name: 'depended-element',
    data() {
      return ({
        active: false,
        fieldValues: this.value,
        fieldsData: [
          {
            name: 'connection',
            fieldType: 'switcher',
            options: [{
              name: 'tire',
              icon: 'tire-connection',
              label: 'Шинное соединение'
            }, {
              name: 'cable',
              icon: 'line',
              label: 'Кабельное соединение'
            }]
          }, {
            name: 'mark',
            label: 'Марка шины',
            fieldType: 'custom-select',
            dependency: {
              field: 'connection',
              value: 'tire'
            }
          }, {
            name: 'voltage',
            label: 'Базовое напряжение',
            fieldType: 'custom-select',
          }, {
            name: 'length',
            label: 'Длина',
            fieldType: 'text-field',
            dependency: {
              field: 'connection',
              value: 'cable'
            }
          }, {
            name: 'size',
            label: 'Сечение',
            fieldType: 'text-field',
            dependency: {
              field: 'connection',
              value: 'cable'
            }
          }, {
            name: 'markcable',
            label: 'Марка кабеля',
            fieldType: 'custom-select',
            dependency: {
              field: 'connection',
              value: 'cable'
            }
          }
        ]
      })
    },
    watch: {
      waitSelect(current) {
        if (!current.id) {
          this.active = false;
        }
      }
    },
    components: {
      switcher,
      'text-field': input,
      'custom-select': select
    },
    props: ["value", "label", "id", "name"],
    computed: {
      ...mapGetters(['elementsList', 'selectedElement', 'selectedElementData', 'waitSelect', 'findById']),
      valueID() {
        return this.value?.id
      },
      connection() {
        return this.value?.connection
      },
      brand() {
        return this.value?.brand
      },

      fields() {
        return Object.entries(this.value).map(el => {
          return {
            key: el[0],
            value: el[1],
            fieldParams: this.fieldsData.find(obj => {
              return obj.name === el[0];
            }),
          };
        });
      }
    },
    created() {

    },
    mounted() {

    },
    methods: {
      checkCondition(field) {
        if (field.fieldParams.dependency) {
          const depElement = this.fields.find(el => el.key === field.fieldParams.dependency.field);
          if (depElement) {
            return depElement.value === field.fieldParams.dependency.value;
          }
        }
        return true
      },
      change(field, value) {
        this.fieldValues[field] = value;
        this.$emit('change', this.fieldValues, this.name)
      },
      addWaitSelect() {
        const data = {
          id: this.selectedElement.id,
          field: this.name,
          connectedTires: this.selectedElementData.connectedTires
        };
        this.$store.dispatch("waitSelectUpdate", this.active ? {} : data);
        this.active = !this.active;
      },
      clear() {
        const element = this.findById(this.valueID);
        const data = {id: this.valueID, field: 'reverse', value: element.admittance !== 'top-tire'}
        this.$store.dispatch("changeData", data);
        let tires = this.selectedElementData.connectedTires.filter(tire => tire !== element.currentTireID)
        this.$store.dispatch('changeData', {
          id: this.selectedElementData.id,
          field: 'connectedTires',
          value: tires
        })
        this.change('id', null);
      }
    }
  }
</script>
