<template>
  <div class="form-field">
    <div v-if="loading">Загрузка...</div>

    <div v-else-if="errored" class="alert alert-danger" role="alert">
      Данные, не загрузились! Попробуйте еще раз!
      <img
        src="/public/uploads/icons/reload.svg"
        style="width: 25px; margin-left: 5px"
        @click="funLoad()"
      />
    </div>

    <div v-else>
      <!-- заголовок -->
      <div v-if="this.getTitle !== ''" class="form-input-label">
        {{ getTitle }}
      </div>

      <!-- список -->
      <select
        class="text-field"
        :name="getField"
        v-model="spravSelect"
        @change="funChange"
      >
        <option v-if="!modelData.length" disabled>Нет данных!</option>
        <option v-if="!getCurrentId > 0" disabled>не указано</option>
        <option v-for="item in modelData" v-bind:value="item.id" :key="item.id">
          <span v-if="typeof item.identifiedobject === 'undefined'">
            {{ item[spravFieldName] }}
          </span>
          <span v-else>
            {{ item.identifiedobject.name }}
          </span>
        </option>
      </select>
    </div>
  </div>
</template>

<script>
// $(function(){
//     $('select').selectric({
//         onChange: (value) => {
//             console.log(4444444);
//             console.log(value);
//         }
//     });
//
//     $('select').on('change', function () {
//         console.log(1111111111111);
//         console.log(value);
//     });
// });

export default {
  name: "option_sprav",
  props: {
    getSprav: String, // имя справочника
    getSpravFieldName: String, // имя поля в справочнике, которое нужно выводить для Пользователя в списке (если не указано, то 'name')
    getTitle: String,
    getField: String, // имя поля, в которое будем сохранять значение
    getCurrentId: Number, // значение, которое было до
  },
  data() {
    return {
      modelData: [],
      spravFieldName:
        typeof this.getSpravFieldName === "undefined"
          ? "name"
          : this.getSpravFieldName,
      loading: false,
      errored: false,
      spravSelect: null, // значение, выбранное в справочнике
    };
  },
  mounted() {
    // let js1 = document.createElement('script');
    // js1.setAttribute('src', "../../../../../public/assets/backend/js/jquery.selectric.min.js");
    // document.head.appendChild(js1);

    // console.log(22222222222);
    // console.log($('.selectric').val());

    //$('select').selectric('refresh');

    this.funLoad();
  },

  watch: {
    // слежение за переменной
    spravSelect: function (value) {
      console.log("Было обновление справочника: ", value);
    },
    getCurrentId: function () {
      this.spravSelect = this.getCurrentId;
    },
  },

  methods: {
    // ------------------------------------------------------------------
    // функция запроса к БД
    async funLoad() {
      // признаки
      this.loading = true;
      this.errored = false;

      // данные post-запроса
      let form = new FormData();
      form.append("modelName", this.getSprav);

      await axios
        .post("/api/getModelRecords", form)
        .then((response) => {
          // запрос прошел - записать полученные данные в массив
          this.modelData = response.data;
          // для отладки
          //console.log(response.data);
        })
        .catch((error) => {
          // ошибка
          this.errored = true;
          console.log(error);
          // сообщение пользователю
          toastr.error("Ошибка при загрузке справочника...");
        })
        .finally(() => {
          // финальная обработка
          this.loading = false;

          // $('.selectric').val(777);
          // $('select').selectric('refresh');
          //
          // console.log("Значение в классе selectric:");
          // console.log($('.selectric').val());
        });
    },

    // ------------------------------------------------------------------
    // функция смена значения
    funChange() {
      // передать выбранное значение родителю
      console.clear();
      console.log("Изменил значение в дочернем на: " + this.spravSelect);
      this.$emit("spravSelect", this.spravSelect);
    },
  },
};
</script>