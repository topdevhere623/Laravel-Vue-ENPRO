<template>
  <section v-if="this.getModelName && this.getModelName !== ''">
    <!-- индикатор загрузки -->
    <div v-if="loading">
      <img
        src="/public/uploads/loading.gif"
        style="
          width: 150px;
          position: fixed;
          margin: auto;
          top: 0;
          bottom: 0;
          left: 0;
          right: 0;
          z-index: 9999;
        "
        alt=""
      />
    </div>

    <div v-else-if="errored" class="alert alert-danger" role="alert">
      Запрос к серверу не прошел! Попробуйте, пожалуйста, позже!
      <img
        src="/public/uploads/icons/reload.svg"
        style="width: 25px; margin-left: 5px"
        v-on:click="funLoadContent()"
        alt=""
      />
    </div>

    <!-- поисковая строчка -->
    <div class="search-bar">
      <input
        class="form-control"
        type="text"
        v-model.trim="filterName"
        @input="funLoadContent()"
        placeholder="Поиск по наименованию"
        ref="search"
      />
      <button
        v-if="filterName.length > 0"
        type="button"
        class="button position-absolute search-bar-reset-btn"
        @click="funSearchClear()"
      >
        <span class="icon icon-close mr-0"> </span>
      </button>
    </div>
    <div class="table-wrapper table-auto-height">
      <table
        class="table custom-table"
        data-plugin="selectable"
        data-row-selectable="false"
      >
        <thead>
          <tr>
            <th class="w-50">
              <label class="checkbox">
                <input
                  class="selectable-all"
                  type="checkbox"
                  v-model="selectedAll"
                  @change="funSelectedAll()"
                />
                <span class="box"></span>
              </label>
            </th>
            <!-- шапка в цикле -->
            <th
              v-for="(item, index) in contentTh"
              class="no-wrap text-center"
              :key="index"
              :style="'width:' + item.width + 'px;'"
            >
              <span
                v-if="item.sortFieldName != null"
                @click="funSorting(item.sortFieldName)"
                style="text-decoration: underline"
              >
                {{ item.name }}
              </span>
              <span v-else>
                {{ item.name }}
              </span>
              <span
                v-if="
                  sorting.col === item.sortFieldName && sorting.direct === 'asc'
                "
                class="wb-triangle-up"
              ></span>
              <span
                v-if="
                  sorting.col === item.sortFieldName &&
                  sorting.direct === 'desc'
                "
                class="wb-triangle-down"
              ></span>
            </th>
          </tr>
        </thead>
        <tbody>
          <!-- содержимое в цикле -->
          <tr v-for="(item, index) in modelData.data" :key="index">
            <td>
              <label class="checkbox">
                <input
                  class="selectable-item"
                  type="checkbox"
                  :id="'check_' + item.id"
                  :value="item.id"
                  v-model="selectedRows"
                />
                <span class="box"></span>
              </label>
            </td>
            <td class="text-center">
              <a :href="`all_kind/${getModelName}/edit/${item.id}`">
                {{ item.id }}
              </a>
            </td>
            <td class="text-left">
              <a
                v-if="item.ru_value"
                :href="`all_kind/${getModelName}/edit/${item.id}`"
              >
                {{ item.ru_value }}
              </a>
            </td>
            <td class="text-left">
              <a
                v-if="item.value"
                :href="`all_kind/${getModelName}/edit/${item.id}`"
              >
                {{ item.value }}
              </a>
            </td>
            <td class="text-left">
              <a
                v-if="item.description"
                :href="`all_kind/${getModelName}/edit/${item.id}`"
              >
                {{ item.description }}
              </a>
            </td>
            <td class="text-left">
              <a
                v-if="item.enpro_code"
                :href="`all_kind/${getModelName}/edit/${item.id}`"
              >
                {{ item.enpro_code }}
              </a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="table-bottom-bar">
      <!-- действия -->
      <div class="left">
        <a class="link-icon" @click="funLoadContent()">Обновить</a>
        <a
          v-if="
            getUserRole === 'vendor' ||
            getUserRole === 'admin' ||
            getUserRole === 'manager' ||
            getUserRole === 'operator'
          "
          class="link-icon"
          @click="funSelectedRows('delete')"
          >Удалить выбранные</a
        >
      </div>
      <div class="right" v-if="Object.keys(modelData).length > 0">
        <small class="mt-5 mr-15"
          >Строк: {{ modelData.meta.pagination.total }}</small
        >
        <pagination
          :limit="5"
          :data="modelData.meta.pagination"
          @pagination-change-page="funLoadContent"
        ></pagination>
      </div>
    </div>
  </section>
