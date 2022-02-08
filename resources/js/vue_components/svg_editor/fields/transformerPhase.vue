<template>
  <div class="form-field" :name="name">
    <select class="text-field" @change="onInput">
      <option v-for="item in options" :value="item.value" :selected="value === item.value">
        {{ item.label }}
      </option>
    </select>
    <div class="form-input-label">{{ label }}</div>
  </div>
</template>

<script>
  export default {
    name: "transformerPhase",
    props: ['value', 'label', 'id', 'name'],
    data() {
      return {
        options: [

        ]
      }
    },
    methods: {
      onInput(e) {
        this.$emit('change', parseInt(e.target.value), 'phase_count');
      },
    }
  }
</script>

<style scoped>

</style>