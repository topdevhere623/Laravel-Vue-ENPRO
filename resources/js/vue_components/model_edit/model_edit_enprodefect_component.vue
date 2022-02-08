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
          <!-- Дефекты - Критичность -->
          <a href="/admin/enpro_defect">{{ titleOne }}</a>
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
        <a href="/admin/enpro_defect" class="button"> Закрыть </a>
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
                        <div class="col-md-8">
                          <!-- код -->
                          <div class="row mb-4">
                            <div class="col-md-3">
                              <div class="form-field">
                                <div class="form-input-label">Код</div>
                                <input
                                  type="text"
                                  class="text-field"
                                  name="code"
                                  v-model.trim="modelData.code"
                                  placeholder="Код"
                                />
                              </div>
                            </div>
                            <div class="col-md-9">
                              <div class="form-field">
                                <div class="form-input-label">Дефект</div>
                                <input
                                  type="text"
                                  class="text-field"
                                  name="title"
                                  v-model.trim="modelData.title"
                                  placeholder="Название группы кодов"
                                />
                              </div>
                            </div>
                          </div>

                          <!-- Группа измерений Группы дефектов -->
                          <div class="row mb-4">
                            <div class="col-md-3">
                              <div class="form-field">
                                <div class="form-input-label">Код ГИ</div>
                                <input
                                  type="text"
                                  class="text-field"
                                  name="code"
                                  v-model.trim="
                                    modelData.enpro_group_defect.code_group
                                  "
                                  placeholder="Код ГИ"
                                  @keyup.enter="groupSearch"
                                />
                              </div>
                            </div>
                            <div class="col-md-9">
                              <div class="form-field">
                                <template v-if="enproGroup">
                                  <div class="form-field">
                                    <div class="form-field-label">
                                      Группа измерений
                                    </div>
                                    <multiselect
                                      ref="multiselect"
                                      v-model="groupValue"
                                      :options="enproGroupList"
                                      @input="groupSelect"
                                      placeholder="Выберите"
                                      select-label="Выбрать"
                                      selected-label="Выбрано"
                                      deselect-label="Удалить"
                                      label="title"
                                      track-by="id"
                                      @close="isOpen = false"
                                      @open="isOpen = true"
                                    >
                                      <span slot="noResult"
                                        >Никаких элементов не найдено.</span
                                      >
                                      <span slot="noOptions">
                                        Список пуст
                                      </span>
                                    </multiselect>
                                  </div>
                                </template>
                                <template v-else> Загрузка ... </template>
                              </div>
                            </div>
                          </div>
                          <div class="row mb-4">
                            <div class="col-md-3">
                              <div class="form-field">
                                <div class="form-input-label">Код ГО</div>
                                <input
                                  type="text"
                                  class="text-field"
                                  name="codey"
                                  v-model.trim="
                                    modelData.enpro_class_defect.class
                                  "
                                  placeholder="Код ГО"
                                  @keyup.enter="classSearch"
                                />
                              </div>
                            </div>
                            <div class="col-md-9">
                              <div class="form-field">
                                <template v-if="enproClass">
                                  <div class="form-field">
                                    <div class="form-field-label">
                                      Группа оборудования
                                    </div>
                                    <multiselect
                                      ref="multiselectClass"
                                      placeholder="Выберите"
                                      v-model="classValue"
                                      :options="enproClassList"
                                      @input="classSelect"
                                      select-label="Выбрать"
                                      selected-label="Выбрано"
                                      deselect-label="Удалить"
                                      label="title"
                                      track-by="id"
                                      @close="isClassOpen = false"
                                      @open="isClassOpen = true"
                                    >
                                      <span slot="noResult"
                                        >Никаких элементов не найдено.</span
                                      >
                                      <span slot="noOptions">
                                        Список пуст
                                      </span>
                                    </multiselect>
                                  </div>
                                </template>
                                <template v-else> Загрузка ... </template>
                              </div>
                            </div>
                          </div>
                          <!-- class -->
                          <div class="form-field">
                            <div class="form-input-label">Критичность</div>
                            <input
                              type="number"
                              class="text-field"
                              name="critical"
                              v-model="modelData.critical"
                              placeholder="Критичность"
                            />
                          </div>
                        </div>
                        <div class="col-md-4">
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
import Multiselect from "vue-multiselect";
export default {
  name: "enprodefect_edit",
  props: {
    getModelId: {
      type: Number,
      default: 0,
    }, // id линии,
    titleOne: String,
  },
  data() {
    return {
      isOpen: false,
      isClassOpen: false,
      groupValue: {},
      classValue: {},
      loading: false,
      uploading: false,
      errored: false,
      enproClass: null,
      enproClassList: null,
      enproGroup: null,
      enproGroupList: null,
      gr: null,
      modelData: {
        id: null,
        code: null,
        class_id: null,
        group_id: null,
        title: null,
        critical: null,
        enpro_group_defect: {
          code_group: null,
        },
        enpro_class_defect: {
          class: null,
        },
      },
    };
  },
  components: { Multiselect },
  mounted() {
    this.funLoad();
  },
  created: function () {
    this.debouncedFilter = _.debounce(this.groupSearch, 1000);
    this.debouncedFilterClass = _.debounce(this.classSearch, 1000);
  },
  computed: {
    groupFilterCode() {
      return this.modelData.enpro_group_defect.code_group;
    },
    classFilterCode() {
      return this.modelData.enpro_class_defect.class;
    },
  },
  watch: {
    groupFilterCode: function () {
      this.debouncedFilter();
    },
    classFilterCode: function () {
      this.debouncedFilterClass();
    },
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
    groupSelect() {
      if (this.groupValue !== null) {
        this.modelData.enpro_group_defect.code_group =
          this.groupValue.code_group;
        this.enproGroupList = this.enproGroup;
      } else {
        this.modelData.enpro_group_defect.code_group = null;
        this.enproGroupList = this.enproGroup;
      }
    },

    classSelect() {
      if (this.classValue !== null) {
        this.modelData.enpro_class_defect.class = this.classValue.class;
        this.enproClassList = this.enproClass;
      } else {
        this.modelData.enpro_class_defect.class = null;
        this.enproClassList = this.enproClass;
      }
    },
    groupSearch() {
      const res = _.filter(this.enproGroup, (item) => {
        let groupCode = String(item.code_group);
        let searchGroupCode = String(
          this.modelData.enpro_group_defect.code_group
        );
        if (groupCode.toLowerCase().includes(searchGroupCode.toLowerCase())) {
          return true;
        }
      });

      if (res.length === 1) {
        this.groupValue = res[0];
      } else if (res.length > 1) {
        this.enproGroupList = res;
        this.$refs.multiselect.$el.focus();
        this.isOpen = true;
      } else {
        this.isOpen = false;
      }
    },
    classSearch() {
      const res = _.filter(this.enproClass, (item) => {
        let classCode = String(item.class);
        let searchClassCode = String(this.modelData.enpro_class_defect.class);
        if (classCode.toLowerCase().includes(searchClassCode.toLowerCase())) {
          return true;
        }
      });
      if (res.length === 1) {
        this.classValue = res[0];
      } else if (res.length > 1) {
        this.enproClassList = res;
        this.$refs.multiselectClass.$el.focus();
        this.isClassOpen = true;
      } else {
        this.isClassOpen = false;
      }
    },
    // ------------------------------------------------------------------
    // функция загрузки
    async funLoad() {
      // признаки
      this.loading = true;
      this.errored = false;
      await axios
        .get("/api/enpro_defect/" + this.getModelId)
        .then((response) => {
          // запрос прошел
          // записать полученные данные в массив
          if (response.data.data) {
            this.modelData = response.data.data;
          }
          this.enproGroup = response.data.enproGroup;
          this.enproGroupList = response.data.enproGroup;
          this.enproClass = response.data.enproClass;
          this.enproClassList = response.data.enproClass;
          // console.log(this.modelData.title)

          // сообщение пользователю
          toastr.success("Данные успешно загружены...");
        })
        .catch((error) => {
          // ошибка
          this.errored = true;
          // для отладки

          toastr.error("Ошибка при загрузке данных...");
        })
        .finally(() => {
          // финальная обработка
          // console.log('finally')
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
      if (this.getModelId > 0) {
        const data = {};

        data.code = this.modelData.code;
        data.class_id = this.classValue.id;
        data.group_id = this.groupValue.id;
        data.critical = this.modelData.critical;
        data.title = this.modelData.title;

        await axios
          .put("/api/enpro_defect/" + this.getModelId, data)
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
        const data = {};

        data.code = this.modelData.code;
        data.class_id = this.classValue.id;
        data.group_id = this.groupValue.id;
        data.critical = this.modelData.critical;
        data.title = this.modelData.title;

        await axios
          .post("/api/enpro_defect", data)
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
