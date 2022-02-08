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
    <div class="col-md-12 filter-container collapse" id="collapseTableFilter">
      <div class="filter-bar row">
        <div class="col-md-6">
          <div class="form-field">
            <label>Функциональное назначение</label>
            <select-with-search
              get-label="ru_value"
              @select="filter.functionId = $event"
              @loading="loadStatus"
              @loadingDone="loadStatus"
              get-model-name="TransformerFunctionKind"
              get-title="Функциональное назначение"
              get-url="all_kind/model/TransformerFunctionKind"
              get-id="TransformerFunctionKind-1"
              :multi="true"
              :addItem="false"
              :resetValue="resetMaterial"
              @reset="resetMaterial = false"
            >
            </select-with-search>
          </div>
        </div>
        <div class="input-range-slider col-md-6">
          <div class="form-group mb-0">
            <label for="#nom-min">Номинальное напряжение обмоток</label>
            <div class="d-flex r-input-container mb-2">
              <div class="input-group form-field">
                <input
                  type="text"
                  class="form-control text-field"
                  v-model.number="filter.ratedU.min"
                  id="nom-min"
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
                  v-model.number="filter.ratedU.max"
                  id="nom-max"
                  placeholder="Максимальное"
                  aria-describedby="inputGroupPrepend3"
                  required
                />
                <div class="input-group-prepend">
                  <span class="input-group-text" id="inputGroupPrepend3"
                    >кВ</span
                  >
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="input-range-slider col-md-6">
          <div class="form-group mb-0">
            <label for="#nom-min">Номинальная мощность обмоток</label>
            <div class="d-flex r-input-container mb-2">
              <div class="input-group form-field">
                <input
                  type="text"
                  class="form-control text-field"
                  v-model.number="filter.ratedS.min"
                  id="nom-min"
                  placeholder="Минимальное"
                  aria-describedby="inputGroupPrepend4"
                  required
                />
                <div class="input-group-prepend">
                  <span class="input-group-text" id="inputGroupPrepend4"
                    >кВA</span
                  >
                </div>
              </div>
              <div class="input-group form-field">
                <input
                  type="text"
                  class="form-control text-field"
                  v-model.number="filter.ratedS.max"
                  id="nom-max"
                  placeholder="Максимальное"
                  aria-describedby="inputGroupPrepend5"
                  required
                />
                <div class="input-group-prepend">
                  <span class="input-group-text" id="inputGroupPrepend5"
                    >кВA</span
                  >
                </div>
              </div>
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
              <a :href="`old_transformer_tank_info/edit/${item.id}`">
                {{ item.id }}
              </a>
            </td>
            <td class="text-center">
              <a
                v-if="
                  item.TransformerTankInfo.AssetInfo.CatalogAssetType
                    .IdentifiedObject.name
                "
                :href="`old_transformer_tank_info/edit/${item.id}`"
              >
                {{
                  item.TransformerTankInfo.AssetInfo.CatalogAssetType
                    .IdentifiedObject.name
                }}
              </a>
            </td>
            <td class="text-center">
              <a
                v-if="item.constructionKind"
                :href="`old_transformer_tank_info/edit/${item.id}`"
              >
                {{ item.constructionKind.ru_value }}
              </a>
            </td>
            <td class="text-center">
              <a
                v-if="item.coreCoilsWeight"
                :href="`old_transformer_tank_info/edit/${item.id}`"
              >
                {{ item.coreCoilsWeight.value }}
              </a>
            </td>
            <td class="text-center">
              <a
                v-if="item.coreKind"
                :href="`old_transformer_tank_info/edit/${item.id}`"
              >
                {{ item.coreKind.ru_value }}
              </a>
            </td>
            <td class="text-center">
              <a
                v-if="item.function"
                :href="`old_transformer_tank_info/edit/${item.id}`"
              >
                {{ item.function.ru_value }}
              </a>
            </td>
            <td class="text-center">
              <a
                v-if="item.coolingKind"
                :href="`old_transformer_tank_info/edit/${item.id}`"
              >
                {{ item.coolingKind.ru_value }}
              </a>
            </td>
            <td class="text-center">
              <a
                v-if="item.enproFullWeight"
                :href="`old_transformer_tank_info/edit/${item.id}`"
              >
                {{ item.enproFullWeight.value }}
              </a>
            </td>
            <td class="text-center">
              <a
                v-if="item.enproOilWeight"
                :href="`old_transformer_tank_info/edit/${item.id}`"
              >
                {{ item.enproOilWeight.value }}
              </a>
            </td>
            <td class="text-center">
              <a
                v-if="item.enproTemperatureRange"
                :href="`old_transformer_tank_info/edit/${item.id}`"
              >
                <span
                  v-if="
                    item.enproTemperatureRange.minTemperature.value !== null
                  "
                  >от
                  {{ item.enproTemperatureRange.minTemperature.value }} °C</span
                >
                <span
                  v-if="
                    item.enproTemperatureRange.maxTemperature.value !== null
                  "
                >
                  до
                  <span
                    v-if="item.enproTemperatureRange.maxTemperature.value > 0"
                    >+</span
                  >
                  {{ item.enproTemperatureRange.maxTemperature.value }} °C
                </span>
              </a>
            </td>
            <td class="text-center">
              <a
                v-if="item.enproGost"
                :href="`old_transformer_tank_info/edit/${item.id}`"
              >
                {{ item.enproGost.name }}
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
            Object.keys(selectedRows).length > 0 &&
            (getUserRole === 'vendor' ||
              getUserRole === 'admin' ||
              getUserRole === 'manager' ||
              getUserRole === 'operator')
          "
          class="link-icon"
          @click="funSelectedRows('delete')"
          >Удалить выбранные</a
        >
      </div>
      <!-- пагинация -->
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
  name: "spisok_oldtransformertankinfo",
  props: {
    getUserRole: {
      required: true,
      type: String,
    },
  },
  mixins: [spisokLiveSearch],
  data() {
    return {
      loading: false,
      errored: false,
      modelData: {},
      filterIsset: false,
      resetMaterial: false,
      contentTh: {
        0: {
          name: "ID",
          sortFieldName: "id",
          width: 50,
        },
        2: {
          name: "Наименование",
          sortFieldName: "TransformerTankInfo",
          width: "auto",
        },
        3: {
          name: "Тип конструкции",
          sortFieldName: "constructionKind",
          width: "auto",
        },
        4: {
          name: "Вес активной части",
          sortFieldName: "coreCoilsWeight",
          width: "auto",
        },
        5: {
          name: "Тип сердечника",
          sortFieldName: "coreKind",
          width: "auto",
        },
        6: {
          name: "Функциональное назначение",
          sortFieldName: "function",
          width: "auto",
        },
        7: {
          name: "Вид охлаждения",
          sortFieldName: "coolingKind",
          width: "auto",
        },
        8: {
          name: "Масса полная",
          sortFieldName: "enproFullWeight",
          width: "auto",
        },
        9: {
          name: "Масса масла",
          sortFieldName: "enproOilWeight",
          width: "auto",
        },
        10: {
          name: "Диапазон рабочих температур",
          sortFieldName: "enproTemperatureRange",
          width: "auto",
        },
        11: {
          name: "ГОСТ",
          sortFieldName: "enproGost",
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
      filter: {
        functionId: [],
        ratedU: {
          min: null,
          max: null,
        },
        ratedS: {
          min: null,
          max: null,
        },
      },
    };
  },
  mounted() {
    this.funLoadAll();
  },
  methods: {
    clearFiltering() {
      this.filterIsset = false;
      this.resetMaterial = true;
      this.filter = {
        functionId: [],
        ratedU: {
          min: null,
          max: null,
        },
        ratedS: {
          min: null,
          max: null,
        },
      };
    },
    funFiltering() {
      console.log("strart filtering");
      this.funLoadContent();
      if (
        this.filter.ratedU.min !== null ||
        this.filter.ratedU.max !== null ||
        this.filter.ratedS.min !== null ||
        this.filter.ratedS.max !== null ||
        this.filter.functionId?.length > 0
      ) {
        this.filterIsset = true;
      }
    },
    loadStatus(val) {
      this.loading = val;
    },
    funLoadAll() {
      this.funLoadContent();
    },
    funLoadContent(page = 1) {
      this.loading = true;
      this.errored = false;

      let url = `/api/oldTransformerTankInfo?page=${page}`;
      if (this.filterName.length > 0) {
        url += `&search=${this.filterName}`;
      }
      if (this.sorting.col !== "") {
        url += "&sortCol=" + this.sorting.col;
        if (this.sorting.direct !== "") {
          url += "&sortDirect=" + this.sorting.direct;
        }
      }

      if (this.filter.ratedU.min) {
        url += "&ratedUMin=" + this.filter.ratedU.min;
      }
      if (this.filter.ratedU.max) {
        url += "&ratedUMax=" + this.filter.ratedU.max;
      }
      if (this.filter.ratedS.min) {
        url += "&ratedSMin=" + this.filter.ratedS.min;
      }
      if (this.filter.ratedS.max) {
        url += "&ratedSMax=" + this.filter.ratedS.max;
      }

      if (this.filter.functionId?.length > 0) {
        this.filter.functionId.forEach((mid) => {
          url += "&functionId[]=" + mid;
        });
      }

      axios
        .get(url)
        .then((response) => {
          this.modelData = response.data;
        })
        .catch((error) => {
          this.errored = true;
          toastr.error("Ошибка при загрузке данных...");
        })
        .finally(() => {
          this.loading = false;

          this.selectedAll = false;
          this.selectedRows = [];
        });
    },

    funSearchClear() {
      this.filterName = "";
      this.filterBaseVoltage = 0;
      this.filterAclineStatus = 0;

      this.funLoadContent();
    },
    funSelectedAll() {
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
      if (!confirm("Вы уверены, что хотите удалить выделенные записи?")) return;

      this.loading = true;
      this.errored = false;

      let form = new FormData();
      form.append("selectedRows", this.selectedRows);

      // await axios
      //     .delete("/api/oldTransformerTankInfo", form)
      //     .then(response => {
      //         this.funLoadAll();
      //     })
      //     .catch(error => {
      //         this.errored = true;
      //         toastr.error("Ошибка при обработке данных...");
      //         console.log(error);
      //     })
      //     .finally(() => {
      //         this.loading = false;
      //     });

      if (Object.keys(this.selectedRows).length === 1) {
        await axios
          .delete(`/api/oldTransformerTankInfo/${this.selectedRows[0]}`)
          .then((response) => {
            this.funLoadAll();
          })
          .catch((error) => {
            this.errored = true;
            toastr.error("Ошибка при обработке данных...");
            console.log(error);
          })
          .finally(() => {
            this.loading = false;
          });
      } else if (Object.keys(this.selectedRows).length > 1) {
        toastr.error("Массовое удаление пока не поддерживается!");
        this.loading = false;
      } else {
        toastr.error("Выберите хотябы одну запись для удаления!");
        this.loading = false;
      }
    },
    funSorting(getCol) {
      if (this.sorting.col !== getCol) {
        this.sorting.direct = "asc";
      } else {
        this.sorting.direct = this.sorting.direct === "asc" ? "desc" : "asc";
      }

      this.sorting.col = getCol;

      this.funLoadContent();
    },
  },
};
</script>
