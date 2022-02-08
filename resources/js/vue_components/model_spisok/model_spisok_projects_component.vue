<template>
  <div class="tree-list-table">
    <ul class="unordered-list">
      <vue-tree-component
        :itemm="treeData"
        @tree-selected="a"
      ></vue-tree-component>
    </ul>
    <div class="table-wrapper table-auto-height">
      <table
        class="table custom-table"
        data-plugin="selectable"
        data-row-selectable="false"
      >
        <thead>
          <tr>
            <!-- шапка в цикле -->
            <th class="no-wrap text-center">
              <span> Название </span>
            </th>
          </tr>
        </thead>
        <tbody>
          <!-- содержимое в цикле -->

          <tr :key="it.subject" v-for="it in tableSubject">
            <td>
              <span role="button" @click="loadTwoTable(it.id)">
                {{ it.subject }}
              </span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="table-wrapper table-auto-height">
      <table
        class="table custom-table"
        data-plugin="selectable"
        data-row-selectable="false"
      >
        <thead>
          <tr>
            <!-- шапка в цикле -->
            <template v-for="(item, index) in contentTh">
              <th
                :key="index"
                class="no-wrap text-center"
                :style="'width:' + item.width + 'px;'"
              >
                <span>
                  {{ item.name }}
                </span>
              </th>
            </template>
          </tr>
        </thead>
        <tbody>
          <!-- содержимое в цикле -->
          <tr v-for="it in table2" :key="it.BUDGET">
            <td>
              <a>
                {{ it.priority }}
              </a>
            </td>
            <td>
              <a>
                {{ it.statusKind }}
              </a>
            </td>
            <td>
              <a>
                {{ it.design }}
              </a>
            </td>
            <td>
              <a>
                {{ it.BUDGET }}
              </a>
            </td>
            <td>
              <a>
                {{ it.BUDGET }}
              </a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
<script>
export default {
  name: "projects_interface",
  props: {
    getUserRole: String,
    titleOne: String,
    getModelName: String,
  },
  data() {
    return {
      treeData: {
        name: "Проекты",
        children: [
          { name: "hello", id: 2 },
          {
            name: "wat",
            id: 3,
            children: [
              { name: "hello", id: 9 },
              { name: "wat", id: 10 },
            ],
          },
          {
            name: "child folder",
            children: [
              {
                name: "child folder",
                id: 4,
                children: [
                  { name: "hello", id: 5 },
                  { name: "wat", id: 6 },
                ],
              },
              { name: "hello", id: 7 },
              { name: "wat", id: 8 },
              {
                name: "child folder",
                children: [
                  { name: "hello", id: 9 },
                  { name: "wat", id: 10 },
                ],
              },
            ],
          },
        ],
      },
      loading: false,
      errored: false,
      modelData: {},
      tableSubject: [],
      table2: [],
      modelRoute: {
        CableInfo: "cable_info",
        OverheadWireInfo: "overhead_wire_info",
      },
      contentTh: {
        1: {
          name: "Приоритет",
          sortFieldName: "priority",
          width: "auto",
          display: true,
        },
        2: {
          name: "Статус",
          sortFieldName: "statusKind",
          width: "auto",
          display: true,
        },
        3: {
          name: "Нормативный расчет",
          sortFieldName: "DESIGN",
          width: "auto",
          display: true,
        },
        4: {
          name: "Сумма по плану",
          sortFieldName: "BUDGET",
          width: "auto",
          display: true,
        },
        5: {
          name: "Сумма по факту",
          sortFieldName: "BUDGET",
          width: "auto",
          display: true,
        },
      },
      selectedAll: false,
      selectedRows: [],
      filterName: "",
      sorting: { col: "", direct: "" },
      dataa: [
        [
          { priority: 1, statusKind: 12, DESIGN: 23, BUDGET: 25 },
          { priority: 1, statusKind: 12, DESIGN: 23, BUDGET: 25 },
          { priority: 1, statusKind: 12, DESIGN: 23, BUDGET: 25 },
          { priority: 1, statusKind: 12, DESIGN: 23, BUDGET: 25 },
        ],
        [
          { priority: 100, statusKind: 1200, DESIGN: 2300, BUDGET: 2500 },
          { priority: 100, statusKind: 1200, DESIGN: 230, BUDGET: 250 },
          { priority: 100, statusKind: 1200, DESIGN: 230, BUDGET: 250 },
        ],
        [
          { priority: 199, statusKind: 12744, DESIGN: 203, BUDGET: 2235 },
          { priority: 199, statusKind: 12744, DESIGN: 203, BUDGET: 2235 },
          { priority: 199, statusKind: 12744, DESIGN: 203, BUDGET: 2235 },
          { priority: 199, statusKind: 12744, DESIGN: 203, BUDGET: 2235 },
        ],
        [
          { priority: 162, statusKind: 64, DESIGN: 65, BUDGET: 123 },
          { priority: 162, statusKind: 64, DESIGN: 65, BUDGET: 123 },
          { priority: 162, statusKind: 64, DESIGN: 65, BUDGET: 123 },
        ],
      ],
    };
  },
  mounted() {
    // общая загрузка
    // this.funLoadAll();
  },
  methods: {
    loadTwoTable(index) {
      this.table2 = this.dataa[index];
    },
    a(e) {
      console.log(e);
      this.tableSubject = [
        { subject: "one", id: 0 },
        { subject: "two", id: 1 },
        { subject: "3", id: 2 },
        { subject: "4", id: 3 },
      ];
    },
    //   // ------------------------------------------------------------------
    //   // общая загрузка
    //   funLoadAll() {
    //     // загрузка содержимого таблицы
    //     this.funLoadContent();
    //   },
    //   // ------------------------------------------------------------------
    //   // загрузка содержимого таблицы
    //   funLoadContent(page = 1) {
    //     // сообщение пользователю
    //     toastr.info("Начался процесс загрузки данных...");
    //     // признаки
    //     this.loading = true;
    //     this.errored = false;
    //     // данные post-запроса
    //     let url = `/api/modelName/${this.getModelName}/wireAssemblyInfo?page=${page}`;
    //     if (this.filterName.length > 0) {
    //       url += `&search=${this.filterName}`;
    //     }
    //     if (this.sorting.col !== "") {
    //       url += "&sortCol=" + this.sorting.col;
    //       if (this.sorting.direct !== "") {
    //         url += "&sortDirect=" + this.sorting.direct;
    //       }
    //     }
    //     axios
    //       .get(url)
    //       .then((response) => {
    //         // для отладки
    //         console.log("Загрузка успешно прошла!");
    //         console.log(response.data);
    //         // запрос прошел - записать полученные данные в массив
    //         this.modelData = response.data;
    //         // сообщение пользователю
    //         toastr.success("Данные успешно загружены...");
    //       })
    //       .catch((error) => {
    //         // ошибка
    //         this.errored = true;
    //         // для отладки
    //         console.log("Ошибка!");
    //         console.log(error);
    //         // сообщение пользователю
    //         toastr.error("Ошибка при загрузке данных...");
    //       })
    //       .finally(() => {
    //         // финальная обработка
    //         this.loading = false;
    //         // сбросить флажок Выделить все
    //         this.selectedAll = false;
    //         this.selectedRows = [];
    //       });
    //   },
  },
};
</script>
<style lang="scss">
.tree-list-table {
  display: grid;
  grid-template-columns: 1fr 1fr 4fr;
}
</style>