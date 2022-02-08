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
      <a
        class="search-bar-filter-btn"
        :class="{ 'search-bar-filter-btn-dot': filterIsset }"
        data-toggle="collapse"
        href="#collapseTableFilter"
        role="button"
        aria-expanded="false"
        aria-controls="collapseTableFilter"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          xmlns:xlink="http://www.w3.org/1999/xlink"
          xmlns:svgjs="http://svgjs.com/svgjs"
          version="1.1"
          width="36"
          height="36"
          x="0"
          y="0"
          viewBox="0 0 512 512"
          style="enable-background: new 0 0 512 512"
          xml:space="preserve"
          class=""
        >
          <g>
            <g xmlns="http://www.w3.org/2000/svg">
              <path
                d="m420.404 0h-328.808c-50.506 0-91.596 41.09-91.596 91.596v328.809c0 50.505 41.09 91.595 91.596 91.595h328.809c50.505 0 91.595-41.09 91.595-91.596v-328.808c0-50.506-41.09-91.596-91.596-91.596zm61.596 420.404c0 33.964-27.632 61.596-61.596 61.596h-328.808c-33.964 0-61.596-27.632-61.596-61.596v-328.808c0-33.964 27.632-61.596 61.596-61.596h328.809c33.963 0 61.595 27.632 61.595 61.596z"
                fill="#ffffff"
                data-original="#000000"
                style=""
                class=""
              />
              <path
                d="m432.733 112.467h-228.461c-6.281-18.655-23.926-32.133-44.672-32.133s-38.391 13.478-44.672 32.133h-35.661c-8.284 0-15 6.716-15 15s6.716 15 15 15h35.662c6.281 18.655 23.926 32.133 44.672 32.133s38.391-13.478 44.672-32.133h228.461c8.284 0 15-6.716 15-15s-6.716-15-15.001-15zm-273.133 32.133c-9.447 0-17.133-7.686-17.133-17.133s7.686-17.133 17.133-17.133 17.133 7.686 17.133 17.133-7.686 17.133-17.133 17.133z"
                fill="#ffffff"
                data-original="#000000"
                style=""
                class=""
              />
              <path
                d="m432.733 241h-35.662c-6.281-18.655-23.927-32.133-44.672-32.133s-38.39 13.478-44.671 32.133h-228.461c-8.284 0-15 6.716-15 15s6.716 15 15 15h228.461c6.281 18.655 23.927 32.133 44.672 32.133s38.391-13.478 44.672-32.133h35.662c8.284 0 15-6.716 15-15s-6.716-15-15.001-15zm-80.333 32.133c-9.447 0-17.133-7.686-17.133-17.133s7.686-17.133 17.133-17.133 17.133 7.686 17.133 17.133-7.686 17.133-17.133 17.133z"
                fill="#ffffff"
                data-original="#000000"
                style=""
                class=""
              />
              <path
                d="m432.733 369.533h-164.194c-6.281-18.655-23.926-32.133-44.672-32.133s-38.391 13.478-44.672 32.133h-99.928c-8.284 0-15 6.716-15 15s6.716 15 15 15h99.928c6.281 18.655 23.926 32.133 44.672 32.133s38.391-13.478 44.672-32.133h164.195c8.284 0 15-6.716 15-15s-6.716-15-15.001-15zm-208.866 32.134c-9.447 0-17.133-7.686-17.133-17.133s7.686-17.133 17.133-17.133 17.133 7.685 17.133 17.132-7.686 17.134-17.133 17.134z"
                fill="#ffffff"
                data-original="#000000"
                style=""
                class=""
              />
            </g>
          </g>
        </svg>
      </a>
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
    <div class="col-md-12 filter-container collapse" id="collapseTableFilter">
      <div class="filter-bar row">
        <div class="col-md-6">
          <div class="form-field">
            <label>Материал</label>
            <select-with-search
              get-label="ru_value"
              @select="filter.materialIds = $event"
              @loading="loadStatus"
              @loadingDone="loadStatus"
              get-model-name="WireMaterialKind"
              get-title="Материал"
              get-url="all_kind/model/WireMaterialKind"
              get-id="filterWireMaterial-1"
              :multi="true"
              :addItem="false"
              :resetValue="resetMaterial"
              @reset="resetMaterial = false"
            >
            </select-with-search>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-field">
            <label>Материал изоляции</label>
            <select-with-search
              get-label="ru_value"
              @select="filter.insulationMaterialIds = $event"
              @loading="loadStatus"
              @loadingDone="loadStatus"
              get-model-name="WireInsulationKind"
              get-title="Материал"
              get-url="all_kind/model/WireInsulationKind"
              get-id="filterWinsulationMaterialIds-1"
              :multi="true"
              :addItem="false"
              :resetValue="resetMaterialInsulation"
              @reset="resetMaterialInsulation = false"
            >
            </select-with-search>
          </div>
        </div>
        <div class="input-range-slider col-md-6">
          <div class="form-group mb-0">
            <label for="#nom-min">Номинальное напряжение</label>
            <div class="d-flex r-input-container mb-2">
              <div class="input-group form-field">
                <input
                  type="text"
                  class="form-control text-field"
                  v-model.number="filter.nominalVoltage.min"
                  id="validationDefaultUsername"
                  placeholder="Минимальное"
                  aria-describedby="inputGroupPrepend2"
                  required
                />
                <div class="input-group-prepend">
                  <span class="input-group-text" id="inputGroupPrepend2"
                    >кВ</span
                  >
                </div>
              </div>
              <div class="input-group form-field">
                <input
                  type="text"
                  class="form-control text-field"
                  v-model.number="filter.nominalVoltage.max"
                  id="validationDefaultUsername"
                  placeholder="Максимальное"
                  aria-describedby="inputGroupPrepend2"
                  required
                />
                <div class="input-group-prepend">
                  <span class="input-group-text" id="inputGroupPrepend2"
                    >кВ</span
                  >
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-input-label mb-2">Изолированный</div>
          <div class="d-flex">
            <div class="d-flex align-items-center">
              <input
                type="radio"
                value="1"
                v-model="filter.isInsulated"
                id="insulationTrue"
              />
              <label class="form-check-label pl-0 ml-1" for="insulationTrue">
                да
              </label>
            </div>
            <div class="d-flex align-items-center ml-4">
              <input
                type="radio"
                value="0"
                v-model="filter.isInsulated"
                id="insulationFalse"
              />
              <label class="form-check-label pl-0 ml-1" for="insulationFalse">
                нет
              </label>
            </div>
            <div class="d-flex align-items-center ml-4">
              <input
                type="radio"
                :value="null"
                v-model="filter.isInsulated"
                id="insulationNull"
              />
              <label class="form-check-label pl-0 ml-1" for="insulationNull">
                Не выбрано
              </label>
            </div>
          </div>
        </div>

        <div class="col-md-12">
          <div class="d-flex align-items-center justify-content-end">
            <button
              type="button"
              class="
                mr-4
                button
                bordered
                d-flex
                align-items-center
                justify-content-center
                text-center
              "
              style="min-width: 140px"
              @click="clearFiltering()"
            >
              <span>Сбросить</span>
            </button>
            <button
              type="button"
              class="
                button
                bordered
                d-flex
                align-items-center
                justify-content-center
                text-center
              "
              style="min-width: 140px"
              @click="funFiltering()"
            >
              <span>Применить</span>
            </button>
          </div>
        </div>
      </div>
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
            <template v-for="(item, index) in contentTh">
              <th
                v-if="item.display"
                :key="index"
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
              </th>
            </template>
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
              <a
                @click="saveQuery"
                :href="`/admin/${modelRoute[getModelName]}/edit/${item.id}`"
              >
                {{ item.id }}
              </a>
            </td>
            <td>
              <a
                @click="saveQuery"
                :href="`/admin/${modelRoute[getModelName]}/edit/${item.id}`"
              >
                {{ item.AssetInfo.CatalogAssetType.IdentifiedObject.name }}
              </a>
            </td>
            <td>
              <a
                @click="saveQuery"
                :href="`/admin/${modelRoute[getModelName]}/edit/${item.id}`"
              >
                {{ item.listInsulated ? "Да" : "Нет" }}
              </a>
            </td>
            <td>
              <a
                @click="saveQuery"
                :href="`/admin/${modelRoute[getModelName]}/edit/${item.id}`"
              >
                {{ item.listMaterial }}
              </a>
            </td>
            <td>
              <a
                @click="saveQuery"
                :href="`/admin/${modelRoute[getModelName]}/edit/${item.id}`"
              >
                {{
                  item.listNominalVoltage !== null
                    ? item.listNominalVoltage + " кВ"
                    : ""
                }}
              </a>
            </td>
            <td>
              <a
                @click="saveQuery"
                :href="`/admin/${modelRoute[getModelName]}/edit/${item.id}`"
              >
                {{ item.phasesCount }}
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
        <a
          class="link-icon"
          v-if="
            getUserRole === 'vendor' ||
            getUserRole === 'admin' ||
            getUserRole === 'manager' ||
            getUserRole === 'operator'
          "
          @click="funSelectedRows('delete')"
          >Удалить выбранные</a
        >
        <a
          v-if="
            getUserRole === 'vendor' ||
            getUserRole === 'admin' ||
            getUserRole === 'manager' ||
            getUserRole === 'operator'
          "
          @click="copyRow($event)"
          class="link-icon copy_link"
          href=""
          >Скопировать</a
        >
      </div>

      <!-- пагинация -->
      <div v-if="Object.keys(modelData).length > 0" class="right">
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
import Multiselect from "vue-multiselect";
export default {
  name: "wire_info_spisok",
  props: {
    getUserRole: String,
    titleOne: String,
    getModelName: String,
  },
  components: {
    Multiselect,
  },
  mixins: [spisokLiveSearch],
  data() {
    return {
      isSaving: false,
      loading: false,
      errored: false,
      collapseValue: false,
      modelData: {},
      filterIsset: false,
      wireMaterialKind: [],
      resetMaterialInsulation: false,
      resetMaterial: false,
      modelRoute: {
        CableInfo: "cable_info",
        OverheadWireInfo: "overhead_wire_info",
      },
      contentTh: {
        0: {
          name: "ID",
          sortFieldName: "id",
          width: 50,
          display: true,
        },
        1: {
          name: "Наименование марки оборудования",
          sortFieldName: "name",
          width: "auto",
          display: true,
        },
        2: {
          name: "Изолированный",
          sortFieldName: "listInsulated",
          width: "auto",
          display: true,
        },
        3: {
          name: "Материал",
          sortFieldName: "listMaterial",
          width: "auto",
          display: true,
        },
        4: {
          name: "Номинальное напряжение",
          sortFieldName: "listNominalVoltage",
          width: "auto",
          display: true,
        },
        5: {
          name: "Количество фаз",
          sortFieldName: "phasesCount",
          width: "auto",
          display: true,
        },
      },
      selectedAll: false,
      selectedRows: [],
      filterName: "",
      sorting: { col: "", direct: "" },
      filter: {
        nominalVoltage: {
          min: null,
          max: null,
        },
        materialIds: [],
        insulationMaterialIds: [],
        isInsulated: null,
      },
    };
  },

  mounted() {
    // общая загрузка
    if (this.getModelName === "CableInfo") {
      this.contentTh[3].name = "Материал жилы";
    }
    this.funLoadAll();
  },
  methods: {
    loadStatus(val) {
      this.loading = val;
    },
    setRangeValues(range) {
      this.filter.nomninalVoltage.min = range.min;
      this.filter.nomninalVoltage.max = range.max;
    },
    // ------------------------------------------------------------------
    // общая загрузка
    funLoadAll() {
      if (localStorage.getItem("table_search")) {
        if (localStorage.getItem("spisok_model") === this.getModelName) {
          this.filterName = localStorage.getItem("table_search");
        } else {
          // загрузка содержимого таблицы
          this.funLoadContent();
        }
        localStorage.removeItem("table_search");
      } else {
        // загрузка содержимого таблицы
        this.funLoadContent();
      }
    },

    // ------------------------------------------------------------------
    // загрузка содержимого таблицы
    funLoadContent(page = 1) {
      // сообщение пользователю

      // признаки
      this.loading = true;
      this.errored = false;

      let currentPage = page;
      if (localStorage.getItem("spisok_model")) {
        if (localStorage.getItem("table_pagination")) {
          if (localStorage.getItem("spisok_model") === this.getModelName) {
            currentPage = localStorage.getItem("table_pagination");
          }
          localStorage.removeItem("table_pagination");
        }
        localStorage.removeItem("spisok_model");
      }

      // данные post-запроса
      let url = `/api/modelName/${this.getModelName}/wireAssemblyInfo?page=${currentPage}`;
      if (this.filterName.length > 0) {
        url += `&search=${this.filterName}`;
      }
      if (this.sorting.col !== "") {
        url += "&sortCol=" + this.sorting.col;
        if (this.sorting.direct !== "") {
          url += "&sortDirect=" + this.sorting.direct;
        }
      }

      if (this.filter.nominalVoltage.min) {
        url += "&nominalVoltageMin=" + this.filter.nominalVoltage.min;
      }
      if (this.filter.nominalVoltage.max) {
        url += "&nominalVoltageMax=" + this.filter.nominalVoltage.max;
      }
      if (this.filter.materialIds?.length > 0) {
        this.filter.materialIds.forEach((mid) => {
          url += "&materialIds[]=" + mid;
        });
      }
      if (this.filter.insulationMaterialIds?.length > 0) {
        this.filter.insulationMaterialIds.forEach(function (wmid) {
          url += "&insulationMaterialIds[]=" + wmid;
        });
      }
      if (this.filter.isInsulated !== null) {
        url += "&isInsulated=" + this.filter.isInsulated;
      }

      axios
        .get(url)
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
          this.collapseValue = true;
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
    // групповая обработка строк (delete / copy)
    async funSelectedRows(getRegim) {
      // вопрос Пользователю

      if (!confirm("Вы уверены, что хотите удалить выделенные записи?")) return;

      // признаки
      this.loading = true;
      this.errored = false;
      if (Object.keys(this.selectedRows).length === 1) {
        await axios
          .delete(
            `/api/modelName/${this.getModelName}/wireAssemblyInfo/${this.selectedRows[0]}`
          )
          .then((response) => {
            toastr.info("Удаление выполнено успешно");
            this.funLoadContent();
          })
          .catch((error) => {
            this.errored = true;
            toastr.error("Ошибка при обработке данных...");
            console.log(error);
          });
      } else if (Object.keys(this.selectedRows).length > 1) {
        toastr.error("Массовое удаление пока не поддерживается!");
        this.loading = false;
      } else {
        toastr.error("Выберите хотябы одну запись для удаления!");
        this.loading = false;
      }
    },
    funFiltering() {
      this.resetMultiSelectValues = false;
      console.log("strart filtering");
      this.funLoadContent();
      if (
        this.filter.nominalVoltage.min !== null ||
        this.filter.nominalVoltage.max !== null ||
        this.filter.materialIds?.length > 0 ||
        this.filter.insulationMaterialIds?.length > 0 ||
        this.filter.isInsulated !== null
      ) {
        this.filterIsset = true;
      }
    },
    clearFiltering() {
      this.filterIsset = false;
      this.resetMaterialInsulation = true;
      this.resetMate
      rial = true;
      this.filter = {
        nominalVoltage: {
          min: null,
          max: null,
        },
        materialIds: [],
        insulationMaterialIds: [],
        isInsulated: null,
      };
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

    copyRow(e) {
      e.preventDefault();
      if (this.selectedRows.length === 1) {
        window.location.href += `/edit?fromId=${this.selectedRows[0]}`;
      } else {
        toastr.error("Выберите одну запись");
      }
    },
    saveQuery() {
      if (this.modelData.meta?.pagination) {
        localStorage.setItem(
          "table_pagination",
          this.modelData.meta?.pagination.current_page
        );
      }
      if (this.filterName !== "") {
        localStorage.setItem("table_search", this.filterName);
      }
      localStorage.setItem("spisok_model", this.getModelName);
    },
  },
};
</script>
<style lang="scss">
.search-bar {
  align-items: center;
  &-filter-btn {
    line-height: 1;
    margin-right: 10px;
  }
}
.filter-bar {
  background-color: #171636;
  padding: 10px 20px;
  row-gap: 15px;
}
.input-range-slider {
  .input-group-prepend {
    padding: 0 10px !important;
    background-color: #202346;
  }
  .form-control {
    padding: 10px;
    height: initial;
  }
  .r-input-container {
    column-gap: 15px;
    margin-bottom: 50px;
    .form-field {
      margin-top: 0;
    }
  }
}
.search-bar-filter-btn {
  position: relative;
  &-dot::after {
    content: "";
    display: block;
    width: 10px;
    height: 10px;
    position: absolute;
    right: -3px;
    top: -3px;
    background: #2ce32c;
    border-radius: 50%;
  }
}
</style>
