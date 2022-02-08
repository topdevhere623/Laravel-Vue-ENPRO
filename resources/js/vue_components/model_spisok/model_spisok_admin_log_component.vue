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

              <!-- фильтр по имени пользователя -->
              <div v-if="item.sortFieldName === 'username'" class="mt-10">
                <select
                  class="form-control"
                  v-model="filterUserName"
                  @change="funLoadContent()"
                  style="max-width: 100px"
                >
                  <option value="0">любой</option>
                  <option
                    v-for="item in contentFilterUserName"
                    :value="item.id"
                  >
                    {{ item.username }}
                  </option>
                </select>
              </div>

              <!-- фильтр по типу журнала -->
              <div v-if="item.sortFieldName === 'adminlogtype'" class="mt-10">
                <select
                  class="form-control"
                  v-model="filterAdminLogType"
                  @change="funLoadContent()"
                  style="max-width: 100px"
                >
                  <option value="0">любой</option>
                  <option
                    v-for="item in contentFilterAdminLogType"
                    :value="item.type"
                  >
                    {{ item.type }}
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
              {{ item.id }}
            </td>
            <td class="text-center">
              {{ item.adminlogtype }}
            </td>
            <td>
              <small>
                {{ item.ip }}
              </small>
            </td>
            <td>
              {{ item.time }}
            </td>
            <td class="text-center">
              <small>
                {{ item.method }}
              </small>
            </td>
            <td>
              {{ item.url }}
              <br /><br />
              {{ item.input }}
            </td>
            <td>
              <small>
                {{ item.duration }}
              </small>
            </td>
            <td>
              {{ item.username }}
            </td>
            <td>
              <small>
                {{ item.browser }}
              </small>
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
          name: "Тип",
          sortFieldName: "adminlogtype",
          width: 50,
        },
        2: {
          name: "IP",
          sortFieldName: "ip",
          width: 50,
        },
        3: {
          name: "Дата",
          sortFieldName: "time",
          width: 75,
        },
        4: {
          name: "Метод",
          sortFieldName: "method",
          width: 50,
        },
        5: {
          name: "Страница",
          sortFieldName: "url",
          width: "auto",
        },
        6: {
          name: "Время",
          sortFieldName: "duration",
          width: 50,
        },
        7: {
          name: "Пользователь",
          sortFieldName: "username",
          width: 100,
        },
        8: {
          name: "Браузер",
          sortFieldName: "browser",
          width: 75,
        },
      },
      contentFilterUserName: {}, // значения в списке фильтра
      contentFilterAdminLogType: {},
      selectedAll: false,
      selectedRows: [],
      filterName: "",
      filterUserName: 0, // значение по-умолчанию
      filterAdminLogType: 0,
      sorting: { col: "id", direct: "desc" },
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
      // загрузка данных для фильтра имени пользователя
      this.funLoadfilterUserName();
      // загрузка данных для фильтра типа журанала
      this.funLoadfilterAdminLogType();
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
      form.append("filterUserName", this.filterUserName);
      form.append("filterAdminLogType", this.filterAdminLogType);
      form.append("sortCol", this.sorting.col);
      form.append("sortDirect", this.sorting.direct);

      axios
        .post("/api/adminlogVueIndex", form)
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
    // загрузка данных для фильтра имени пользователя
    async funLoadfilterUserName() {
      // тело сырого запроса
      let myQuery =
        "SELECT DISTINCT b.id, b.username " +
        "FROM admin_log a " +
        "LEFT JOIN user b ON a.user_id = b.id " +
        "WHERE (b.deleted_at IS NULL) ORDER BY b.username";

      // выполнеине сырого запроса
      let myReturn = await this.funAnyQuery(myQuery);
      console.log("Для фильтра - шаг 1");
      console.log(myReturn);

      if (
        typeof myReturn !== "undefined" &&
        myReturn !== null &&
        myReturn.length > 0
      ) {
        this.contentFilterUserName = [];
        this.contentFilterUserName = myReturn;
        console.log("Для фильтра данные:");
        console.log(this.contentFilterUserName);
      }
    },

    // ------------------------------------------------------------------
    // загрузка данных для фильтра типа журанала
    async funLoadfilterAdminLogType() {
      // тело сырого запроса
      let myQuery =
        "SELECT DISTINCT a.type " + "FROM admin_log a " + "ORDER BY a.type";

      // выполнеине сырого запроса
      let myReturn = await this.funAnyQuery(myQuery);
      console.log("Для фильтра - шаг 1");
      console.log(myReturn);

      if (
        typeof myReturn !== "undefined" &&
        myReturn !== null &&
        myReturn.length > 0
      ) {
        this.contentFilterAdminLogType = [];
        this.contentFilterAdminLogType = myReturn;
        console.log("Для фильтра данные:");
        console.log(this.contentFilterAdminLogType);
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
      this.filterUserName = 0;
      this.filterAdminLogType = 0;

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
        .post("/api/adminlogVueDelete", form)
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
