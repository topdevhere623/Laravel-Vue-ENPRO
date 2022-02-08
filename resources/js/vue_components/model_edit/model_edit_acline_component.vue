<template>
  <section>
    <div class="page-header">
      <!-- заголовок -->
      <h2 class="page-title">
        ЛЭП
        <span v-if="modelData.name != null && modelData.name.length > 0">
          - {{ modelData.name.slice(0, 50) }}
        </span>
      </h2>

      <!-- хлебные крошки -->
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin">Главная</a></li>
        <li class="breadcrumb-item"><a href="/admin/acline">ЛЭП</a></li>
        <li v-if="getModelId > 0" class="breadcrumb-item">
          <a :href="'/admin/acline/map/edit/' + getModelId">На карте</a>
        </li>
        <li class="breadcrumb-item active">Редактирование</li>
      </ol>

      <!-- действия на странице -->
      <div class="page-header-actions">
        <!-- кнопка выход -->
        <a href="/admin/acline" class="button"> Выход </a>
      </div>
    </div>

    <!-- содержимое страницы-->
    <div class="page-content main-content">
      <div class="row row-lg">
        <div class="col-lg-12">
          <div class="panel panel-bordered form-icons">
            <div class="panel-body">
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

              <div class="example-wrap">
                <div class="nav-tabs-horizontal" data-plugin="tabs">
                  <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                      <a
                        class="nav-link active"
                        data-toggle="tab"
                        href="#tabMain"
                        aria-controls="tabMain"
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
                        href="#tabSegment"
                        aria-controls="tabSegment"
                        role="tab"
                        aria-selected="true"
                      >
                        Сегменты
                      </a>
                    </li>
                    <li class="nav-item" role="presentation">
                      <a
                        class="nav-link"
                        data-toggle="tab"
                        href="#tabSpan"
                        aria-controls="tabSpan"
                        role="tab"
                        aria-selected="true"
                      >
                        Пролеты
                      </a>
                    </li>
                    <li class="nav-item" role="presentation">
                      <a
                        class="nav-link"
                        data-toggle="tab"
                        href="#tabTower"
                        aria-controls="tabTower"
                        role="tab"
                        aria-selected="true"
                      >
                        Опоры
                      </a>
                    </li>
                    <li class="nav-item" role="presentation">
                      <a
                        class="nav-link"
                        data-toggle="tab"
                        href="#tabCustomer"
                        aria-controls="tabCustomer"
                        role="tab"
                        aria-selected="true"
                      >
                        Потребители
                      </a>
                    </li>
                    <li class="nav-item" role="presentation">
                      <a
                        class="nav-link"
                        data-toggle="tab"
                        href="#tabDisconnector"
                        aria-controls="tabDisconnector"
                        role="tab"
                        aria-selected="true"
                      >
                        Разьединители
                      </a>
                    </li>
                    <li class="nav-item" role="presentation">
                      <a
                        class="nav-link"
                        data-toggle="tab"
                        href="#tabDischarger"
                        aria-controls="tabDischarger"
                        role="tab"
                        aria-selected="true"
                      >
                        Разрядники
                      </a>
                    </li>
                    <li class="nav-item" role="presentation">
                      <a
                        class="nav-link"
                        data-toggle="tab"
                        href="#tabCrossing"
                        aria-controls="tabCrossing"
                        role="tab"
                        aria-selected="true"
                      >
                        Пересечения
                      </a>
                    </li>
                    <li class="nav-item" role="presentation">
                      <a
                        class="nav-link"
                        data-toggle="tab"
                        href="#tabOtherData"
                        aria-controls="tabCrossing"
                        role="tab"
                        aria-selected="true"
                      >
                        Другие данные
                      </a>
                    </li>
                    <li class="nav-item" role="presentation">
                      <a
                        class="nav-link"
                        data-toggle="tab"
                        href="#tabActivityRecord"
                        aria-controls="tabCrossing"
                        role="tab"
                        aria-selected="true"
                      >
                        Activity record
                      </a>
                    </li>
                  </ul>
                  <div class="tab-content pt-20">
                    <!-- вкладка Основное -->
                    <div class="tab-pane active" id="tabMain" role="tabpanel">
                      <div class="row">
                        <div class="col-md-6">
                          <!-- наименование -->
                          <div class="form-field">
                            <div class="form-input-label">Наименование</div>
                            <input
                              type="text"
                              class="text-field"
                              name="name"
                              v-model="modelData.name"
                              placeholder="наименование"
                            />
                          </div>

                          <!-- выбор из справочника vue-компонент -->
                          <options-sprav-component
                            get-sprav="BaseVoltage"
                            get-title="Класс напряжения"
                            get-field="voltage_id"
                            :get-current-id="modelData.voltage_id"
                            @spravSelect="funUpdateSpravBaseVoltage"
                          >
                          </options-sprav-component>

                          <!-- выбор из справочника vue-компонент -->
                          <options-sprav-component
                            get-sprav="AclineStatus"
                            get-title="Статусы линии"
                            get-field="status_id"
                            :get-current-id="modelData.status_id"
                            @spravSelect="funUpdateSpravAclineStatus"
                          >
                          </options-sprav-component>
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

                    <!-- вкладка Сегменты -->
                    <div class="tab-pane" id="tabSegment" role="tabpanel">
                      <model-spisok-acline-segment-component
                        :get-acline-id="getModelId"
                        :get-segments="modelData.segments"
                      >
                      </model-spisok-acline-segment-component>
                    </div>

                    <!-- вкладка Пролеты -->
                    <div class="tab-pane" id="tabSpan" role="tabpanel">
                      <model-spisok-acline-span-component
                        :get-acline-id="getModelId"
                        :get-spans="modelData.spans"
                      >
                      </model-spisok-acline-span-component>
                    </div>

                    <!-- вкладка Опоры -->
                    <div class="tab-pane" id="tabTower" role="tabpanel">
                      <model-spisok-acline-tower-component
                        :get-acline-id="getModelId"
                        :get-towers="modelData.towers"
                      >
                      </model-spisok-acline-tower-component>
                    </div>

                    <!-- вкладка Потребители -->
                    <div class="tab-pane" id="tabCustomer" role="tabpanel">
                      <model-spisok-acline-customer-component
                        :get-acline-id="getModelId"
                        :get-customers="modelData.customers"
                      >
                      </model-spisok-acline-customer-component>
                    </div>

                    <!-- вкладка Разьединители -->
                    <div class="tab-pane" id="tabDisconnector" role="tabpanel">
                      <model-spisok-acline-disconnector-component
                        :get-acline-id="getModelId"
                        :get-disconnectors="modelData.disconnectors"
                      >
                      </model-spisok-acline-disconnector-component>
                    </div>

                    <!-- вкладка Разрядники -->
                    <div class="tab-pane" id="tabDischarger" role="tabpanel">
                      <model-spisok-acline-discharger-component
                        :get-acline-id="getModelId"
                        :get-dischargers="modelData.dischargers"
                      >
                      </model-spisok-acline-discharger-component>
                    </div>

                    <!-- вкладка Пересечения -->
                    <div class="tab-pane" id="tabCrossing" role="tabpanel">
                      <model-spisok-acline-crossing-component
                        :get-acline-id="getModelId"
                        :get-crossings="modelData.crossings"
                      >
                      </model-spisok-acline-crossing-component>
                    </div>
                    <!-- вкладка другие данные --->
                    <div class="tab-pane" id="tabOtherData" role="tabpanel">
                      <model-asset-otherdata-component
                        get-model-name="Acline"
                        :get-model-id="getModelId"
                      >
                      </model-asset-otherdata-component>
                    </div>
                    <!-- вкладка все записи Activity record--->
                    <div
                      class="tab-pane"
                      id="tabActivityRecord"
                      role="tabpanel"
                    >
                      <model-asset-activityrecord-component>
                      </model-asset-activityrecord-component>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: "acline_edit",
  props: {
    getModelId: Number, // id линии
  },
  data() {
    return {
      loading: false,
      uploading: false,
      errored: false,
      modelData: {
        id: null,
        asset_id: null,
        name: null,
        towermaterial_id: null,
        img: null,
        segments: {},
        spans: {},
        towers: {},
        customers: {},
        disconnectors: {},
        dischargers: {},
        crossings: {},
        voltage_id: null,
        status_id: null,
      },
      imageRegim: null,
      progressValue: 0, // процент загрузки
      lastUpdateDateTime: null, // время последнего обновления дочернего компонента pivot
    };
  },

  mounted() {
    console.log("ID для загрузки: " + this.getModelId);
    if (this.getModelId > 0) {
      // id есть - это не новая модель
      // функция загрузки
      this.funLoad();
    }
  },

  // мои методы и функции
  methods: {
    //при созданий asset обновляем acline
    // aa(x) {
    //     // // this.modelData.asset_id= x
    //     // this.funSave()
    //     console.log("other data-" + x);
    // },
    // ------------------------------------------------------------------
    // функция загрузки
    funLoad() {
      // признаки
      this.loading = true;
      this.errored = false;

      // данные post-запроса
      let form = new FormData();
      form.append("modelID", this.getModelId);

      axios
        .post("/api/aclineVueLoad", form)
        .then((response) => {
          // запрос прошел
          // для отладки
          console.log("Полученные данные по всей линии:");
          console.log(response.data);

          // записать полученные данные в массив
          this.modelData.id = response.data.acline.id;
          this.modelData.name = response.data.acline.identifiedobject.name;
          this.modelData.segments = response.data.aclinesegments;
          this.modelData.spans = response.data.spans;
          this.modelData.towers = response.data.towers;
          this.modelData.customers = response.data.customers;
          this.modelData.disconnectors = response.data.disconnectors;
          this.modelData.dischargers = response.data.dischargers;
          this.modelData.crossing = response.data.crossing;
          this.modelData.asset_id = response.data.acline.asset_id;
          console.log("asset id", response.data.acline.asset_id);
          this.modelData.voltage_id =
            response.data.acline.identifiedobject.voltage_id;
          this.modelData.status_id = response.data.acline.aclinestatus.id;

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
        .post("/api/aclineVueSave", form)
        .then((response) => {
          // запрос прошел

          // проверка на новую запись
          if (!this.getModelId > 0) {
            // записать присвоенное id
            this.getModelId = response.data.acline.id;
            // дописать в url присвоенный id
            history.pushState(
              null,
              null,
              window.location.href + "/" + this.getModelId
            );
          }

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
      form.append("getModelDir", "acline");
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
            "uploads/models/acline/" + this.getModelId + "/",
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
    // событие при обновлении дочернего справочника towermaterial
    funUpdateSpravBaseVoltage(data) {
      console.log("Обновился дочерний справочник: " + data);
      this.modelData.voltage_id = data;
    },

    // ------------------------------------------------------------------
    // событие при обновлении дочернего справочника aclinestatus
    funUpdateSpravAclineStatus(data) {
      console.log("Обновился дочерний справочник: " + data);
      this.modelData.status_id = data;
    },
  },
};
</script>
