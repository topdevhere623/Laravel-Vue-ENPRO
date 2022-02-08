<template>
  <div class="form-field">
    <input :type="inputType ? inputType : 'text'" :value="value" @input="onInput($event.target.value)"
           class="text-field">
    <div class="form-input-label">{{ label }}</div>
    <button @click="$emit('deleteField', fieldId)" v-if="fieldId" type="button"
            class="button position-absolute field-delete-btn">
      <span class="icon icon-close mr-0"></span>
    </button>
  </div>
</template>
<script>

  export default {
    name: 'text-field',
    watch: {},
    props: ['value', 'label', 'id', 'inputType', 'fieldId'],
    computed: {},
    methods: {
      onInput(value) {
        if (this.fieldId) {
          this.$emit('change', {
            id: this.fieldId,
            value: value
          }, this.id);
        } else {
          this.$emit('change', value, this.id);
        }
      },
    },
  };
</script>
<style scoped>
.field-delete-btn {
  width: 50px;
  padding: 0;
  right: 0;
}
</style>
