<template>
  <polygon
      :ref="itemData.id"
      :class="itemData.interactable ? 'editable' : ''"
      :stroke="selected ? '#00DBFF' : currentColor"
      stroke-width="1"
      stroke-linejoin="round"
      fill="none"
      :points="`${itemData.coords[0].x},${itemData.coords[0].y}
             ${itemData.coords[1].x},${itemData.coords[1].y}`"/>
</template>

<script>
import manipulate from '../../../mixins/manipulate';

export default {
  name: 'svg-line',
  mixins: [manipulate],
  data() {
    return {
      width: 0,
      height: 0,
    };
  },
  methods: {
    onMovePoint({x, y, target, index}) {
      let point = this.$el.points.getItem(index)

      point.x += x
      point.y += y
      target.x.baseVal.value = point.x;
      target.y.baseVal.value = point.y;
    }
  },
  props: ['itemData', 'selected', 'instance'],
  computed: {
    currentColor() {
      if (this.itemData.color) {
        return this.itemData.color;
      } else {
        return 'white';
      }
    },
  },
  mounted() {
    this.width = this.$refs[this.itemData.id].getBBox().width;
    this.height = this.$refs[this.itemData.id].getBBox().height;
    this.instance.$on('move', (data) => {
      this.$emit('moveElement', data);
    });
    this.instance.$on('move-end', (data) => {
      this.$emit('saveTransform', data);
    });
    this.instance.$on('move-point', this.onMovePoint);
  },
};
</script>
