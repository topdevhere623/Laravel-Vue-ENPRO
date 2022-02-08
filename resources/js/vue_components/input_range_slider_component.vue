<template>
  <div class="input-range-slider">
    <div class="form-group">
      <label for="#nom-min">Номинальное напряжение</label>
      <div class="d-flex r-input-container">
        <input
          class="form-control text-field"
          v-model.number="value.min"
          type="number"
          id="nom-min"
        />
        <input
          v-model.number="value.max"
          class="form-control text-field"
          type="number"
        />
      </div>
      <vue-slider-component
        v-model="Rvalue"
        :min="getRange[0]"
        :max="getRange[1]"
        @change="setValue"
        :format="format"
      ></vue-slider-component>
    </div>
  </div>
</template>
<script>
export default {
  name: "input_range_slider",
  props: {
    getFormat: {
      type: String,
      default: "",
    },
    getRange: {
      type: Array,
      default: [0, 0],
    },
  },
  data() {
    return {
      label: "кВ",
      value: {
        min: this.getRange[0],
        max: this.getRange[1],
      },
      format: {
        suffix: ` ${this.getFormat}`,
        decimals: 0,
      },
      Rvalue: [0, 0],
    };
  },
  methods: {
    setValue(val) {
      this.value.min = val[0];
      this.value.max = val[1];
    },
  },
  watch: {
    "value.min": function (old, nn) {
      deep: true;
      this.$emit("inputRange", this.value);
      this.$set(this.Rvalue, 0, this.value.min);
    },
    "value.max": function (old, nn) {
      deep: true;
      this.$set(this.Rvalue, 1, this.value.max);
      this.$emit("inputRange", this.value);
    },
  },
};
</script>
<style lang="scss" scoped>
.input-range-slider {
  .form-control {
    padding: 10px;
    height: initial;
  }
  .r-input-container {
    column-gap: 15px;
    margin-bottom: 50px;
  }
}
.slider-theme-blue {
  --slider-connect-bg: #3b82f6;
  --slider-tooltip-bg: #3b82f6;
  --slider-handle-ring-color: #3b82f630;
}
</style>
