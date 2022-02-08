<template>
  <div v-if="itemm" class="y-tree-list">
    <li>
      <div
        v-if="isFolder"
        :class="{ bold: isFolder }"
        @click="toggle"
        class="folder-item"
      >
        <span v-if="isFolder">
          <svg
            v-if="!isOpen"
            width="20px"
            aria-hidden="true"
            focusable="false"
            data-prefix="fas"
            data-icon="folder"
            role="img"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 512 512"
            class="svg-inline--fa fa-folder fa-w-16 fa-7x"
          >
            <path
              fill="currentColor"
              d="M464 128H272l-64-64H48C21.49 64 0 85.49 0 112v288c0 26.51 21.49 48 48 48h416c26.51 0 48-21.49 48-48V176c0-26.51-21.49-48-48-48z"
              class=""
            ></path>
          </svg>
          <svg
            v-else
            width="20px"
            aria-hidden="true"
            focusable="false"
            data-prefix="fas"
            data-icon="folder-open"
            role="img"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 576 512"
            class="svg-inline--fa fa-folder-open fa-w-18 fa-7x"
          >
            <path
              fill="currentColor"
              d="M572.694 292.093L500.27 416.248A63.997 63.997 0 0 1 444.989 448H45.025c-18.523 0-30.064-20.093-20.731-36.093l72.424-124.155A64 64 0 0 1 152 256h399.964c18.523 0 30.064 20.093 20.73 36.093zM152 224h328v-48c0-26.51-21.49-48-48-48H272l-64-64H48C21.49 64 0 85.49 0 112v278.046l69.077-118.418C86.214 242.25 117.989 224 152 224z"
              class=""
            ></path>
          </svg>
        </span>

        {{ itemm.name }}
        <span v-if="isFolder">[{{ isOpen ? "-" : "+" }}]</span>
      </div>
      <div v-else>
        <span @click="$emit('tree-selected', 1)">
          {{ itemm.name }}
        </span>
      </div>

      <ul class="unordered-list" v-show="isOpen" v-if="isFolder">
        <vue-tree-component
          class="itemm"
          v-for="(child, index) in itemm.children"
          :key="index"
          :itemm="child"
          @tree-selected="$emit('tree-selected', $event)"
        ></vue-tree-component>
      </ul>
    </li>
  </div>
</template>
<script>
export default {
  name: "tree",
  props: {
    itemm: {
      type: Object,
      default: null,
    },
  },
  data() {
    return {
      isOpen: false,
    };
  },
  computed: {
    isFolder: function () {
      return this.itemm.children && this.itemm.children.length;
    },
  },
  methods: {
    toggle: function () {
      if (this.isFolder) {
        this.isOpen = !this.isOpen;
      }
    },
  },
};
</script>
<style lang="scss">
.unordered-list {
  list-style: none;
}
.y-tree-list {
  .folder-item {
    display: flex;
    flex-wrap: nowrap;
    column-gap: 5px;
  }
}
</style>