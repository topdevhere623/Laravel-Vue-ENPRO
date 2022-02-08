<template>
  <div class="form-field text-options">
    <template v-for="(field, index) in fields">
      <template v-if="field.fieldParams">
        <component
            @change="change"
            :is="field.fieldParams.fieldType"
            :options="field.fieldParams.options"
            :id="field.key"
            :value="field.value"
            :name="field.fieldParams.name"
            :limits="field.fieldParams.limits"
        />
      </template>
    </template>
  </div>
</template>
<script>

import switcher from './switcher';
import counter from "./counter";

export default {
  name: 'text-field',
  data() {
    return ({
      fieldValues: this.value,
      fieldsData: [
        {
          name: 'alignment',
          fieldType: 'switcher',
          options: [{
            name: 'start',
            icon: 'align-left',
            label: 'По левому краю',
          }, {
            name: 'middle',
            icon: 'align-center',
            label: 'По центру',
          }, {
            name: 'end',
            icon: 'align-right',
            label: 'По правому краю',
          }],
        }, {
          name: 'orientation',
          fieldType: 'switcher',
          options: [{
            name: 'horizontal',
            icon: 'text-horizontal',
            label: 'Горизонтальный ',
          }, {
            name: 'vertical',
            icon: 'text-vertical',
            label: 'Вертикальный',
          }],
        }, {
          name: 'fontSize',
          fieldType: 'counter',
          limits: {
            min: 8,
            max: 24,
          }
        },
      ],
    });
  },
  components: {
    switcher,
    counter
  },
  props: ['value', 'label', 'id'],
  computed: {
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
    },
  },
  methods: {
    change(field, value) {
      this.fieldValues[field] = value;
      this.$emit('change', this.fieldValues, 'textOptions');
    },
  },
  created() {

  },
  mounted() {

  },
};
</script>
