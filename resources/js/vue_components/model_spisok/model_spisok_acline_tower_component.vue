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
      <!-- список опор в линии -->
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
          >
            {{ item.name }}
          </th>
        </tr>
        </thead>

        <tbody>
        <tr v-for="(item, index) in modelData">
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
            <div v-if="!item.regimEdit" @dblclick="funEditBegin(index)">
              {{ item.name }}
            </div>
            <input
              v-else
              type="text"
              class="form-control"
              autocomplete="off"
              v-model="item.oldName"
            />
          </td>
          <td class="text-center">
            <div v-if="item.photo !== ''">
              <a
                :href="'/public/uploads/models/map/photos/' + item.photo"
                target="_blank"
              >
                <img
                  :src="'/public/uploads/models/map/photos/' + item.photo"
                  class="mb-5 h-50"
                />
              </a>
              <br/>
              <small> {{ item.kol_photos }} шт. </small>
            </div>
          </td>
          <td>
              <span v-if="item.aclines_count > 1">
                Линий: {{ item.aclines_count }}
                <br/>
                {{ item.aclines_text }}
              </span>
          </td>
          <td>
            {{ item.fict_tp }}
          </td>
          <td>
            {{ item.towerinfo_name }}
          </td>
          <td>
            {{ item.towermaterial_name }}
          </td>
          <td>
            {{ item.towerkind_name }}
          </td>
          <td class="text-nowrap">
            <!-- кнопка начать редактировать -->
            <button
              type="button"
              class="link-icon"
              v-if="!item.regimEdit"
              @click="funEditBegin(index)"
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
            <!-- кнопка редактор компонент -->
            <button
              type="button"
              class="link-icon"
              v-if="!item.regimEdit"
              @click="funEditComponentsBegin(index)"
            >
              <span class="md-wrench"></span>
            </button>
            <!-- показать на карте -->
            <a
              :href="
                  '/admin/acline/map/edit/' +
                  getAclineId +
                  '?fly_lat=' +
                  item.lat +
                  '&fly_long=' +
                  item.long
                "
              class="link-icon"
              v-if="!item.regimEdit"
            >
              <span class="md-pin-drop"></span>
            </a>
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
        <model-edit-tower-component
          :get-model-id="currentTowerID"
          @updateParent="funUpdateTower"
        >
        </model-edit-tower-component>
      </div>
    </div>
  </section>
</template>

<script>
  import modelSpisok from "../../mixins/modelSpisok";

  export default {
    mixins: [modelSpisok],
    props: {
      getAclineId: Number, // id линии
      getTowers: {}, // список ее опор
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
            name: "Дисп.номер",
            width: "auto",
          },
          2: {
            name: "Фото",
            width: "75",
          },
          3: {
            name: "Совместный подвес",
            width: "auto",
          },
          4: {
            name: "Фикт.ТП",
            width: 75,
          },
          5: {
            name: "Марка",
            width: 100,
          },
          6: {
            name: "Материал",
            width: 75,
          },
          7: {
            name: "Назначение",
            width: 75,
          },
          8: {
            name: "Действия",
            width: 75,
          },
        },
        selectedAll: false,
        selectedRows: [],
        currentTowerID: 0,
      };
    },
    mounted() {
    },
    watch: {
      // слежение за переменной
      getTowers: function (getNewValue) {
        // записать полученные данные в простые данные без вложенных обьектов
        this.funLoad(getNewValue);
      },
    },
    methods: {
      // ------------------------------------------------------------------
      // записать полученные данные в простые данные без вложенных обьектов
      funLoad(getData) {
        if (getData.length > 0) {
          console.log(getData);
          for (let i = 0; i < getData.length; i++) {
            // фото
            let myPhotos = []
            if (!!getData[i].photos) {
              myPhotos = JSON.parse(getData[i].photos);
            }

            this.modelData.push({
              id: getData[i].id,
              name: getData[i].viewName, // при выборке всех опор линии сюда уже подставится нужное имя поры (проверка и по старому, и по новому)
              lat: getData[i].identifiedobject["lat"],
              long: getData[i].identifiedobject["long"],
              kol_photos: myPhotos.length,
              photo: myPhotos.length > 0 ? myPhotos[0] : "",
              oldName: "",
              aclines_count: getData[i].aclinesObject["count"],
              aclines_text: getData[i].aclinesObject["text"],
              fict_tp: getData[i].fict_tp === 1 ? "да" : "",
              towerinfo_name: getData[i].towerinfo.name,
              towermaterial_name: getData[i].towermaterial.name,
              towerkind_name: getData[i].towerkind.name,
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
          toastr.error("Укажите, пожалуйста, диспетчерский номер...");
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
        form.append("modelName", "Tower");
        form.append("modelData", JSON.stringify(this.modelData[getIndex]));
        form.append("aclineID", this.getAclineId);

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

      // ------------------------------------------------------------------
      // начало редактирования компонент
      funEditComponentsBegin(getIndex) {
        this.currentTowerID = this.modelData[getIndex].id;
        this.editComponents = true;
      },

      // ------------------------------------------------------------------
      // завершение редактирования компонент
      funEditComponentsEnd() {
        this.editComponents = false;
      },

      // ------------------------------------------------------------------
      // событие при обновлении дочернего обьекта
      funUpdateTower(data) {
        console.log("Обновился дочерний обьект");
      },
    },
  };
</script>
