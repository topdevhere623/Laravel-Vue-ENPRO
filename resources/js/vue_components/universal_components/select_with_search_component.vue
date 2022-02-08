<template>
  <div>
    <multiselect
      placeholder="Введите для поиска"
      v-model="value"
      :options="list"
      :multiple="multi"
      :id="getId"
      select-label="Выбрать"
      selected-label="Выбрано"
      deselect-label="Удалить"
      :searchable="true"
      :internal-search="false"
      :clear-on-select="false"
      :label="getLabel"
      track-by="id"
      :show-no-results="true"
      :hide-selected="false"
      open-direction="bottom"
      @search-change="changeSQ"
      @select="select"
      @input="multiSelect"
      @remove="remove"
      :disabled="disabled"
    >
      <span slot="noResult">Никаких элементов не найдено.</span>

      <span slot="noOptions"> Список пуст </span>
    </multiselect>
    <div class="text-right pr-1 pt-1" v-if="addItem">
      <a
        href="#"
        class="app-text-color-accent-action text-sm"
        @click="!disabled && $refs[getId].open()"
        data-whatever="@mdo"
        >Добавить новую</a
      >
    </div>

    <sweet-modal
      v-if="addItem"
      :ref="getId"
      class="my-custom-sweet-modal"
      overlay-theme="dark"
      enable-mobile-fullscreen
    >
      <template v-if="getModelName === 'WindingConnection'">
        <model-edit-enum-allkindform-component
          :isModal="true"
          @onNewCreatedData="selectCreatedData"
          @save="closeForm"
          :model="getModelName"
        >
        </model-edit-enum-allkindform-component>
      </template>
      <template v-else-if="getModelName === 'gost'">
        <model-add-gost-component
          @onNewCreated="closeForm"
          @onNewCreatedData="selectCreatedData"
        >
        </model-add-gost-component>
      </template>
      <template v-else>
        <h4 class="text-left">
          <!-- {{ modalLabel }} -->
          {{ getTitle }}
        </h4>
        <model-edit-allkindform-component
          :isModal="true"
          @onNewCreatedData="selectCreatedData"
          @save="closeForm"
          :model="getModelName"
        ></model-edit-allkindform-component>
      </template>
    </sweet-modal>

    <div v-if="errored" class="alert alert-danger" role="alert">
      Запрос к серверу не прошел! Попробуйте, пожалуйста, позже!
      <img
        src="/public/uploads/icons/reload.svg"
        style="width: 25px; margin-left: 5px"
        v-on:click="loadList"
      />
    </div>
  </div>
</template>
<script>
// import Multiselect from "vue-multiselect";

export default {
  name: "select_with_search",
  props: {
    getUrl: {
      required: true,
      type: String,
    },
    resetValue: {
      type: Boolean,
      default: false,
      required: false,
    },
    getLabel: {
      default: "name",
      type: String,
    },
    getValue: {
      type: Object,
      default: null,
    },
    getModelName: {
      type: String,
      required: true,
    },
    getId: {
      required: true,
      type: String,
    },
    getTitle: {
      required: true,
      type: String,
    },
    disabled: {
      type: Boolean,
      required: false,
      default: false,
    },
    multi: {
      type: Boolean,
      required: false,
      default: false,
    },
    addItem: {
      type: Boolean,
      required: false,
      default: true,
    },
  },
  data() {
    return {
      errored: false,
      loading: false,
      list: [],
      value: null,
      searchQuery: null,
      search: false,
    };
  },
  computed: {
    reset: function () {
      return this.resetValue;
    },
  },

  // components: {
  //   Multiselect,
  // },
  methods: {
    selectCreatedData(newData) {
      this.value = newData;
      this.select(this.value);
    },
    closeForm() {
      this.searchQuery = null;
      this.loadList();
      this.$refs[this.getId].close();
    },
    select(val) {
      console.log(val);
      if (!this.multi) {
        this.$emit("select", val);
      }
    },
    multiSelect(val) {
      if (this.multi) {
        let ids = [];
        val.map(({ id }) => ids.push(id));
        this.$emit("select", ids);
      }
    },
    remove(val) {
      if (!this.multi) {
        this.$emit("remove", { id: null });
      }
    },
    changeSQ(query) {
      this.searchQuery = query;
    },
    async loadList() {
      this.loading = true;
      this.$emit("loading", true);
      this.errored = false;
      let url = `/api/${this.getUrl}?perPage=0`;
      if (!!this.searchQuery) {
        url += `&search=${this.searchQuery}`;
      }

      await axios
        .get(url)
        .then((response) => {
          this.list = response.data.data;
          toastr.success("Данные успешно загружены...");
        })
        .catch((error) => {
          this.errored = true;
          // для отладки
          console.log("Ошибка при загрузке данных WireInfo");
          console.log(error);
          // сообщение пользователю
          toastr.error("Ошибка при загрузке данных...");
        })
        .finally(() => {
          this.loading = false;
          this.$emit("loadingDone", false);
        });
    },
  },
  watch: {
    searchQuery: function (newQ, oldQ) {
      this.search = true;
      if (newQ !== "" && oldQ !== "" && this.searchQuery !== "") {
        this.debouncedGetAnswer();
      }
    },
    getValue: function (newQ, oldQ) {
      this.value = this.getValue;
    },
    reset: function (oldv, newv) {
      console.log(111);
      this.value = null;
      deep: true;
      this.$emit("reset", true);
    },

    value: function (o, n) {
      deep: true;
      if (!this.multi) {
        this.$emit("select", this.value);
      }
    },
  },
  created: function () {
    this.debouncedGetAnswer = _.debounce(this.loadList, 1000);
  },
  mounted() {
    this.loadList();
    if (null !== _.get(this.getValue, ["id"], null)) {
      this.value = this.getValue;
    }
  },
};
</script>
<style scoped>
.multiselect__spinner {
  top: 4px;
  background: #273661;
}
</style>