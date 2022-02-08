<template>
  <section>
    <div class="row align-items-end">
      <div class="col-md-12">
        <div v-if="errored" class="alert alert-danger" role="alert">
          Запрос к серверу не прошел! Попробуйте, пожалуйста, позже!
          <img
            src="/public/uploads/icons/reload.svg"
            style="width: 25px; margin-left: 5px"
            v-on:click="loadOptinsAndGroupTypes"
            alt=""/>
        </div>
      </div>
      <div class="col-md-9">
        <div class="form-field">
          <div class="form-field-label">
            {{ formLabel }}
          </div>
          <multiselect
            @input="changeList"
            v-model="value"
            :options="options"
            :multiple="true"
            select-label="Выбрать"
            selected-label="Выбрано"
            deselect-label="Удалить"
            placeholder="Поиск ..."
            track-by="name"
            :custom-label="nameWithGroup"
          >
            <span slot="noResult">Никаких элементов не найдено.</span>
            <span slot="noOptions"> Список пуст </span>
          </multiselect>
        </div>
      </div>
      <div class="col-md-3">
        <button
          type="button"
          class="button"
          data-toggle="modal"
          data-target="#add-group-modal"
          data-whatever="@mdo"
          @click="customInputFocus"
        >
          Добавить
        </button>
      </div>
    </div>
    <div
      class="modal fade"
      id="add-group-modal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-dark">
              {{ modalLabel }}
            </h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="addNewAssetgroup">
              <div class="form-field">
                <label for="group-name" class="form-input-label"
                >Название группы</label
                >
                <input
                  required
                  v-model="newGroup.name"
                  type="text"
                  class="text-field"
                  id="group-name"
                />
              </div>
              <div class="form-field">
                <label for="group-type" class="form-input-label"
                >Тип группы:</label
                >
                <select
                  required
                  v-model="newGroup.asset_group_kind_id"
                  name="group-type"
                  class="text-field"
                >
                  <!---->
                  <option disabled="disabled">не указано</option>
                  <option
                    v-for="(item, index) in groupTypes"
                    :key="index"
                    :value="item.id"
                  >
                    <span>
                      {{ item.description }}
                    </span>
                  </option>
                </select>
              </div>
              <div class="form-field">
                <button type="submit" class="button">Сохранить</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
<script>
  import Multiselect from "vue-multiselect";

  export default {
    name: "multi-select-group",
    props: {
      formLabel: {
        type: String,
        required: true,
      },
      modalLabel: {
        type: String,
        required: true,
      },
      getSlectedIds: {
        type: Array,
      },
      getModelName: {
        type: String,
        required: true,
      },
      getModelKindName: {
        type: String,
        required: true,
      },
    },
    components: {Multiselect},
    data() {
      return {
        newGroup: {
          name: null,
          asset_group_kind_id: null,
        },
        groupTypes: null,
        options: [],
        value: [],
        errored: false,
      };
    },
    methods: {
      //добавляем в выбранные то что выбралпользователь
      addSelectedIds(ids) {
        console.log("add selectes");
        this.value = [];
        for (const id of ids) {
          console.log('selected id', id)
          for (const option of this.options) {
            if (id.id === option.id) {
              this.value.push(option);
            }
          }
        }
      },
      //кастомная
      nameWithGroup({name, kind}) {
        return `${name}—${kind.description}`;
      },
      //bootsrap modal custom focus
      customInputFocus() {
        $("#add-group-modal").on("shown.bs.modal", function () {
          $("#group-name").trigger("focus");
        });
      },
      // передать родителю выбранный список групп
      changeList() {
        this.$emit("input", this.value);
      },
      //Получение списка типов групп Asset-ов
      async groupTypesLoad() {
        await axios
          .get(`/api/${this.getModelKindName}`)
          .then((response) => {
            // console.log(response)
            this.groupTypes = response.data;
            toastr.success("Данные успешно загружены...");
          })
          .catch((error) => {
            this.errored = true;
            toastr.error("Ошибка при загрузке данных...");
          });
      },
      //Добавление новой группы Asset-ов
      async addNewAssetgroup() {
        toastr.info("Начался процесс сохранения данных...");
        //console.log(this.newGroup);
        await axios
          .post(`/api/${this.getModelName}`, this.newGroup)
          .then((response) => {
            // console.log('сохранение новой группы')
            // console.log(response)
            toastr.success("Данные успешно сохранены...");
            this.value.push(response.data);
            this.changeList();
            this.loadOptions();
            this.newGroup.name = null;
            this.asset_group_kind_id = null;
            $("#add-group-modal").modal("hide");
          })
          .catch((error) => {
            toastr.error("Ошибка при сохранении данных...");
            this.errored = true;
          });
      },
      async loadOptions() {
        await axios
          .get(`/api/${this.getModelName}`)
          .then((response) => {
            if (response.data.length > 0) {
              this.options = response.data;
              toastr.success("Данные успешно загружены...");
              console.log(this.getSlectedIds)
              if (this.getSlectedIds) {
                this.addSelectedIds(this.getSlectedIds);
              }
            }
          })
          .catch((error) => {
            toastr.error("Ошибка при загрузке данных...");
            //   console.log("ошибка loadOptions");
            // console.log(error)
            this.errored = true;
          });
      },
      loadOptinsAndGroupTypes() {
        this.loadOptions();
        this.groupTypesLoad();
      },
    },
    watch: {
      getSlectedIds: {
        deep: true,
        handler(val) {
          if (val) {
            this.addSelectedIds(val);
          }
        }
      }
    },
    mounted() {
      //запроса
      this.loadOptinsAndGroupTypes();
    },
  };
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style>
.multiselect__option--highlight {
  background: #171636;
}

.multiselect__option--highlight::after {
  background: #171636;
}

.multiselect__tag {
  background: #273661;
}

.multiselect__tag-icon:focus,
.multiselect__tag-icon:hover {
  background: #171636;
}

.multiselect__tag-icon:after {
  color: #fff;
}

.multiselect__tag-icon:after:hover {
  color: red;
}

.multiselect__tags {
  border: 1px solid #273661;
  background: #273661;
}

.multiselect__input,
.multiselect__single {
  background: #273661;
  color: #fff;
}
</style>
