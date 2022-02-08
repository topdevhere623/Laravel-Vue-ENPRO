<template>
  <section>
    <div class="page-header">
      <!-- заголовок -->
      <h2 class="page-title">
        {{ titleOne }}
      </h2>
      <!-- хлебные крошки -->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="/admin">Главная</a>
        </li>
<!--        <li class="breadcrumb-item" id="defect-toggle" @click="openDefectInfo">-->
<!--          <a class="link-icon"> Справочник дефектов </a>-->
<!--        </li>-->
        <li class="breadcrumb-item">
          <a href="/admin/enpro_group_defect">{{ titleOne }}</a>
        </li>
        <li class="breadcrumb-item active">
          {{
            getModelId ? `Редактирование ${modelData.title && '- ' + modelData.title}` : `Создание - ${modelData.title && modelData.title !== '' ? modelData.title : titleOne}`
          }}
        </li>
      </ol>

      <!-- действия на странице -->
      <div class="page-header-actions">
        <!-- кнопка выход -->
        <a href="/admin/enpro_group_defect" class="button"> Закрыть </a>
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
                  </ul>
                  <div class="tab-content pt-20">
                    <!-- вкладка Основное -->
                    <div class="tab-pane active" id="tabMain" role="tabpanel">
                      <div class="row">
                        <div class="col-md-6">
                          <!-- type -->
                          <div class="form-field">
                            <div class="form-input-label">
                              Код группы измерений
                            </div>
                            <input
                              type="text"
                              class="text-field"
                              name="group"
                              v-model="modelData.code_group"
                              placeholder="Код группы измерений"
                            />
                          </div>
                          <!-- class -->
                          <div class="form-field">
                            <div class="form-input-label">
                              Название группы измерений
                            </div>
                            <input
                              type="text"
                              class="text-field"
                              name="title"
                              v-model="modelData.title"
                              placeholder="Название группы измерений"
                            />
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
  name: "enprogroupdefect_edit",
  props: {
    getModelId: Number, // id линии,
    titleOne: String,
  },
  data() {
    return {
      loading: false,
      uploading: false,
      errored: false,
      modelData: {
        code_group: null,
        title: null,
      },
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
    // функция загрузки
    funLoad() {
      // признаки
      this.loading = true;
      this.errored = false;

      axios
        .get("/api/enpro_group_defect/" + this.getModelId)
        .then((response) => {
          // запрос прошел
          // для отладки
          // console.log("Полученные данные по всей линии:");
          // console.log(response.data);
          // записать полученные данные в массив
          this.modelData = response.data;

          // сообщение пользователю
          toastr.success("Данные успешно загружены...");
        })
        .catch((error) => {
          // ошибка
          this.errored = true;
          // для отладки
          // console.log("Ошибка при загрузке данных");
          // console.log(error);
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

      if (this.getModelId > 0) {
        await axios
          .put("/api/enpro_group_defect/" + this.getModelId, this.modelData)
          .then((response) => {
            // сообщение пользователю
            toastr.success("Данные успешно сохранены...");
          })
          .catch((error) => {
            // ошибка
            this.errored = true;
            // console.log(error);
            // сообщение пользователю
            toastr.error("Ошибка при сохранении данных...");
          })
          .finally(() => {
            // финальная обработка

            // признаки
            this.loading = false;
          });
      } else {
        await axios
          .post("/api/enpro_group_defect", this.modelData)
          .then((response) => {
            toastr.success("Данные успешно сохранены...");
          })
          .catch((error) => {
            // ошибка
            this.errored = true;
            // console.log(error);
            // сообщение пользователю
            toastr.error("Ошибка при сохранении данных...");
          })
          .finally(() => {
            // финальная обработка

            // признаки
            this.loading = false;
          });
      }
    },
  },
};
</script>