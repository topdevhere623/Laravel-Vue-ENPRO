<template>
  <div class="position-relative mt-2">
    <template v-if="searchable">
      <div class="form-field" :name="name">
        <div v-if="loading" class="text-field d-flex justify-content-end pr-0">
          <img
              src="/public/uploads/loading.gif"
              style="
          height: 37px;
          object-fit: contain;
        "
              alt=""
          />
        </div>
        <div class="text-field" v-else>
          <input @focus="openDropdown" @blur="closeDropdown" ref="select-header" class="select-header"
                 v-model="searchQuery" type="text">
          <span
              v-if="dropdown"
              @click="closeDropdown"
              style="top: 15px; right: 10px; cursor: pointer;"
              class="position-absolute m-0"
          >
            <svg style="transform: scaleY(-1)" class="icon">
              <use :xlink:href="`../../../../public/assets/backend/icons/sprite.svg#chevron-down`"></use>
            </svg>
          </span>
          <span
              v-if="!dropdown"
              @click="openDropdown"
              style="top: 15px; right: 10px; cursor: pointer;"
              class="position-absolute m-0"
          >
            <svg style="transform: scaleY(1)" class="icon">
              <use :xlink:href="`../../../../public/assets/backend/icons/sprite.svg#chevron-down`"></use>
            </svg>
          </span>
        </div>
        <div class="form-input-label">{{ label }}</div>
      </div>
      <div tabindex="1" v-if="dropdown" class="select-dropdown">
        <div v-for="option in selectOptions" @click="select(option)" class="select-option"
             :class="value.value === option.id && 'active'">
          {{ getCaption(option) }}
        </div>
        <div v-if="!selectOptions || selectOptions.length === 0" class="empty-option">
          Нет данных
        </div>
      </div>
    </template>
    <template v-else>
      <div class="form-field" :name="name">
        <div v-if="loading" class="text-field d-flex justify-content-end pr-0">
          <img
              src="/public/uploads/loading.gif"
              style="
          height: 37px;
          object-fit: contain;
        "
              alt=""
          />
        </div>
        <div class="text-field" v-else>
          <input @focus="openDropdown" @blur="closeDropdown" ref="select-header" class="select-header" readonly
                 :value="value.caption"
                 type="text">
          <span
              v-if="dropdown"
              @click="closeDropdown"
              style="top: 15px; right: 10px; cursor: pointer;"
              class="position-absolute m-0"
          >
            <svg style="transform: scaleY(-1)" class="icon">
              <use :xlink:href="`../../../../public/assets/backend/icons/sprite.svg#chevron-down`"></use>
            </svg>
          </span>
          <span
              v-if="!dropdown"
              @click="openDropdown"
              style="top: 15px; right: 10px; cursor: pointer;"
              class="position-absolute m-0"
          >
            <svg style="transform: scaleY(1)" class="icon">
              <use :xlink:href="`../../../../public/assets/backend/icons/sprite.svg#chevron-down`"></use>
            </svg>
          </span>
        </div>
        <div class="form-input-label">{{ label }}</div>
      </div>
      <div tabindex="1" v-if="dropdown" class="select-dropdown">
        <div v-for="option in options" @click="select(option)" class="select-option"
             :class="value.value === option.id && 'active'">
          {{ getCaption(option) }}
        </div>
        <div v-if="!selectOptions || selectOptions.length === 0" class="empty-option">
          Нет данных
        </div>
      </div>
    </template>
  </div>
</template>
<script>

export default {
  name: 'custom-select',
  props: ['value', 'label', 'id', 'options', 'name', 'loading', 'fieldId', 'searchable'],
  methods: {
    getCaption(item) {
      return item.ASSETINFOKEY || item.MARK_PEGAS || item.label || item.name || item.value || item?.TransformerTankInfo?.AssetInfo?.CatalogAssetType?.IdentifiedObject.name
    },
    onInput(e) {
      const data = this.value;
      console.log(data)
      data.value = e.target.value;
      data.caption = e.target.options[e.target.selectedIndex].text
      if (this.fieldId) {
        this.$emit('change', {
          id: this.fieldId,
          value: data
        }, this.name);
      } else {
        this.$emit('change', data, this.name);
      }
    },
    openDropdown() {
      if (!this.dropdown) {
        this.dropdown = true
        this.searchQuery = ''
      }
    },
    select(option) {
      const data = this.value;
      data.value = option.id;
      data.caption = this.getCaption(option)
      if (this.fieldId) {
        this.$emit('change', {
          id: this.fieldId,
          value: data
        }, this.name);
      } else {
        this.$emit('change', data, this.name);
      }
      this.dropdown = false
      this.setCaption()
    },
    setCaption() {
      if (this.value.caption) {
        this.searchQuery = this.value.caption
      }
    },
    closeDropdown() {
      if (this.dropdown) {
        setTimeout(() => {
          if (!document.activeElement.classList.contains('select-dropdown')) {
            this.dropdown = false
            this.setCaption()
          }
        }, 1);
      }
    },
  },
  data() {
    return {
      dropdown: false,
      searchQuery: ''
    }
  },
  computed: {
    selectOptions() {
      return this.searchQuery === '' ? this.options : this.options.filter(option => {
        return this.getCaption(option).includes(this.searchQuery)
      })
    }
  },
  mounted() {
    this.setCaption()
  }
};
</script>
<style scoped>
.select-dropdown {
  background: #273661;
  padding: 5px 0 5px 5px;
  position: absolute;
  width: 100%;
  top: 46px;
  z-index: 999;
  border-radius: 0 0 6px 6px;
  max-height: 300px;
  overflow-y: scroll;
}

.select-dropdown::-webkit-scrollbar-track {
  opacity: 0;
}

.select-dropdown::-webkit-scrollbar {
  width: 5px;
}

.select-dropdown::-webkit-scrollbar-thumb {
  border-radius: 5px;
  background: #171636;
}

.select-option, .empty-option {
  padding: 5px 10px;
  border-radius: 6px;
}

.select-option:hover {
  background: #171636;
}

.select-option.active {
  background: #171636;
}

.select-header {
  background: none;
  outline: none;
  border: none;
  margin-top: 5px;
  color: #FFFFFF;
}
</style>
