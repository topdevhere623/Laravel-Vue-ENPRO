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
        v-if="!editComponents"
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
              :key="item.id"
            >
              {{ item.name }}
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
              {{ item.type }}
            </td>
            <td>
              {{ item.disconnectorinfo_name }}
            </td>
            <td>
              {{ item.tower }}
            </td>
            <td>
              {{ item.span }}
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
        <model-edit-acline-disconnector-component
          :get-model-id="currentDisconnectrId"
          @updateParent="funUpdateTower"
        >
        </model-edit-acline-disconnector-component>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: "acline_disconnector_spisok",
  props: {
    getAclineId: Number, // id линии
    getDisconnectors: {}, // список ее разьединителей
  },
  data() {
    return {
      loading: false,
      errored: false,
      modelData: [],
      editComponents: false,
      contentTh: {
        0: {
          name: "ID",
          width: 50,
        },
        1: {
          name: "Тип",
          width: 75,
        },
        2: {
          name: "Марка",
          width: "auto",
        },
        3: {
          name: "Опора",
          width: 75,
        },
        4: {
          name: "Пролет",
          width: 75,
        },
        5: {
          name: "Действия",
          width: 75,
        },
      },
      selectedAll: false,
      selectedRows: [],
      currentDisconnectrId: 0,
    };
  },
  mounted() {},
  watch: {
    // слежение за переменной
    getDisconnectors: function (getNewValue) {
      // записать полученные данные в простые данные без вложенных обьектов
      this.funLoad(getNewValue);
    },
  },
  methods: {
    // ------------------------------------------------------------------
    // записать полученные данные в простые данные без вложенных обьектов
    funLoad(getData) {
      if (getData.length > 0) {
        for (let i = 0; i < getData.length; i++) {
          this.modelData.push({
            id: getData[i].id,
            type:
              getData[i].type === 2
                ? "реклоузер"
                : getData[i].type === 3
                ? "выключатель нагрузки"
                : "разъединитель",
            disconnectorinfo_name: getData[i].disconnectorinfo.ASSETINFOKEY,
            tower: getData[i].start_i_o.name,
            span: getData[i].span.id,
            regimEdit: false,
          });
        }
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
      form.append("modelName", "Disconnector");
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
      this.currentDisconnectrId = this.modelData[getIndex].id;
      this.editComponents = true;
    },
    // завершение редактирования компонент
    funEditComponentsEnd() {
      this.editComponents = false;
    },
  },
};
</script>