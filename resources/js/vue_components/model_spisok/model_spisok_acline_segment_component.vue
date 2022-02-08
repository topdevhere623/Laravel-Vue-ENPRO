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
        v-on:click="funLoad()"
      />
    </div>

    <div class="table-wrapper table-auto-height">
      <table
        class="table custom-table"
        v-if="!editComponents"
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
              <span v-html="item.name"></span>
            </th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="(item, index) in modelData" :key="index">
            <td>
              <label v-if="item.id > 0" class="checkbox">
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
              {{ item.id }}
            </td>
            <td>
              {{ item.name }}
            </td>
            <td>
              <span v-if="item.aclines_count > 1">
                Линий: {{ item.aclines_count }}
                <br />
                {{ item.aclines_text }}
              </span>
            </td>
            <td>
              {{ item.wiremark_name }}
            </td>
            <td>
              {{ item.layingcondition_name }}
            </td>
            <td>
              {{ item.wires }}
            </td>
            <td>
              {{ item.wiren }}
            </td>
            <td>
              {{ item.wirelength }}
            </td>
            <td>
              {{ item.wirephasen }}
            </td>
            <td>
              {{ item.cabelsn }}
            </td>
            <td class="text-nowrap">
              <!-- кнопка начать редактировать -->
              <button
                type="button"
                class="link-icon"
                v-if="!item.regimEdit"
                @click="funEditBegin(index)"
                disabled
              >
                <span class="wb-pencil"></span>
              </button>
              <!-- кнопка сохранить после редактирования -->
              <button
                type="button"
                class="link-icon"
                v-if="item.regimEdit"
                @click="funEditSave(index)"
              >
                <span class="md-thumb-up"></span>
              </button>
              <!-- кнопка отменить редактирование -->
              <button
                type="button"
                class="link-icon"
                v-if="item.regimEdit && item.id > 0"
                @click="funEditEnd(index)"
              >
                <span class="wb-thumb-down"></span>
              </button>
              <button
                type="button"
                class="link-icon"
                v-if="!item.regimEdit"
                @click="funEditComponentsBegin(index)"
              >
                <span class="md-wrench"></span>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
      <!-- редактор компонент -->
      <div v-if="editComponents">
        <!-- крестик -->
        <div class="text-right">
          <span class="wb-close" @click="funEditComponentsEnd()"></span>
        </div>

        <!-- редактирование опоры -->
        <model-edit-acline-segment-component
          :get-model-id="currentSegmentID"
          @updateParent="funUpdateTower"
        >
        </model-edit-acline-segment-component>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  props: {
    getAclineId: Number, // id линии
    getSegments: {}, // список ее опор
  },
  data() {
    return {
      loading: false,
      errored: false,
      editComponents: false, // редактое компонент
      modelData: [],
      contentTh: {
        0: {
          name: "ID",
          width: 50,
        },
        1: {
          name: "Наименование",
          width: "auto",
        },
        2: {
          name: "Совместный подвес",
          width: "auto",
        },
        3: {
          name: "Марка <br>провода",
          width: 75,
        },
        4: {
          name: "Условие<br>прокладки",
          width: 75,
        },
        5: {
          name: "Сечение",
          width: 75,
        },
        6: {
          name: "Кол-во<br>проводов",
          width: 75,
        },
        7: {
          name: "Длина<br>провода",
          width: 75,
        },
        8: {
          name: "Проводов<br>в фазе",
          width: 75,
        },
        9: {
          name: "Кабелей<br>в траншее",
          width: 75,
        },
        10: {
          name: "Действия",
          width: 75,
        },
      },
      selectedAll: false,
      selectedRows: [],
      currentSegmentID: 0,
    };
  },
  mounted() {},
  watch: {
    // слежение за переменной
    getSegments: function (getNewValue) {
      // записать полученные данные в простые данные без вложенных обьектов
      this.funLoad(getNewValue);
    },
  },
  methods: {
    // ------------------------------------------------------------------
    // записать полученные данные в простые данные без вложенных обьектов
    funLoad(getData) {
      // для отладки
      //console.log("Полученные данные по сегментам:");
      //console.log(getData);

      if (getData.length > 0) {
        for (let i = 0; i < getData.length; i++) {
          // имя сегмента, определенное как и в отчете-2 + магситраль/отпайка
          let mySegmentName = "";
          let mySegmentSort = 0;
          if (typeof getData[i].segmentAnaliz !== "undefined") {
            mySegmentName =
              (getData[i].segmentAnaliz.group !== ""
                ? "(" + getData[i].segmentAnaliz.group + ") "
                : "") + getData[i].segmentAnaliz.segmentName;
            mySegmentSort = getData[i].segmentAnaliz.sort;
          }

          this.modelData.push({
            id: getData[i].id,
            name: mySegmentName,
            aclines_count: getData[i].aclinesObject["count"],
            aclines_text: getData[i].aclinesObject["text"],
            sort: mySegmentSort,
            wiremark_name: getData[i].wiremark.assetinfokey,
            layingcondition_name: getData[i].layingcondition.name,
            wires: getData[i].wires,
            wiren: getData[i].wiren,
            wirelength: getData[i].wirelength,
            wirephasen: getData[i].wirephasen,
            cabelsn: getData[i].cabelsn,
            regimEdit: false,
          });
        }

        // отсортировать
        this.modelData.sort((a, b) => a.sort - b.sort);
        //console.log("Отсортированный массив сегментов:");
        //console.log(this.modelData);
      }
    },

    // ------------------------------------------------------------------
    // выделить все
    funSelectedAll() {
      // очистить выделенные и записать всем новый признак
      this.selectedRows = [];
      if (this.modelData.length > 0 && this.selectedAll === true) {
        let mySpisok = [];
        this.modelData.forEach(function (item) {
          mySpisok.push(item.id);
        });
        this.selectedRows = mySpisok;
      }
    },

    // ------------------------------------------------------------------
    // начало редактирования
    funEditBegin(getIndex) {
      // значения до
      this.modelData[getIndex].oldName = this.modelData[getIndex].name;

      // переключить режим редактирования
      this.modelData[getIndex].regimEdit = true;
    },

    // ------------------------------------------------------------------
    // после редактирования - сохранить
    funEditSave(getIndex) {
      // валидация после редактирования
      if (this.funEditValidate(getIndex)) {
        // валидацяи прошла

        // записать в основную ветку
        this.modelData[getIndex].name = this.modelData[getIndex].oldName;

        // завершение редактирования
        this.funEditEnd(getIndex);
        // сохранить
        this.funSave(getIndex);
      }
    },

    // ------------------------------------------------------------------
    // валидация после редактирования
    funEditValidate(getIndex) {
      let myValidate = true;

      if (this.modelData[getIndex].oldName.trim() === "") {
        // сообщение пользователю
        toastr.error("Укажите, пожалуйста, наименование...");
        myValidate = false;
      }

      // возвращаемый параметр
      return myValidate;
    },

    // ------------------------------------------------------------------
    // завершение редактирования
    funEditEnd(getIndex) {
      // переключить режим редактирования
      this.modelData[getIndex].regimEdit = false;
    },

    // ------------------------------------------------------------------
    // функция сохранения строки
    async funSave(getIndex = null) {
      // сообщение пользователю
      toastr.info("Начался процесс сохранения данных...");

      // признаки
      this.loading = true;
      this.errored = false;

      // данные post-запроса
      let form = new FormData();
      form.append("modelName", "Segment");
      form.append("modelData", JSON.stringify(this.modelData[getIndex]));

      axios
        .post("/api/aclineVueSaveOther", form)
        .then((response) => {
          // запрос прошел
          // сообщение пользователю
          toastr.success("Данные успешно сохранены...");
        })
        .catch((error) => {
          // ошибка
          this.errored = true;
          console.log(error);
          // сообщение пользователю
          toastr.error("Ошибка при сохранении данных...");
        })
        .finally(() => {
          // финальная обработка

          // признаки
          this.loading = false;
        });
    },
    // начало редактирования компонент
    funEditComponentsBegin(getIndex) {
      this.currentSegmentID = this.modelData[getIndex].id;
      this.editComponents = true;
    },
    // завершение редактирования компонент
    funEditComponentsEnd() {
      this.editComponents = false;
    },
  },
};
</script>
