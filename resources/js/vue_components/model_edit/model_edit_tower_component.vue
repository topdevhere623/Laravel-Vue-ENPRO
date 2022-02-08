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

    <div class="example-wrap p-15">
      <div class="nav-tabs-horizontal" data-plugin="tabs">
        <ul class="nav nav-tabs" role="tablist">
          <li class="nav-item" role="presentation">
            <a
              class="nav-link active"
              data-toggle="tab"
              href="#tabMainTower"
              aria-controls="tabMainTower"
              role="tab"
              aria-selected="true"
            >
              Основное
            </a>
          </li>
          <li class="nav-item" role="presentation">
            <a
              class="nav-link"
              data-toggle="tab"
              href="#tabComponents"
              aria-controls="tabComponents"
              role="tab"
              aria-selected="true"
            >
              Компоненты
            </a>
          </li>
          <li class="nav-item" role="presentation">
            <a
              class="nav-link"
              data-toggle="tab"
              href="#tabComponentsItogo"
              aria-controls="tabComponentsItogo"
              role="tab"
              aria-selected="true"
            >
              Спецификация
            </a>
          </li>
          <li class="nav-item" role="presentation">
            <a
              class="nav-link"
              data-toggle="tab"
              href="#tabComponentsAssetForm"
              aria-controls="tabComponentsAssetForm"
              role="tab"
              aria-selected="true"
            >
              Другие данные
            </a>
          </li>
        </ul>
        <div class="tab-content pt-20">
          <!-- вкладка Основное -->
          <div class="tab-pane active" id="tabMainTower" role="tabpanel">
            <div class="row">
              <div class="col-md-6">
                <!-- диспетчерский номер -->
                <div class="form-field">
                  <div class="form-input-label">Диспетчерский номер</div>
                  <input
                    type="text"
                    class="text-field"
                    name="name"
                    v-model="modelData.name"
                    placeholder="диспетчерский номер"
                  />
                </div>

                <!-- марка опоры -->
                <options-sprav-component
                  get-sprav="Towerinfo"
                  get-title="Марка опоры"
                  get-field="towerinfo_id"
                  :get-current-id="modelData.towerinfo_id"
                  @spravSelect="funUpdateSpravTowerinfo"
                >
                </options-sprav-component>

                <!-- материал опоры -->
                <options-sprav-component
                  get-sprav="Towermaterial"
                  get-title="Материал опоры"
                  get-field="towermaterial_id"
                  :get-current-id="modelData.towermaterial_id"
                  @spravSelect="funUpdateSpravTowermaterial"
                >
                </options-sprav-component>

                <!-- назначение опоры-->
                <options-sprav-component
                  get-sprav="Towerkind"
                  get-title="Назначение опоры"
                  get-field="towerkind_id"
                  :get-current-id="modelData.towerkind_id"
                  @spravSelect="funUpdateSpravTowerkind"
                >
                </options-sprav-component>

                <!-- конструкция опоры-->
                <options-sprav-component
                  get-sprav="Towerconstructionkind"
                  get-title="Конструкция опоры"
                  get-field="towerconstruction_id"
                  :get-current-id="modelData.towerconstruction_id"
                  @spravSelect="funUpdateSpravTowerconstructionkind"
                >
                </options-sprav-component>

                <!-- кол-во стоек -->
                <div class="form-field">
                  <select class="form-control" v-model="modelData.propn">
                    <option value="no" selected>не указано</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                  </select>
                  <div class="form-input-label">Кол-во стоек</div>
                </div>

                <!-- оттяжка -->
                <div class="form-field">
                  <select class="form-control" v-model="modelData.guy">
                    <option value="no" selected>нет</option>
                    <option value="left">слева</option>
                    <option value="right">справа</option>
                  </select>
                  <div class="form-input-label">Оттяжка</div>
                </div>

                <!-- подкос -->
                <div class="form-field">
                  <div class="row">
                    <div class="col col-5">
                      <div class="form-field">
                        <select class="form-control" v-model="modelData.strut">
                          <option value="no" selected>нет</option>
                          <option value="concrete">железобетон</option>
                          <option value="wood">дерево</option>
                          <option value="metal">металл</option>
                        </select>
                        <div class="form-input-label">Подкос</div>
                      </div>
                    </div>
                    <div class="col col-7">
                      <div class="form-field">
                        <select class="form-control" v-model="modelData.strutN">
                          <option value="no" selected>не указано</option>
                          <option value="1">1</option>
                          <option value="2">2</option>
                          <option value="3">3</option>
                        </select>
                        <div class="form-input-label">Количество</div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- приставка -->
                <div class="form-field">
                  <select class="form-control" v-model="modelData.annex">
                    <option value="no" selected>нет</option>
                    <option value="metal">металл</option>
                    <option value="concrete">железобетон</option>
                  </select>
                  <div class="form-input-label">Приставка</div>
                </div>
              </div>
              <div class="col-md-6">
                <div style="text-align: right; margin-bottom: 30px">
                  <button
                    type="button"
                    class="button bordered"
                    @click="funSave()"
                  >
                    Сохранить
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- вкладка Компоненты -->
          <div class="tab-pane" id="tabComponents" role="tabpanel">
            <!-- выбор компонент в pivot -->
            <tower-construction-master-pivots-component
              get-model-name="Tower"
              :get-model-id="getModelId"
              @updatePivots="funUpdateConstructionPivots"
            >
            </tower-construction-master-pivots-component>
          </div>

          <!-- вкладка Спецификация -->
          <div class="tab-pane" id="tabComponentsItogo" role="tabpanel">
            <tower-construction-master-itogo-component
              get-model-name="Tower"
              :get-model-id="getModelId"
              :get-last-update="lastUpdateDateTime"
            >
            </tower-construction-master-itogo-component>
          </div>

          <div class="tab-pane" id="tabComponentsAssetForm" role="tabpanel">
            <model-asset-otherdata-component
              get-model-name="Tower"
              :get-model-id="getModelId"
            >
            </model-asset-otherdata-component>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  props: {
    getModelId: Number, // id марки опоры
  },
  data() {
    return {
      loading: false,
      uploading: false,
      errored: false,
      modelData: {
        id: null,
        name: null,
        towerinfo_id: null,
        towermaterial_id: null,
        towerkind_id: null,
        towerconstructionkind_id: null,
        propn: null,
        guy: null,
        strut: null,
        strutN: null,
        annex: null,
      },
      imageRegim: null,
      progressValue: 0, // процент загрузки
      lastUpdateDateTime: null, // время последнего обновления дочернего компонента pivot
    };
  },

  mounted() {
    //console.log("ID для загрузки: " + this.getModelId);
    if (this.getModelId > 0) {
      // id есть - это не новая модель
      // функция загрузки
      this.funLoad();
    }
  },

  // мои методы и функции
  methods: {
    // ------------------------------------------------------------------
    // функция загрузки
    funLoad() {
      // признаки
      this.loading = true;
      this.errored = false;

      // данные post-запроса
      let form = new FormData();
      form.append("modelName", "Tower");
      form.append("modelID", this.getModelId);

      axios
        .post("/api/getModelRecords", form)
        .then((response) => {
          // запрос прошел
          // для отладки
          console.log("Полученные данные по опоре:");
          console.log(response.data);

          // записать полученные данные в массив
          this.modelData.id = response.data.id;
          this.modelData.name = response.data.identifiedobject.name;
          this.modelData.towerinfo_id = response.data.towerinfo_id;
          this.modelData.towermaterial_id = response.data.towermaterial_id;
          this.modelData.towerkind_id = response.data.towerkind_id;
          this.modelData.towerconstructionkind_id =
            response.data.towerconstructionkind_id;
          this.modelData.propn = response.data.propn;
          this.modelData.guy = response.data.guy;
          this.modelData.strut = response.data.strut;
          this.modelData.strutN = response.data.strutN;
          this.modelData.annex = response.data.annex;

          // сообщение пользователю
          toastr.success("Данные успешно загружены...");
        })
        .catch((error) => {
          // ошибка
          this.errored = true;
          // для отладки
          console.log("Ошибка при загрузке данных");
          console.log(error);
          // сообщение пользователю
          toastr.error("Ошибка при загрузке данных...");
        })
        .finally(() => {
          // финальная обработка

          // признаки
          this.loading = false;
        });
    },

    // ------------------------------------------------------------------
    // функция сохранения
    async funSave() {
      // сообщение пользователю
      toastr.info("Начался процесс сохранения данных...");

      // признаки
      this.loading = true;
      this.errored = false;

      // данные post-запроса
      let form = new FormData();
      form.append("modelID", this.getModelId);
      form.append("modelData", JSON.stringify(this.modelData));

      await axios
        .post("/api/towerVueSave", form)
        .then((response) => {
          // запрос прошел

          // проверка на новую запись
          if (!this.getModelId > 0) {
            // записать присвоенное id
            this.getModelId = response.data.id;
            // дописать в url присвоенный id
            history.pushState(
              null,
              null,
              window.location.href + "/" + this.getModelId
            );
          }

          // сообщить об изменениях родителю
          //this.$emit('updatePivots', this.funGetNow());

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
    // клик на выбрать изображение
    funSelectImg(getImageRegim) {
      $("#bFileUpload").trigger("click");
      this.imageRegim = getImageRegim;
    },

    // ------------------------------------------------------------------
    // закачка изображения
    async funUploadImg() {
      // выбранное изображение через обзор
      let myImg = Array.from(event.target.files);
      if (myImg.length === 0) return;
      myImg = myImg[0];

      // признаки
      this.uploading = true;

      // данные post-запроса
      let form = new FormData();
      form.append("image", myImg);
      form.append("getModelDir", "towerinfo");
      form.append("getModelId", this.getModelId);

      await axios
        .post("/api/uploadModelFiles", form, {
          onUploadProgress: (itemUpload) => {
            this.progressValue = Math.round(
              (itemUpload.loaded / itemUpload.total) * 100
            );
          },
        })
        .then((response) => {
          // для отладки
          //console.log(response.data);
          // сгенерированнео имя файла с путем
          let newFullName = response.data;
          // оставить только сгениртрованное имя без пути
          newFullName = newFullName.replace(
            "uploads/models/towerinfo/" + this.getModelId + "/",
            ""
          );
          //console.log("Присвоенное имя изображения после закачки: " + newFullName);
          // записать в массив присвоенное имя фото
          this.modelData[this.imageRegim] = newFullName;
        })
        .catch((error) => {
          // ошибка
          console.log(error);
          // сообщение пользователю
          toastr.error("Ошибка при загрузке изображения...");
        });

      // очистить поле выбора загружаемого файла (чтоб тоже самое изображение можно было выбрать)
      $("#bFileUpload").val("");
      // признаки
      this.uploading = false;

      // сохранить
      this.funSave();
    },

    // ------------------------------------------------------------------
    // событие при обновлении дочернего pivot
    funUpdateConstructionPivots(data) {
      console.log("Обновился дочерний pivot в: " + data);
      this.lastUpdateDateTime = data;
    },

    // ------------------------------------------------------------------
    // событие при обновлении дочернего справочника towerinfo
    funUpdateSpravTowerinfo(data) {
      console.log("Обновился дочерний справочник: " + data);
      this.modelData.towerinfo_id = data;
    },

    // ------------------------------------------------------------------
    // событие при обновлении дочернего справочника towermaterial
    funUpdateSpravTowermaterial(data) {
      console.log("Обновился дочерний справочник: " + data);
      this.modelData.towermaterial_id = data;
    },

    // ------------------------------------------------------------------
    // событие при обновлении дочернего справочника towerkind
    funUpdateSpravTowerkind(data) {
      console.log("Обновился дочерний справочник: " + data);
      this.modelData.towerkind_id = data;
    },

    // ------------------------------------------------------------------
    // событие при обновлении дочернего справочника towerconstructionkind
    funUpdateSpravTowerconstructionkind(data) {
      console.log("Обновился дочерний справочник: " + data);
      this.modelData.towerconstruction_id = data;
    },
  },
};
</script>