<template>
  <section>
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
      />
    </div>

    <div v-else-if="errored" class="alert alert-danger" role="alert">
      Запрос к серверу не прошел! Попробуйте, пожалуйста, позже!
      <img
        src="/public/uploads/icons/reload.svg"
        style="width: 25px; margin-left: 5px"
        v-on:click="funLoadAll()"
      />
    </div>

    <!-- поисковая строчка -->
    <div class="search-bar">
      <input
        class="form-control"
        type="text"
        v-model.trim="filterName"
        @keyup.enter="funLoadContent()"
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
              v-for="item in contentTh"
              class="no-wrap text-center"
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
          <tr v-for="item in modelData.data" :key="item.id">
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
            <td>
              <a :href="'/admin/substation/edit/' + item.id">
                {{ item.id }}
              </a>
            </td>
            <td>
              <a :href="'/admin/substation/edit/' + item.id">
                {{ item.name }}
              </a>
            </td>
            <td>
              {{ item.busbarsections.length }}
            </td>
            <td>
              <small>
                {{ item.coord }}
              </small>
            </td>
            <td>
              {{ item.address }}
            </td>
            <td class="text-center">
              <small>
                {{
                  item.updated_at != null
                    ? item.updated_at.substring(0, 10)
                    : ""
                }}
              </small>
            </td>
            <td class="no-wrap">
              <a
                :href="'/admin/substation/show/' + item.id"
                class="btn btn-default btn-xs"
              >
                Схема
              </a>
              <a
                :href="'/admin/substation/parse/' + item.id"
                class="btn btn-default btn-xs"
              >
                XSDE
              </a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- нижняя панель -->
    <div class="table-bottom-bar">
      <!-- действия -->
      <div class="left">
        <a class="link-icon" @click="funLoadContent()">Обновить</a>
        <a class="link-icon" @click="funSelectedRows()">Удалить выбранные</a>
      </div>

      <!-- пагинация -->
      <div class="right">
        <small class="mt-5 mr-15">Строк: {{ modelData.total }}</small>
        <pagination
          :limit="5"
          :data="modelData"
          @pagination-change-page="funLoadContent"
        ></pagination>
      </div>
    </div>
  </section>
</template>

<script>
import spisokLiveSearch from "../../mixins/spisokLiveSearch";
export default {
  props: {},
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
          name: "Наименование",
          sortFieldName: "name",
          width: "auto",
        },
        2: {
          name: "Секций шин, шт.",
          sortFieldName: null,
          width: 100,
        },
        3: {
          name: "Координаты",
          sortFieldName: "coord",
          width: "auto",
        },
        4: {
          name: "Адрес",
          sortFieldName: "address",
          width: "auto",
        },
        5: {
          name: "Обновлено",
          sortFieldName: "updated_at",
          width: 75,
        },
        6: {
          name: "Действия",
          sortFieldName: null,
          width: 75,
        },
      },
      selectedAll: false,
      selectedRows: [],
      filterName: "",
      sorting: { col: "updated_at", direct: "desc" },
    };
  },
  mounted() {
    // общая загрузка
    this.funLoadAll();
  },
  methods: {
    // ------------------------------------------------------------------
    // общая загрузка
    funLoadAll() {
      // загрузка содержимого таблицы
      this.funLoadContent();
    },

    // ------------------------------------------------------------------
    // загрузка содержимого таблицы
    funLoadContent(page = 1) {
      // сообщение пользователю


      // признаки
      this.loading = true;
      this.errored = false;

      // данные post-запроса
      let form = new FormData();
      form.append("page", page);
      form.append("filterName", this.filterName);
      form.append("sortCol", this.sorting.col);
      form.append("sortDirect", this.sorting.direct);

      axios
        .post("/api/substationVueIndex", form)
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

    // ------------------------------------------------------------------
    // сброс фильтра
    funSearchClear() {
      this.filterName = "";

      // повторная загрузка
      this.funLoadContent();
    },

    // ------------------------------------------------------------------
    // выделить все
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

    // ------------------------------------------------------------------
    // удалить выбранные
    async funSelectedRows() {
      // вопрос Пользователю
      if (!confirm("Вы уверены, что хотите удалить выделенные записи?")) return;

      // признаки
      this.loading = true;
      this.errored = false;

      // данные post-запроса
      let form = new FormData();
      form.append("selectedRows", this.selectedRows);

      await axios
        .post("/api/substationVueDelete", form)
        .then((response) => {
          // запрос прошел
          // для отладки
          //console.log("Удаление успешно завершено!");

          // повторная загрузка
          this.funLoadAll();
        })
        .catch((error) => {
          // ошибка
          this.errored = true;
          // для отладки
          console.log("Удаление прошло с ошибками!");
          console.log(error);
        })
        .finally(() => {
          // финальная обработка
          this.loading = false;
        });
    },

    // ------------------------------------------------------------------
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

      //console.log("Выбрана сортировка по столбцу: " + this.sorting.col + " направление: " + this.sorting.direct);

      // повторная загрузка
      this.funLoadContent();
    },
  },
};
</script>
