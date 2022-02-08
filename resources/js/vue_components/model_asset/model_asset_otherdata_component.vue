<template>
  <section>
    <div class="row">
      <div class="col-md-10">
        <!-- диспетчерский номер -->
        <div class="form-field">
          <model-edit-multigroupselect-component
            form-label="Группы:"
            modal-label="Добавление группы"
            :get-slected-ids="
              modelData.AssetGroups ? modelData.AssetGroups : null
            "
            get-model-name="assetgroup"
            get-model-kind-name="assetgroupkind"
            @input="getGroupList($event)"
          ></model-edit-multigroupselect-component>
        </div>
        <div class="row form-field-2-column">
          <div class="form-field col-md-6">
            <div class="form-input-label">Name</div>
            <input
              type="text"
              class="text-field"
              name="Name"
              v-model="modelData.IdentifiedObject.name"
              placeholder="Name"
            />
          </div>
          <div class="form-field col-md-6">
            <div class="form-input-label">AliasName</div>
            <input
              type="text"
              class="text-field"
              name="AliasName"
              v-model="modelData.IdentifiedObject.names[0].name"
              placeholder="AliasName"
            />
          </div>

          <div class="form-field col-md-6">
            <div class="form-input-label">initialCondition</div>
            <input
              type="number"
              class="text-field"
              name="InitialCondition"
              v-model="modelData.initialcondition"
              placeholder="initialCondition"
            />
          </div>
          <div class="form-field col-md-6">
            <div class="form-input-label">initialLossOfLife</div>
            <input
              type="number"
              step="0.1"
              class="text-field"
              name="InitialLossOfLife"
              v-model="modelData.initiallossoflife"
              placeholder="initialLossOfLife"
            />
          </div>
          <div class="form-field col-md-6">
            <div class="form-input-label">type</div>
            <input
              type="text"
              class="text-field"
              name="Type"
              v-model="modelData.type"
              placeholder="type"
            />
          </div>
          <div class="form-field col-md-6">
            <div class="form-input-label">utcNumber</div>
            <input
              type="text"
              class="text-field"
              name="utcNumber"
              v-model="modelData.utc_number"
              placeholder="utcNumber"
            />
          </div>
          <div class="form-field col-md-6">
            <div class="form-input-label">serialNumber</div>
            <input
              type="text"
              class="text-field"
              name="serialNumber"
              v-model="modelData.serialnumber"
              placeholder="serialNumber"
            />
          </div>
          <div class="form-field col-md-6">
            <div class="form-input-label">lotNumber</div>
            <input
              type="text"
              class="text-field"
              name="lotNumber"
              v-model="modelData.lot_number"
              placeholder="lotNumber"
            />
          </div>
          <div class="form-field col-md-6">
            <div class="form-input-label">purchasePrice</div>
            <input
              type="number"
              step="0.1"
              class="text-field"
              name="PurchasePrice"
              v-model="modelData.purchaseprice"
              placeholder="PurchasePrice"
            />
          </div>
          <div class="form-field col-md-6">
            <div class="form-input-label">electronicAddress</div>
            <input
              type="email"
              class="text-field"
              name="ElectronicAddress"
              v-model="modelData.electronic_address"
              placeholder="ElectronicAddress"
            />
          </div>
          <div class="form-field col-md-6">
            <template v-if="classDefect">
              <div>
                <div class="form-field-label">Группа оборудования</div>
                <multiselect
                  ref="multiselect"
                  v-model="defectClassValue"
                  :options="classDefect"
                  placeholder="Группа оборудования"
                  select-label="Выбрать"
                  selected-label="Выбрано"
                  deselect-label="Удалить"
                  label="title"
                  track-by="id"
                >
                  <span slot="noResult">Никаких элементов не найдено.</span>
                  <span slot="noOptions"> Список пуст </span>
                </multiselect>
              </div>
            </template>
            <template v-else> Загрузка ...</template>
          </div>
        </div>
      </div>
      <div class="col-md-2">
        <div style="text-align: right; margin-bottom: 30px">
          <button type="button" class="button bordered" @click="funSave()">
            Сохранить
          </button>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
  import Multiselect from "vue-multiselect";

  export default {
    props: {
      getModelName: {
        required: true,
        type: String,
      },
      getModelId: {
        type: Number,
      },
    },
    data() {
      return {
        loading: false,
        errored: false,
        classDefect: null,
        defectClassValue: null,
        modelData: {
          id: null,
          IdentifiedObject: {
            name: null,
            names: [
              {
                name: null
              }
            ]
          },
          type: null,
          utc_number: null,
          serialnumber: null,
          lot_number: null,
          purchaseprice: null,
          electronic_address: null,
          initialcondition: null,
          initiallossoflife: null,
          AssetGroups: null,
          enpro_class_defect_id: null,
          //имитируем запись для ассет
          critical: false,
          activiyRecords: [],
        },
      };
    },
    computed: {
      getDefectClassValueId: function () {
        if (this.defectClassValue) {
          return this.defectClassValue.id;
        } else {
          return null;
        }
      },
    },
    created() {
      // console.log('iddd',this.getModelId)
      // console.log('namme',this.getModelName)
      // функция загрузки данных
      if (this.getModelId > 0) {
        this.funLoad();
      }
      this.loadDefectClass();
    },
    components: {Multiselect},
    // мои методы и функции
    methods: {
      getGroupList(list) {
        const group_ids = [];
        for (const g_id of list) {
          group_ids.push({
            id: g_id.id
          });
        }
        this.modelData.AssetGroups = group_ids;
        // console.log(this.AssetGroups)
      },
      // функция загрузки
      async loadDefectClass() {
        this.loading = true;
        this.errored = false;
        let url = "/api/enpro_class_defect?all";
        await axios
          .get(url)
          .then((response) => {
            // для отладки
            //console.log("Загрузка успешно прошла!");
            //console.log(response.data);

            // запрос прошел - записать полученные данные в массив
            this.classDefect = response.data.data;
            // сообщение пользователю
            toastr.success("Данные успешно загружены...");
          })
          .catch((error) => {
            // ошибка
            this.errored = true;
            // для отладки
            // console.log("Ошибка! дефекты");
            // console.log(error);
            // сообщение пользователю
            toastr.error("Ошибка при загрузке данных...");
          })
          .finally(() => {
            // финальная обработка
            this.loading = false;
          });
      },
      async funLoad() {
        // признаки
        this.loading = true;
        this.errored = false;
        // имитация загрузки по api
        // console.log("loading asset");
        // данные post-запроса
        // let form = new FormData();
        // form.append("modelName", this.getModelName);
        // form.append("modelID", this.getModelId);

        await axios
          .get(`/api/assets/model/${this.getModelName}/id/${this.getModelId}`)
          .then((response) => {
            // запрос прошел
            // для отладки
            console.log("Полученные данные:");
            console.log(response.data);
            if (response.data.data.asset !== null) {
              this.modelData.type = response.data.data.asset.type;
              this.modelData.id = response.data.data.asset.id;
              this.modelData.utc_number = response.data.data.asset.utc_number;
              this.modelData.lot_number = response.data.data.asset.lot_number;
              this.modelData.purchaseprice =
                response.data.data.asset.purchaseprice;
              this.modelData.electronic_address =
                response.data.data.asset.electronic_address;
              this.modelData.initialcondition =
                response.data.data.asset.initialcondition;
              this.modelData.initiallossoflife =
                response.data.data.asset.initiallossoflife;
              this.modelData.AssetGroups = response.data.data.asset.AssetGroups;
              this.modelData.IdentifiedObject = response.data.data.asset.IdentifiedObject
              this.modelData.serialnumber = response.data.data.asset.serialnumber;
              this.modelData.enpro_class_defect_id =
                response.data.data.asset.enpro_class_defect_id;
              // if (response.data.data.asset.enpro_class_defect_id !== null) {
              //   console.log("111");
              //   this.addSelectedIds(
              //     response.data.data.asset.enpro_class_defect_id
              //   );
              // }
              this.loadSelectedClass(
                response.data.data.asset.enpro_class_defect_id
              );
              if (!this.modelData.IdentifiedObject.names || this.modelData.IdentifiedObject.names.length === 0) {
                this.$set(this.modelData.IdentifiedObject, 'names', [{name: null}])
              }
              // сообщение пользователю
              toastr.success("Данные успешно загружены...");
            }
            console.log('modelData', this.modelData)
            // console.log("aaa");
            // console.log(response);
          })
          .catch((error) => {
            // ошибка
            this.errored = true;
            // для отладки
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

      async loadSelectedClass(id) {
        // признаки
        this.loading = true;
        this.errored = false;
        if (id > 0) {
          await axios
            .get("/api/enpro_class_defect/" + id)
            .then((response) => {
              // запрос прошел
              // для отладки
              // console.log("Полученные данные по всей линии:");
              // console.log(response.data);
              // записать полученные данные в массив
              this.defectClassValue = response.data;

              // console.log(this.modelData.title)

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
        }
      },

      addSelectedIds(id) {
        this.defectClassValue = null;
        for (const option of this.classDefect) {
          if (id === option.id) {
            this.defectClassValue = option;
          }
        }
      },
      // ------------------------------------------------------------------
      // функция загрузки данных
      // функция сохранения
      async funSave() {
        // сообщение пользователю
        toastr.info("Начался процесс сохранения данных...");
        let data = {
          id: this.modelData.id,
          IdentifiedObject: this.modelData.IdentifiedObject,
          type: this.modelData.type,
          utc_number: this.modelData.utc_number,
          serialnumber: this.modelData.serialnumber,
          lot_number: this.modelData.lot_number,
          purchaseprice: this.modelData.purchaseprice,
          electronic_address: this.modelData.electronic_address,
          initialcondition: this.modelData.initialcondition,
          initiallossoflife: this.modelData.initiallossoflife,
          enpro_class_defect_id: this.getDefectClassValueId,
          AssetGroups: this.modelData.AssetGroups,

        };
        console.log('data', data)

        let url = `/api/assets/model/${this.getModelName}/id/${this.getModelId}`;
        await axios.post(url, data, {
          headers: {
            'Content-Type': 'application/json'
          }
        }).then((response) => {
          // запрос прошел
          // для отладки
          // console.log("ОТправляемые данные данные:");
          // console.log(response.data);
          // console.log(11);
          // console.log(response);
          // записать обновленные данные в объект
          this.modelData.IdentifiedObject = response.data.data.IdentifiedObject
          this.modelData.id = response.data.data.id
          this.modelData.type = response.data.data.type;
          this.modelData.utc_number = response.data.data.utc_number;
          this.modelData.serialnumber = response.data.data.serialnumber;
          this.modelData.lot_number = response.data.data.lot_number;
          this.modelData.purchaseprice = response.data.data.purchaseprice;
          this.modelData.electronic_address =
            response.data.data.electronic_address;
          this.modelData.initialcondition =
            response.data.data.initialcondition;
          this.modelData.initiallossoflife =
            response.data.data.initiallossoflife;
          // AssetGroups:this.AssetGroups
          // сообщение пользователю
          toastr.success("Данные успешно сохранены...");
        })
          .catch((error) => {
            console.log(error)
            // сообщение пользователю
            toastr.error("Ошибка при сохранении данных...");
          })
          .finally(() => {
            // финальная обработка
            // признаки
            this.loading = false;
          });
      },
    },
  };
</script>

<style scoped>
.activity-records.form-field {
  margin-top: 18px;
}

.form-field-2-column .form-field {
  margin-top: 18px !important;
}
</style>
