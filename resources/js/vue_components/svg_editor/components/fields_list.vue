<template>
  <div>
    <h3>Параметры элемента</h3>
    <template v-for="(field, index) in fieldsList">
      <template v-if="field.fieldParams">
        <component
            @change="change"
            :is="field.fieldParams.fieldType"
            :label="field.fieldParams.label"
            :value="field.value"
            :id="field.key"
            :name="field.fieldParams.name"
            :input-type="field.fieldParams.inputType"
            :options="field.fieldParams.options"
            :loading="field.fieldParams.isLoading"
            :searchable="field.fieldParams.searchable"
            v-if="checkCondition(field)"
        />
      </template>
    </template>
  </div>
</template>

<script>
import text from '../fields/text';
import input from '../fields/input';
import dependedElement from '../fields/dependedElement';
import textOptions from '../fields/textOptions';
import elementRotate from '../fields/elementRotate';
import switchElement from '../fields/switchElement';
import select from '../fields/select';
import {mapGetters} from 'vuex';
import textArea from "../fields/textarea";
import transformerPhase from "../fields/transformerPhase";
import additionalEquipment from "../fields/additionalEquipment";

export default {
  name: 'fields_list',
  props: ['selectedElementData', 'change'],
  components: {
    'text-read': text,
    'text-field': input,
    'depended-element': dependedElement,
    'text-options': textOptions,
    'rotate': elementRotate,
    'switch-element': switchElement,
    'custom-select': select,
    'text-area': textArea,
    'transformer-phase': transformerPhase,
    'additional-equipment': additionalEquipment,
  },
  watch: {
    selectedElementData: {
      immediate: true,
      deep: true,
      handler(val) {
        if (val.type === 'transformer') {
          let free_connection = {
            name: 'free_connection',
            label: 'Свободное подключение',
            fieldType: 'depended-element',
          }
          let isset = !!this.fieldsData.find(el => el.name === free_connection?.name)
          if (val.phase_count.value === '3' && !isset) {
            this.fieldsData.push(free_connection)
          } else if (val.phase_count.value !== '3' && isset) {
            this.fieldsData = this.fieldsData.filter(el => el.name !== 'free_connection')
          }
        }
      }
    }
  },
  data() {
    return {
      fieldsData: [
        {
          name: 'id',
          label: 'ID',
          fieldType: 'text-read',
        }, {
          name: 'value',
          label: 'Текст',
          fieldType: 'text-area',
        }, {
          name: 'index',
          label: 'Номер соединения',
          fieldType: 'text-field',
          dependency: {
            field: 'currentTireID',
            value: true
          }
        }, {
          name: 'index2',
          label: 'Номер соединения',
          fieldType: 'text-field',
        }, {
          name: 'type',
          label: 'Тип элемента',
          fieldType: 'text-read',
        }, {
          name: 'income',
          label: 'Элемент входа',
          fieldType: 'depended-element',
        }, {
          name: 'outcome',
          label: 'Элемент выхода',
          fieldType: 'depended-element',
        }, {
          name: 'caption',
          label: 'Подпись',
          fieldType: 'text-area',
        }, {
          name: 'mark',
          label: 'Марка',
          fieldType: 'custom-select',
          options: [],
          dataFetchable: true,
          isLoading: false,
          searchable: true
        },
        {
          name: 'voltageLevel',
          label: 'Класс напряжения',
          fieldType: 'custom-select',
          options: [],
          dataFetchable: true,
          isLoading: false,
          dependency: {
            field: 'currentTireID',
            value: false
          }
        },
        {
          name: 'textOptions',
          label: 'Параметры текста',
          fieldType: 'text-options',
        }, {
          name: 'rotate',
          label: 'Положение компонента',
          fieldType: 'rotate',
        }, {
          name: 'normalOpen',
          label: 'Норм. состояние',
          fieldType: 'switch-element',
        }, {
          name: 'switchElement',
          label: 'Тек. состояние',
          fieldType: 'switch-element',
        }, {
          name: 'hidden',
          label: 'Скрыто на схеме',
          fieldType: 'switch-element',
        }, {
          name: 'adminName',
          label: 'Диспетчерское наименование',
          fieldType: 'text-field',
        }, {
          name: 'lineHeight',
          label: 'Длина линии',
          fieldType: 'text-field',
          inputType: 'number',
        }, {
          name: 'additionalEquipment',
          label: 'Дополнительное оборудование',
          fieldType: 'additional-equipment',
        },
        // {
        //   name: 'phase_count',
        //   label: 'Тип',
        //   fieldType: 'custom-select',
        //   options: [
        //     {
        //       label: 'двухобмоточный',
        //       id: 2
        //     },
        //     {
        //       label: 'трехобмоточный',
        //       id: 3
        //     }
        //   ]
        // },
      ],
    };
  },
  computed: {
    ...mapGetters(['modelsList', 'findById']),
    valueID() {
      return this.value?.id
    },
    fieldsList() {
      return Object.entries(this.selectedElementData).map(el => {
        return {
          key: el[0],
          value: el[1],
          fieldParams: this.fieldsData.find(obj => {
            return obj.name === el[0];
          }),
        };
      });
    },
  },
  methods: {
    checkCondition(field) {
      if (field.fieldParams.dependency) {
        const depElement = this.fieldsList.find(el => el.key === field.fieldParams.dependency.field);
        if (depElement) {
          return depElement.value === field.fieldParams.dependency.value || !!depElement.value === !!field.fieldParams.dependency.value;
        }
      }
      return true
    },
  },
  mounted() {
    let dataFetchable = this.fieldsData.filter(obj => obj.dataFetchable)
    dataFetchable.map(obj => {
      let value = this.selectedElementData[obj.name]
      if (value) {
        if (!this.modelsList[value.key]) {
          obj.isLoading = true
          this.$store.dispatch('fetchModelsList', value.key).then(() => {
            this.$set(obj, 'options', this.modelsList[value.key] || [])
            obj.isLoading = false
          })
        } else {
          this.$set(obj, 'options', this.modelsList[value.key] || [])
        }
      }
    })
  },
};
</script>

<style scoped>

</style>
