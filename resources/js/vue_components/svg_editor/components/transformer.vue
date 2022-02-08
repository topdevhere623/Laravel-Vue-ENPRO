<template>
  <g>
    <g :transform="`matrix(1 0 0 1 ${itemData.coords[0].x} ${itemData.coords[0].y})`">
      <g transform="scale(1.25)">
        <circle cy="22.5" r="22.5"
                fill="transparent"
                stroke-width="1"
                :stroke="selected ? '#00DBFF' : '#465fb9'"/>
        <!-- D -->
        <template v-if="itemData.phase_literals && itemData.phase_literals[0]">
          <polygon
              v-if="itemData.phase_literals[0] === 'D'"
              transform="matrix(1 0 0 1 0 20)"
              :stroke="selected ? '#00DBFF' : '#465fb9'"
              points="0,-8 8,8 -8,8"
              fill="transparent"
              stroke-width="1"
          />
          <polygon
              v-if="itemData.phase_literals[0] === 'Y' || itemData.phase_literals[0] === 'Yn' || itemData.phase_literals[0] === 'Z' || itemData.phase_literals[0] === 'Zn'"
              transform="matrix(1 0 0 1 0 22.5)"
              :stroke="selected ? '#00DBFF' : '#465fb9'"
              points="0,0 -8,-8 0,0 8,-8 0,0 0,8 0,0"
              stroke-width="1"
          />
        </template>
        <circle cy="56.5" r="22.5"
                fill="transparent"
                stroke-width="1"
                :stroke="selected ? '#00DBFF' : 'white'"/>
        <template v-if="itemData.phase_literals && itemData.phase_literals[1]">
          <polygon
              v-if="itemData.phase_literals[1] === 'D'"
              transform="matrix(1 0 0 1 0 57.5)"
              :stroke="selected ? '#00DBFF' : 'white'"
              points="0,-8 8,8 -8,8"
              fill="transparent"
              stroke-width="1"/>
          <polygon
              v-if="itemData.phase_literals[1] === 'Y' || itemData.phase_literals[1] === 'Yn' || itemData.phase_literals[1] === 'Z' || itemData.phase_literals[1] === 'Zn'"
              transform="matrix(1 0 0 1 0 57.5)"
              :stroke="selected ? '#00DBFF' : 'white'"
              points="0,0 -8,-8 0,0 8,-8 0,0 0,8 0,0"
              stroke-width="1"/>
        </template>
        <g v-if="itemData.phase_count.value === '3'">
          <circle cy="39.5" cx="22.5" r="22.5"
                  fill="transparent"
                  stroke-width="1"
                  :stroke="selected ? '#00DBFF' : 'white'"/>
          <!-- Y -->
          <template v-if="itemData.phase_literals && itemData.phase_literals[2]">
            <polygon
                v-if="itemData.phase_literals[2] === 'D'"
                transform="matrix(1 0 0 1 22.5 39.5)"
                :stroke="selected ? '#00DBFF' : 'white'"
                points="0,-8 8,8 -8,8"
                fill="transparent"
                stroke-width="1"/>
            <polygon
                v-if="itemData.phase_literals[2] === 'Y' || itemData.phase_literals[2] === 'Yn' || itemData.phase_literals[2] === 'Z' || itemData.phase_literals[2] === 'Zn'"
                transform="matrix(1 0 0 1 22.5 39.5)"
                :stroke="selected ? '#00DBFF' : 'white'"
                points="0,0 -8,-8 0,0 8,-8 0,0 0,8 0,0"
                stroke-width="1"/>
          </template>
        </g>
      </g>
    </g>
  </g>
</template>

<script>
import manipulate from '../../../mixins/manipulate';
import {mapGetters} from 'vuex';

export default {
  name: 'transformer',
  mixins: [manipulate],
  data() {
    return ({
      yPos: 460,
    });
  },
  methods: {},
  watch: {
    mark: {
      deep: true,
      immediate: true,
      handler(val) {
        if (val?.key && val?.value) {
          if (this.modelsList[val.key]) {
            let model = this.modelsList[val.key].find(el => el.id == val.value)
            if (model.TransformerTankInfo) {
              let phase_count = model.TransformerTankInfo.TransformerEndInfo.length
              let phase_literals = model.TransformerTankInfo.TransformerEndInfo.map(el => el.connectionKind?.literal)
              this.$store.dispatch('changeData', {
                id: this.itemData.id,
                field: 'phase_literals',
                value: phase_literals
              });
              if (phase_count === 3) {
                this.$store.dispatch('changeData', {
                  id: this.itemData.id,
                  field: 'phase_count',
                  value: {
                    value: '3',
                    caption: 'трехобмотачный'
                  }
                });
              } else {
                this.$store.dispatch('changeData', {
                  id: this.itemData.id,
                  field: 'phase_count',
                  value: {
                    value: '2',
                    caption: 'двухобмотачный'
                  }
                });
              }
            }
          }
        }
      },
    },
    phaseCount(val) {
      if (val.value !== '3') {
        if (this.itemData['free_connection']) {
          const element = this.findById(this.itemData['free_connection'].id);
          if (!!element) {
            const data = {
              id: this.itemData['free_connection'].id,
              field: 'reverse',
              value: element.admittance !== 'top-tire'
            }
            this.$store.dispatch("changeData", data);
          }
          this.itemData['free_connection'].id = null
          this.itemData['free_connection'].tire = null
          this.$store.dispatch('changeData', {
            id: this.itemData.id,
            field: 'free_connection',
            value: this.itemData['free_connection']
          });
        }
      }
    }
  },
  props: ['itemData', 'selected', 'instance'],
  computed: {
    ...mapGetters(['findById', 'modelsList']),
    mark() {
      return this.itemData.mark || null
    },
    phaseCount() {
      return this.itemData.phase_count || null
    }
  },
  created() {
    const data = {
      id: this.itemData.id, field: 'coords',
      value: this.itemData.coords.map(e => {
        return {x: e.x, y: this.yPos};
      }),
    };
    this.$store.dispatch('changeData', data);
  },
  mounted() {
    this.instance.$on('move', ({x, target}) => {
      this.$emit('moveElement', {x, y: 0, target});
    });
    this.instance.$on('move-end', ({x, target}) => {
      this.$emit('saveTransform', {x, y: 0, target});
    });
    this.instance.$on('move-start', () => {
    });
  },
  beforeDestroy() {
    let ids = [];
    if (this.itemData.free_connection.id) ids.push(this.itemData.free_connection.id);
    if (this.itemData.income.id) ids.push(this.itemData.income.id);
    if (this.itemData.outcome.id) ids.push(this.itemData.outcome.id);
    ids.map(id => {
      let element = this.findById(id);
      this.$store.dispatch('changeData', {
        id: id,
        field: 'reverse',
        value: element.admittance !== 'top-tire',
      });
    });

  },
};
</script>
