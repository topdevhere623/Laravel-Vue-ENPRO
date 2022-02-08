<template>
  <section>
    <div class="page-header">
      <!-- {{-- заголовок --}} -->
      <h2 class="page-title">
        {{ titleOne }}
      </h2>

      <!-- {{-- хлебные крошки --}} -->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="/admin">Главная</a>
        </li>
<!--        <li class="breadcrumb-item" id="defect-toggle" @click="openDefectInfo">-->
<!--          <a class="link-icon"> Справочник дефектов </a>-->
<!--        </li>-->
        <li class="breadcrumb-item active">{{ titleOne }}</li>
      </ol>
      <!-- {{-- действия на странице --}} -->
      <div class="page-header-actions">
        <a
          v-if="
            getUserRole === 'vendor' ||
            getUserRole === 'admin' ||
            getUserRole === 'manager' ||
            getUserRole === 'operator'
          "
          href="/admin/enpro_defect/edit"
          class="button"
          data-toggle="tooltip"
          data-original-title="Создать новую запись"
        >
          <span class="icon icon-add" aria-hidden="true"></span>
          <span> Создать </span>
        </a>
      </div>
    </div>
    <div class="page-content main-content">
      <div class="row row-lg">
        <div class="col-lg-12">
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
            <!-- Запрос к серверу не прошел! Попробуйте, пожалуйста, позже! -->
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
                    v-for="(item, index) in contentTh"
                    class="no-wrap text-center"
                    :style="'width:' + item.width + 'px;'"
                    :key="index"
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
                        sorting.col === item.sortFieldName &&
                        sorting.direct === 'asc'
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

                    <!-- фильтр по роли -->
                    <div
                      v-if="item.sortFieldName === 'role_name'"
                      class="mt-10"
                    >
                      <select
                        class="form-control"
                        v-model="filterUserRole"
                        @change="funLoadContent()"
                        style="max-width: 100px"
                      >
                        <option value="0">любая</option>
                        <option
                          v-for="item in contentFilterUserRole"
                          :value="item.id"
                          :key="item.id"
                        >
                          {{ item.name }}
                        </option>
                      </select>
                    </div>
                    <input
                      class="d-block"
                      type="text"
                      v-model="item.filterValue"
                      @keyup.enter="funLoadContent()"
                    />
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
                  <!-- Группа оборудования -->
                  <td class="text-center">
                    <!--изменить адрес href потом --->
                    <a :href="'/admin/enpro_defect/edit/?id=' + item.id">
                      {{ item.id }}
                    </a>
                  </td>
                  <!-- Группа оборудования -->
                  <td class="">
                    <a
                      :href="
                        '/admin/enpro_class_defect/edit/?id=' + item.class_id
                      "
                    >
                      {{ item.class_class }}
                    </a>
                  </td>
                  <td class="">
                    <a
                      :href="
                        '/admin/enpro_class_defect/edit/?id=' + item.class_id
                      "
                    >
                      {{ item.class_title }}
                    </a>
                  </td>
                  <!-- Группа инструментов -->
                  <td class="">
                    <a
                      :href="
                        '/admin/enpro_group_defect/edit/?id=' + item.group_id
                      "
                    >
                      {{ item.group_code }}
                    </a>
                  </td>
                  <td class="">
                    <a
                      :href="
                        '/admin/enpro_group_defect/edit/?id=' + item.group_id
                      "
                    >
                      {{ item.group_title }}
                    </a>
                  </td>

                  <td class="">
                    <a :href="'/admin/enpro_defect/edit/?id=' + item.id">
                      {{ item.code }}
                    </a>
                  </td>
                  <td class="">
                    <a :href="'/admin/enpro_defect/edit/?id=' + item.id">
                      {{ item.title }}
                    </a>
                  </td>

                  <td class="">
                    <a :href="'/admin/enpro_defect/edit/?id=' + item.id">
                      {{ item.critical }}
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- нижняя панель -->
          <div class="table-bottom-bar">
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

            <div v-if="Object.keys(modelData).length > 0" class="right">
              <small  class="mt-5 mr-15"
                >Выбрано строк: {{ modelData.meta.pagination.total }}</small
              >
              <small class="mt-5 mr-15">Строк: {{ total }}</small>
              <pagination
                :limit="5"
                :data="modelData.meta.pagination"
                @pagination-change-page="funLoadContent"
              ></pagination>
            </div>
            <!-- действия -->
            <!-- пагинация -->
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import spisokLiveSearch from "../../mixins/spisokLiveSearch";
export default {
  name: "enprodefect",
  props: {
    getUserRole: String,
    titleOne: String,
  },
  mixins: [spisokLiveSearch],
  data() {
    return {
      loading: false,
      errored: false,
      modelData: {},
      total: null,
      filteredId: [],
      contentTh: [
        {
          name: "ID",
          sortFieldName: "id",
          width: 50,
          filterValue: "",
        },
        {
          name: "Код ГО",
          sortFieldName: "class_class",
          width: "auto",
          filterValue: "",
        },
        {
          name: "Группа оборудования",
          sortFieldName: "class_title",
          width: "auto",
          filterValue: "",
        },
        {
          name: "Код ГИ",
          sortFieldName: "group_code",
          width: "auto",
          filterValue: "",
        },
        {
          name: "Группа измерений",
          sortFieldName: "group_title",
          width: "auto",
          filterValue: "",
        },
        {
          name: "Код дефекта",
          sortFieldName: "code",
          width: "auto",
          filterValue: "",
        },
        {
          name: "Дефект",
          sortFieldName: "title",
          width: "auto",
          filterValue: "",
        },
        {
          name: "Критичность",
          sortFieldName: "critical",
          width: "auto",
          filterValue: "",
        },
      ],
      contentFilterUserRole: {}, // значения в списке фильтра
      selectedAll: false,
      selectedRows: [],
      filterName: "",
      filterUserRole: 0, // значение по-умолчанию
      sorting: { col: "", direct: "" },
    };
  },
  computed: {
    watchContentth() {
      return this.contentTh;
    },
  },
  watch: {
    contentTh: {
      handler(val) {
        // console.log("seatch");
        this.debouncedGetAnswer();
      },
      deep: true,
    },
  },
  created: function () {
    this.debouncedGetAnswer = _.debounce(this.funLoadContent, 1000);
  },
  mounted() {
    // общая загрузка
    this.funLoadAll();
  },
  methods: {
    openDefectInfo() {
      if (!$("#defect-info-group").hasClass("opened")) {
        $("#defect-info-group").addClass("opened");
        $("#defect-info-group .group-items.js-nav-content").show();
      } else {
        $("#defect-info-group").removeClass("opened");
        $("#defect-info-group .group-items.js-nav-content").hide();
      }
    },
    // ------------------------------------------------------------------
    // общая загрузка
    funLoadAll() {
      // загрузка содержимого таблицы
      this.funLoadContent();
      // загрузка данных для фильтра роли пользователя
      this.funLoadfilterUserRole();
    },

    // ------------------------------------------------------------------
    // загрузка содержимого таблицы
    async funLoadContent(page = 1, filter) {
      // сообщение пользователю


      // признаки
      this.loading = true;
      this.errored = false;

      // данные post-запроса

      let url = "/api/enpro_defect?page=" + page;
      if (this.filterName !== "") {
        url += "&search=" + this.filterName;
      }
      if (this.sorting.col !== "") {
        url += "&sortCol=" + this.sorting.col;
        if (this.sorting.direct !== "") {
          url += "&sortDirect=" + this.sorting.direct;
        }
      }
      let filteParametr = "";
      const ctx = this;
      this.contentTh.forEach(function (item, index) {
        if (item.filterValue !== "") {
          ctx.filteredId.push(index);
          filteParametr += `&${item.sortFieldName}=${item.filterValue}`;
        }
      });

      if (filteParametr !== "") {
        url += "&filter";
        url += filteParametr;
      }
      //   console.log(url);
      await axios
        .get(url)
        .then((response) => {
          // для отладки
          //console.log("Загрузка успешно прошла!");
          //console.log(response.data);

          // запрос прошел - записать полученные данные в массив
          this.modelData = response.data;
          // сообщение пользователю
          toastr.success("Данные успешно загружены...");
        })
        .catch((error) => {
          // ошибка
          this.errored = true;
          // для отладки
          //console.log("Ошибка!");
          //console.log(error);
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
    // загрузка данных для фильтра роли пользователя
    async funLoadfilterUserRole() {
      // тело сырого запроса
      let myQuery =
        "SELECT a.id, a.name " +
        "FROM admin_user_roles a " +
        "WHERE (a.deleted_at IS NULL) ORDER BY a.name";

      // выполнеине сырого запроса
      let myReturn = await this.funAnyQuery(myQuery);
      // console.log("Для фильтра - шаг 1");
      // console.log(myReturn);

      if (
        typeof myReturn !== "undefined" &&
        myReturn !== null &&
        myReturn.length > 0
      ) {
        this.contentFilterUserRole = [];
        this.contentFilterUserRole = myReturn;
        // console.log("Для фильтра данные:");
        // console.log(this.contentFilterUserRole);
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
          // // для отладки
          // console.log("Загрузка сырого запроса прошла успешно!");
          // console.log(response.data);

          // запрос прошел
          myData = response.data;
        })
        .catch((error) => {
          // ошибка
          this.errored = true;
          // для отладки
          // console.log("Ошибка!");
          // console.log(error);
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
      this.filterUserRole = 0;
      const ctx = this;
      this.filteredId.forEach(function (ind) {
        ctx.contentTh[ind].filterValue = "";
      });
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

      let id = this.selectedRows[0];

      let url = "/api/enpro_defect/";
      for (let i = 0; i < this.selectedRows.length; i++) {
        let s = i > 0 ? "," : "";
        url += s + this.selectedRows[i];
      }

      await axios
        .delete(url)
        .then((response) => {
          // запрос прошел
          // для отладки
          //console.log("Удаление успешно завершено!");
          //console.log(response);
          // повторная загрузка
          this.funLoadAll();
        })
        .catch((error) => {
          // ошибка
          this.errored = true;
          // для отладки
          toastr.error(
            typeof error.response.data.message
              ? error.response.data.message
              : "Удаление прошло с ошибками!"
          );
          //console.log(error);
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
