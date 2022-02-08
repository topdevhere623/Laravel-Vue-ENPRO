<template>
  <section>
    <div class="page-header">
      <!-- заголовок -->
      <h2 class="page-title">{{ getTilte }}</h2>

      <!-- хлебные крошки -->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="/admin">Главная</a>
        </li>
        <li class="breadcrumb-item">
          <!-- Дефекты - Критичность -->
          <!-- <a>{{ titleOne }}</a> -->
          {{getTilte}}
        </li>
        <!-- <li class="breadcrumb-item active">
          {{
            getModelId
              ? `Редактирование ${
                  modelData.AssetInfo.CatalogAssetType.IdentifiedObject.name &&
                  "- " +
                    modelData.AssetInfo.CatalogAssetType.IdentifiedObject.name
                }`
              : `Создание - ${
                  modelData.AssetInfo.CatalogAssetType.IdentifiedObject.name &&
                  modelData.AssetInfo.CatalogAssetType.IdentifiedObject.name !==
                    ""
                    ? modelData.AssetInfo.CatalogAssetType.IdentifiedObject.name
                    : titleOne
                }`
          }}
        </li> -->
      </ol>

      <!-- действия на странице -->
      <div class="page-header-actions">
        <!-- кнопка выход -->
        <!-- <a href="/admin/wire_info" class="button"> Сохранить </a>
        <a href="/admin/wire_info" class="button"> Изменить </a> -->
        <a href="/admin" class="button"> Закрыть </a>
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
                  v-on:click="funSave"
                />
              </div>
              <div class="row">
                <div class="col-md-10">
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-input-label">
                        Вид:
                      </div>
                      <multiselect
                      class="rounded"
                      :class="{
                                  'is-invalid-borders':
                                    $v.modelName.$error,
                                }"
                        placeholder="Введите для поиска"
                        v-model="$v.modelName.$model"
                        :options="ModelsParse"
                        :multiple="false"
                        select-label="Выбрать"
                        selected-label="Выбрано"
                        deselect-label="Удалить"
                        :searchable="true"
                        :internal-search="false"
                        :clear-on-select="false"
                        group-values="items"
                        group-label="group"
                        :group-select="false"
                        track-by="modelName"
                        :show-no-results="true"
                        :hide-selected="false"
                        open-direction="bottom"
                        label="title"
                      >
                        <span slot="noResult">Никаких элементов не найдено.</span>
                        <span slot="noOptions"> Список пуст </span>
                      </multiselect>
                    </div>
                    <div class="col-md-4">
                       <div class="form-input-label">
                        Файл:
                      </div>
                      <label  :class="{
                                  'is-invalid-borders':
                                    $v.file.$error,
                                }" class="button bordered excel-load-btn" for="file">
                        Выберите файл
                      </label>
                       <input
                       class="d-none"
                          type="file"
                          id="file"
                          ref="file"
                          v-on:change="handleFileUpload()"
                        />
                        <div>
                            {{getFileName}}
                        </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-2">
                  <button
                    type="button"
                    class="button bordered mt-15 ml-15"
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
  </section>
</template>
<script>
import { required } from "vuelidate/lib/validators";
export default {
  name: "load_excel",
  data() {
    return {
      loading: false,
      errored:false,
      modelName: null,
      file: null,
    };
  },
  validations:{
    modelName:{
      required
    },
    file:{
      required
    }
  },
  props: {
    titleOne: {
      type: String,
    },
    getModels: {},
    getUserRole: {
      type: String,
    },
  },
  computed: {
    ModelsParse: function () {
      return JSON.parse(this.getModels);
    },
    getFileName(){
      if(this.file!==null){
        return this.file.name
      }
    },
    getTilte(){
      return JSON.parse(this.titleOne).name;
    }
  },
  methods: {
    handleFileUpload() {
      this.$v.file.$model = this.$refs.file.files[0];
    },
    resetForm(){
      this.$v.file.$model=null
      this.$v.modelName.$model=null
    },
    funSave() {
      this.$v.$touch();
      if (this.$v.$invalid) {
        toastr.error("Ошибка при заполнений формы...");
        return;
      }
      const formData = new FormData();
      formData.append("file", this.file);
      this.loading=true
      axios
        .post(`loadExcel/model/${this.modelName.modelName}`, formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then((response)=> {
          toastr.success("Данные успешно сохранены...");
          this.resetForm()
          this.$v.$reset()
        })
        .catch((error) =>{
          this.errored = true;
          toastr.error("Ошибка при сохранении данных...");
        }).finally(()=>{
          this.loading=false
        })
    },
  },
};
</script>
<style>
  .excel-load-btn{
    height: auto;
    padding-bottom: 11px;
    padding-top: 11px;
  }
</style>