<template>
  <section>
    <div>
      <div class="row form-field">
        <div class="col activity-records">
          <div class="form-input-label">Тип</div>
          <select
            class="text-field"
            name="type"
            v-model.trim="activiyRecords.type"
            placeholder="Тип"
          >
            <option disabled>Выберите тип</option>
            <option
              v-for="(type, id) in activityTypes"
              :value="type.value"
              :key="id"
            >
              {{ type.label }}
            </option>
          </select>
        </div>
        <div class="col activity-records">
          <div class="form-input-label">Название</div>
          <input
            type="text"
            class="text-field"
            name="name"
            v-model.trim="activiyRecords.name"
            placeholder="Название"
          />
        </div>
      </div>
      <div class="row form-field">
        <div class="col">
          <div class="form-input-label">Описание</div>
          <textarea
            class="text-field"
            name="description"
            v-model.trim="activiyRecords.description"
            placeholder="Описание"
          />
        </div>
      </div>
      <div class="row form-field">
        <div class="col">
          <div class="form-input-label">Дата начала</div>
          <input
            type="date"
            class="text-field"
            name="startDate"
            v-model.trim="activiyRecords.startDate"
            placeholder="type"
          />
        </div>
        <div class="col">
          <div class="form-input-label">Дата окончания</div>
          <input
            type="date"
            class="text-field"
            name="endDame"
            v-model.trim="activiyRecords.endDate"
            placeholder="type"
          />
        </div>
        <div class="col">
          <div class="form-input-label">Status</div>
          <select
            class="text-field"
            name="status"
            v-model.trim="activiyRecords.status"
            placeholder="type"
          >
            <option disabled>Выберите тип</option>
            <option
              v-for="(status, id) in activityStatuses"
              :value="status.value"
              :key="id"
            >
              {{ status.label }}
            </option>
          </select>
        </div>
      </div>
      <div class="form-field">
        <a
          @click="addActiviyRecords"
          class="button d-block"
          data-toggle="tooltip"
          data-original-title="Создать новую запись"
        >
          <span class="icon icon-add" aria-hidden="true"></span>
        </a>
      </div>
    </div>
    <div class="table-wrapper table-auto-height mt-4">
      <table
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
              {{ item.description }}
            </td>
            <td>
              {{ item.startDate }}
            </td>
            <td>
              {{ item.endDate }}
            </td>
            <td>
              {{ item.status }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>
</template>
<script>
export default {
  props: {
    getActivityRecordsId: Number,
  },
  data() {
    return {
      loading: false,
      errored: false,
      modelData: {},
      activiyRecords: {
        name: "",
        description: "",
        type: "",
        status: "",
        startDate: "",
        endDate: "",
      },
      activityTypes: null,
      activityStatuses: null,
      contentTh: {
        0: {
          name: "ID",
          width: 50,
        },
        1: {
          name: "Тип",
          width: "auto",
        },
        2: {
          name: "Название",
          width: "auto",
        },
        3: {
          name: "Описание",
          width: "auto",
        },
        4: {
          name: "Дата начала ",
          width: "auto",
        },
        5: {
          name: "Дата окончания ",
          width: "auto",
        },
        6: {
          name: "Status",
          width: "auto",
        },
      },

      selectedAll: false,
      selectedRows: [],
    };
  },
  mounted() {
    // общая загрузка
    // this.funLoadAll();
  },
  methods: {
    // ------------------------------------------------------------------
    // общая загрузка
    funLoadAll() {
      // загрузка содержимого таблицы
      this.funLoadContent();

      //имитация получения по апи
      this.activityTypes = [
        {
          value: "overhaul",
          label: "капитальный ремонт",
        },
      ];
      // имитация загрузки по api
      this.activityStatuses = [
        {
          value: "Completed",
          label: "Завершено",
        },
      ];
    },
    addActiviyRecords() {
      if (
        this.activiyRecords.name.length > 0 ||
        this.activiyRecords.description.length > 0 ||
        this.activiyRecords.type.length > 0 ||
        this.activiyRecords.status.length > 0 ||
        this.activiyRecords.endDate.length > 0 ||
        this.activiyRecords.startDate.length > 0
      ) {
        let { name, description, type } = this.activiyRecords;
        //имитация добавления запися через апи
        this.modelData.activiyRecords.push({
          name: name,
          description: description,
          type: type,
        });
        this.funLoadContent();

        this.activiyRecords.name = "";
        this.activiyRecords.description = "";
        this.activiyRecords.type = "";
        this.activiyRecords.status = "";
        this.activiyRecords.endDate = "";
        this.activiyRecords.startDate = "";
        toastr.success("Данные успешно добавлены...");
      } else {
        alert("Нжуно заполнить полей ");
      }
    },
    // ------------------------------------------------------------------
    // загрузка содержимого таблицы
    funLoadContent(page = 1) {
      // сообщение пользователю


      // признаки
      this.loading = true;
      this.errored = false;

      // данные post-запроса
      let form = new FormData();
      form.append("page", page);

      axios
        .post("/api/", form)
        .then((response) => {
          // для отладки
          console.log("Загрузка успешно прошла!");
          console.log(response.data);

          // запрос прошел - записать полученные данные в массив
          this.modelData = response.data;
          // сообщение пользователю
          toastr.success("Данные успешно загружены...");
        })
        .catch((error) => {
          // ошибка
          this.errored = true;
          // для отладки
          console.log("Ошибка!");
          console.log(error);
          // сообщение пользователю
          toastr.error("Ошибка при загрузке данных...");
        })
        .finally(() => {
          // финальная обработка
          this.loading = false;

          // сбросить флажок Выделить все
          this.selectedAll = false;
          this.selectedRows = [];
        });
    },

    // ------------------------------------------------------------------
    // выделить все
    funSelectedAll() {
      // очистить выделенные и записать всем новый признак
      this.selectedRows = [];
      if (this.modelData.data.length > 0 && this.selectedAll === true) {
        let mySpisok = [];
        this.modelData.data.forEach(function (item) {
          mySpisok.push(item.id);
        });
        this.selectedRows = mySpisok;
      }
    },

    // ------------------------------------------------------------------
    // удалить выбранные
    async funSelectedRows() {
      // вопрос Пользователю
      if (!confirm("Вы уверены, что хотите удалить выделенные записи?")) return;

      // признаки
      this.loading = true;
      this.errored = false;

      // данные post-запроса
      let form = new FormData();
      form.append("selectedRows", this.selectedRows);

      await axios
        .post("/api/", form)
        .then((response) => {
          // запрос прошел
          // для отладки
          //console.log("Удаление успешно завершено!");

          // повторная загрузка
          this.funLoadAll();
        })
        .catch((error) => {
          // ошибка
          this.errored = true;
          // для отладки
          console.log("Удаление прошло с ошибками!");
          console.log(error);
        })
        .finally(() => {
          // финальная обработка
          this.loading = false;
        });
    },
  },
};
</script>
