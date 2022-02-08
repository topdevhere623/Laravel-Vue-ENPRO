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
              href="#tabComponentsAsset"
              aria-controls="tabComponentsAsset"
              role="tab"
              aria-selected="true"
            >
              Другие данные
            </a>
          </li>
        </ul>
        <div class="tab-content pt-20">
          <!-- вкладка  другие данные-->

          <div class="tab-pane active" id="tabComponentsAsset" role="tabpanel">
            <model-asset-otherdata-component
              get-model-name="Disconnector"
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
  name: "disconnector_edit_component",
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
      form.append("modelName", "Disconnector");
      form.append("modelID", this.getModelId);

      axios
        .post("/api/getModelRecords", form)
        .then((response) => {
          // запрос прошел
          // для отладки
          console.log("Полученные данные по сегментам:");
          console.log(response.data);

          // записать полученные данные в массив

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
  },
};
</script>