</template>

<script>
import spisokLiveSearch from "../../mixins/spisokLiveSearch";
export default {
  name: "model_spisok_allkind_componnet",
  props: {
    getUserRole: {
      required: true,
      type: String,
    },
    getModelName: {
      type: String,
    },
  },
  mixins: [spisokLiveSearch],
  data() {
    return {
      loading: false,
      errored: false,
      modelData: {},
      contentTh: {
        0: {
          name: "ID",
          sortFieldName: "id",
          width: 50,
        },
        1: {
          name: "Значение на русском",
          sortFieldName: "ru_value",
          width: "auto",
        },
        2: {
          name: "Значение",
          sortFieldName: "value",
          width: "auto",
        },
        3: {
          name: "Описание",
          sortFieldName: "description",
          width: "auto",
        },
        4: {
          name: "Enpro Code",
          sortFieldName: "enpro_code",
          width: "auto",
        },
      },
      selectedAll: false,
      selectedRows: [],
      filterName: "",
      sorting: {
        col: "updated_at",
        direct: "desc",
      },
    };
  },
  watch: {
    getModelName: function () {
      this.funLoadContent();
    },
  },
  methods: {
    funLoadContent(page = 1) {
      // сообщение пользователю


      // признаки
      this.loading = true;
      this.errored = false;
      // данные запроса
      const params = {
        page: page,
        search: this.filterName.length > 0 ? this.filterName : "",
        sortCol: this.sorting.col,
        sortDirect: this.sorting.direct,
      };

      axios
        .get(`/api/all_kind/model/${this.getModelName}/`, {
          params,
        })
        .then((response) => {
          // для отладки
          console.log("Загрузка успешно прошла!");
          console.log(response.data);

          // запрос прошел - записать полученные данные в массив
          this.modelData = response.data;
          // сообщение пользователю
          toastr.success("Данные успешно загружены...");
        })
        .catch((error) => {
          // ошибка
          this.errored = true;
          // для отладки
          console.log("Ошибка!");
          console.log(error);
          // сообщение пользователю
          toastr.error("Ошибка при загрузке данных...");
        })
        .finally(() => {
          // финальная обработка
          this.loading = false;

          // сбросить флажок Выделить все
          this.selectedAll = false;
          this.selectedRows = [];
        });
    },
    funSelectedAll() {
      // очистить выделенные и записать всем новый признак
      this.selectedRows = [];
      if (this.modelData.data.length > 0 && this.selectedAll === true) {
        let mySpisok = [];
        this.modelData.data.forEach(function (item) {
          mySpisok.push(item.id);
        });
        this.selectedRows = mySpisok;
      }
    },
    async funSelectedRows() {
      // вопрос Пользователю
      if (!confirm("Вы уверены, что хотите удалить выделенные записи?")) return;

      // признаки
      this.loading = true;
      this.errored = false;

      // данные запроса
      const params = {
        ids: this.selectedRows,
      };

      await axios
        .post(`/api/all_kind/model/${this.getModelName}/delete`, params)
        .then((response) => {
          // запрос прошел
          // для отладки
          //console.log("Удаление успешно завершено!");

          // сообщение пользователю
          this.funLoadContent();
        })
        .catch((error) => {
          // ошибка
          this.errored = true;
          // сообщение пользователю
          toastr.error("Ошибка при обработке данных...");
          console.log(error);
        });
      // .finally(() => {
      //     // финальная обработка
      //     this.loading = false;
      // });
    },
    // сортировка по столбцу
    funSorting(getCol) {
      // новые значения
      // сортировка
      if (this.sorting.col !== getCol) {
        this.sorting.direct = "asc";
      } else {
        this.sorting.direct = this.sorting.direct === "asc" ? "desc" : "asc";
      }
      // столбец
      this.sorting.col = getCol;

      // повторная загрузка
      this.funLoadContent();
    },
  },
};
</script>

<style scoped>
</style>
