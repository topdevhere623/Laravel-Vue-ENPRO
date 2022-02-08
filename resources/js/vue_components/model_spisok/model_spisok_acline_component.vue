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
    <div class="search-bar position-relative">
      <input
        class="form-control"
        type="text"
        v-model="filterName"
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

              <!-- фильтр по классу напряжения -->
              <div
                v-if="item.sortFieldName === 'basevoltage_name'"
                class="mt-10"
              >
                <select
                  class="form-control"
                  v-model="filterBaseVoltage"
                  @change="funLoadContent()"
                  style="max-width: 100px"
                >
                  <option value="0">любой</option>
                  <option
                    v-for="item in contentFilterBaseVoltage"
                    :value="item.id"
                  >
                    {{ item.name }}
                  </option>
                </select>
              </div>

              <!-- фильтр по статусу линии -->
              <div v-if="item.sortFieldName === 'status_name'" class="mt-10">
                <select
                  class="form-control"
                  v-model="filterAclineStatus"
                  @change="funLoadContent()"
                  style="max-width: 100px"
                >
                  <option value="0">любой</option>
                  <option
                    v-for="item in contentFilterAclineStatus"
                    :value="item.id"
                  >
                    {{ item.name }}
                  </option>
                </select>
              </div>
            </th>
          </tr>
        </thead>

        <tbody>
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
              <a :href="'/admin/acline/edit/' + item.id">
                {{ item.id }}
              </a>
            </td>
            <td>
              <a :href="'/admin/acline/edit/' + item.id">
                {{ item.name }}
              </a>
            </td>
            <td>
              {{ item.basevoltage_name }}
            </td>
            <td>
              {{ item.aclinesegments.length }}
            </td>
            <td class="text-center">
              <small>
                {{ item.status_name }}
              </small>
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
                :href="'/admin/acline/map/edit/' + item.id"
                class="btn btn-default btn-xs"
              >
                Карта
              </a>
              <a
                :href="'/admin/acline/report/1/' + item.id"
                class="btn btn-default btn-xs"
              >
                Паспорт
              </a>
              <a
                :href="'/admin/acline/report/2/' + item.id"
                class="btn btn-default btn-xs"
              >
                Отчет-2
              </a>
              <a
                :href="'/admin/line/cim/1/' + item.id"
                class="btn btn-default btn-xs"
                v-if="item.basevoltage_name !== 'Переменный 0,4 кВ'"
              >
                CIM
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
        <a class="link-icon" @click="funLoadAll()">Обновить</a>
        <a
          v-if="
            getUserRole === 'vendor' ||
            getUserRole === 'admin' ||
            getUserRole === 'manager' ||
            getUserRole === 'operator'
          "
          class="link-icon"
          @click="funSelectedRows()"
          >Удалить выбранные</a
        >
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
  props: {
    getUserRole: String,
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
          name: "Наименование",
          sortFieldName: "name",
          width: "auto",
        },
        2: {
          name: "Класс напряжения",
          sortFieldName: "basevoltage_name",
          width: 100,
        },
        3: {
          name: "Сегментов",
          sortFieldName: null,
          width: 75,
        },
        4: {
          name: "Статус",
          sortFieldName: "status_name",
          width: 100,
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
      contentFilterBaseVoltage: {}, // значения в списке фильтра
      contentFilterAclineStatus: {},
      selectedAll: false,
      selectedRows: [],
      filterName: "",
      filterBaseVoltage: 0, // значение по-умолчанию
      filterAclineStatus: 0,
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
      // загрузка данных для фильтра напряжения
      this.funLoadFilterBaseVoltage();
      // загрузка данных для фильтра статусов линии
      this.funLoadFilterAclineStatus();
    },
    // ------------------------------------------------------------------
    // загрузка содержимого таблицы
    funLoadContent(page = 1) {
      // признаки
      this.loading = true;
      this.errored = false;

      // данные post-запроса
      let form = new FormData();
      form.append("page", page);
      form.append("filterName", this.filterName);
      form.append("filterBaseVoltage", this.filterBaseVoltage);
      form.append("filterAclineStatus", this.filterAclineStatus);
      form.append("sortCol", this.sorting.col);
      form.append("sortDirect", this.sorting.direct);

      axios
        .post("/api/aclineVueIndex", form)
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
    // загрузка данных для фильтра напряжения
    async funLoadFilterBaseVoltage() {
      // тело сырого запроса
      let myQuery =
        "SELECT DISTINCT c.id, c.name " +
        "FROM acline a " +
        "LEFT JOIN identifiedobject b on a.identifiedobject_id = b.id " +
        "LEFT JOIN basevoltage c on b.voltage_id = c.id " +
        "WHERE (a.deleted_at IS NULL) AND (b.deleted_at IS NULL) AND (c.deleted_at IS NULL) ORDER BY c.id";

      // выполнеине сырого запроса
      let myReturn = await this.funAnyQuery(myQuery);
      console.log("Для фильтра - шаг 1");
      console.log(myReturn);

      if (
        typeof myReturn !== "undefined" &&
        myReturn !== null &&
        myReturn.length > 0
      ) {
        this.contentFilterBaseVoltage = [];
        this.contentFilterBaseVoltage = myReturn;
        console.log("Для фильтра данные:");
        console.log(this.contentFilterBaseVoltage);
      }
    },

    // ------------------------------------------------------------------
    // загрузка данных для фильтра статусов линии
    async funLoadFilterAclineStatus() {
      // тело сырого запроса
      let myQuery =
        "SELECT DISTINCT b.id, b.name " +
        "FROM acline a " +
        "LEFT JOIN acline_status b on a.status_id = b.id " +
        "WHERE (a.deleted_at IS NULL) AND (b.deleted_at IS NULL) ORDER BY b.id";

      // выполнеине сырого запроса
      let myReturn = await this.funAnyQuery(myQuery);
      console.log("Для фильтра - шаг 1");
      console.log(myReturn);

      if (
        typeof myReturn !== "undefined" &&
        myReturn !== null &&
        myReturn.length > 0
      ) {
        this.contentFilterAclineStatus = [];
        this.contentFilterAclineStatus = myReturn;
        console.log("Для фильтра данные:");
        console.log(this.contentFilterAclineStatus);
      }
    },

    // ------------------------------------------------------------------
    // выполнеине сырого запроса
    async funAnyQuery(getQuery) {
      // признаки
      this.loading = true;
      this.errored = false;

      let myData = null;

      // данные post-запроса
      let form = new FormData();
      form.append("getQuery", getQuery);

      await axios
        .post("/api/runAnyQuery", form)
        .then((response) => {
          // для отладки
          console.log("Загрузка сырого запроса прошла успешно!");
          console.log(response.data);

          // запрос прошел
          myData = response.data;
        })
        .catch((error) => {
          // ошибка
          this.errored = true;
          // для отладки
          console.log("Ошибка!");
          console.log(error);
        })
        .finally(() => {
          // финальная обработка
          this.loading = false;
        });

      return myData;
    },

    // ------------------------------------------------------------------
    // сброс фильтра
    funSearchClear() {
      this.filterName = "";
      this.filterBaseVoltage = 0;
      this.filterAclineStatus = 0;

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
        .post("/api/aclineVueDelete", form)
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
<style>
.search-bar-reset-btn {
  right: 30px;
  padding: 10px !important;
  min-width: auto !important;
  border: none !important;
}
.icon-close {
  background-image: url("data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIj8+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeG1sbnM6c3ZnanM9Imh0dHA6Ly9zdmdqcy5jb20vc3ZnanMiIHZlcnNpb249IjEuMSIgd2lkdGg9IjUxMiIgaGVpZ2h0PSI1MTIiIHg9IjAiIHk9IjAiIHZpZXdCb3g9IjAgMCA1MTEuOTk1IDUxMS45OTUiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDUxMiA1MTIiIHhtbDpzcGFjZT0icHJlc2VydmUiIGNsYXNzPSIiPjxnPgo8ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgoJPGc+CgkJPHBhdGggZD0iTTQzNy4xMjYsNzQuOTM5Yy05OS44MjYtOTkuODI2LTI2Mi4zMDctOTkuODI2LTM2Mi4xMzMsMEMyNi42MzcsMTIzLjMxNCwwLDE4Ny42MTcsMCwyNTYuMDA1ICAgIHMyNi42MzcsMTMyLjY5MSw3NC45OTMsMTgxLjA0N2M0OS45MjMsNDkuOTIzLDExNS40OTUsNzQuODc0LDE4MS4wNjYsNzQuODc0czEzMS4xNDQtMjQuOTUxLDE4MS4wNjYtNzQuODc0ICAgIEM1MzYuOTUxLDMzNy4yMjYsNTM2Ljk1MSwxNzQuNzg0LDQzNy4xMjYsNzQuOTM5eiBNNDA5LjA4LDQwOS4wMDZjLTg0LjM3NSw4NC4zNzUtMjIxLjY2Nyw4NC4zNzUtMzA2LjA0MiwwICAgIGMtNDAuODU4LTQwLjg1OC02My4zNy05NS4yMDQtNjMuMzctMTUzLjAwMXMyMi41MTItMTEyLjE0Myw2My4zNy0xNTMuMDIxYzg0LjM3NS04NC4zNzUsMjIxLjY2Ny04NC4zNTUsMzA2LjA0MiwwICAgIEM0OTMuNDM1LDE4Ny4zNTksNDkzLjQzNSwzMjQuNjUxLDQwOS4wOCw0MDkuMDA2eiIgZmlsbD0iI2ZmZmZmZiIgZGF0YS1vcmlnaW5hbD0iIzAwMDAwMCIgc3R5bGU9IiIgY2xhc3M9IiIvPgoJPC9nPgo8L2c+CjxnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+Cgk8Zz4KCQk8cGF0aCBkPSJNMzQxLjUyNSwzMTAuODI3bC01Ni4xNTEtNTYuMDcxbDU2LjE1MS01Ni4wNzFjNy43MzUtNy43MzUsNy43MzUtMjAuMjksMC4wMi0yOC4wNDYgICAgYy03Ljc1NS03Ljc3NS0yMC4zMS03Ljc1NS0yOC4wNjUtMC4wMmwtNTYuMTksNTYuMTExbC01Ni4xOS01Ni4xMTFjLTcuNzU1LTcuNzM1LTIwLjMxLTcuNzU1LTI4LjA2NSwwLjAyICAgIGMtNy43MzUsNy43NTUtNy43MzUsMjAuMzEsMC4wMiwyOC4wNDZsNTYuMTUxLDU2LjA3MWwtNTYuMTUxLDU2LjA3MWMtNy43NTUsNy43MzUtNy43NTUsMjAuMjktMC4wMiwyOC4wNDYgICAgYzMuODY4LDMuODg3LDguOTY1LDUuODExLDE0LjA0Myw1LjgxMXMxMC4xNTUtMS45NDQsMTQuMDIzLTUuNzkybDU2LjE5LTU2LjExMWw1Ni4xOSw1Ni4xMTEgICAgYzMuODY4LDMuODY4LDguOTQ1LDUuNzkyLDE0LjAyMyw1Ljc5MmM1LjA3OCwwLDEwLjE3NS0xLjk0NCwxNC4wNDMtNS44MTFDMzQ5LjI4LDMzMS4xMTcsMzQ5LjI4LDMxOC41NjIsMzQxLjUyNSwzMTAuODI3eiIgZmlsbD0iI2ZmZmZmZiIgZGF0YS1vcmlnaW5hbD0iIzAwMDAwMCIgc3R5bGU9IiIgY2xhc3M9IiIvPgoJPC9nPgo8L2c+CjxnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjwvZz4KPGcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPC9nPgo8ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgo8L2c+CjxnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjwvZz4KPGcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPC9nPgo8ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgo8L2c+CjxnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjwvZz4KPGcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPC9nPgo8ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgo8L2c+CjxnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjwvZz4KPGcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPC9nPgo8ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgo8L2c+CjxnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjwvZz4KPGcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPC9nPgo8ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgo8L2c+CjwvZz48L3N2Zz4K");
}
</style>
