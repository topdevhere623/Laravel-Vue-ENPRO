<template>
  <div class="switcher">
    <button :disabled="currentValue >= limits.max" @click="plus" class="item">+</button>
    <div class="item active">{{ currentValue }}</div>
    <button :disabled="currentValue <= limits.min" @click="minus" class="item">-</button>
  </div>
</template>

<script>
export default {
  name: "counter",
  data() {
    return ({
      currentValue: 0,
    });
  },
  watch: {
    value: {
      immediate: true,
      handler(val) {
        this.currentValue = val
      }
    }
  },
  props: ['value', 'label', 'id', 'options', 'name', 'limits'],
  methods: {
    plus() {
      this.$emit('change', this.name, this.currentValue + 1);
    },
    minus() {
      this.$emit('change', this.name, this.currentValue - 1);
    }
  }
}
</script>

<style scoped>
.item {
  border: none;
  outline: none;
}

</style>