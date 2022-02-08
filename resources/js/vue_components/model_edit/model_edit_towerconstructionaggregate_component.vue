<template>
  <section>

    <div class="page-header">

      <!-- заголовок -->
      <h2 class="page-title">
        Сборный агрегат
        <span v-if="modelData.name != null && (modelData.name).length > 0">
                    - {{ (modelData.name).slice(0, 50) }}
                </span>
      </h2>

      <!-- хлебные крошки -->
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin">Главная</a></li>
        <li class="breadcrumb-item"><a href="/admin/towerconstructionmaster">Компоненты</a></li>
        <li class="breadcrumb-item"><a href="/admin/towerconstructionaggregate">Сборные агрегаты</a></li>
        <li class="breadcrumb-item"><a href="/admin/towerinfo">Марки опор</a></li>
        <li class="breadcrumb-item active">
          {{
            getModelId ? `Редактирование ${modelData.name && '- ' + modelData.name}` : `Создание - ${modelData.name && modelData.name !== '' ? modelData.name : 'Сборного агрегата'}`
          }}
        </li>
      </ol>

      <!-- действия на странице -->
      <div class="page-header-actions">
        <!-- кнопка выход -->
        <a href="/admin/towerconstructionaggregate" class="button">
          Выход
        </a>
      </div>

    </div>

    <!-- содержимое -->
    <div class="page-content main-content">

      <div class="row row-lg">
        <div class="col-lg-12">
          <div class="panel panel-bordered form-icons">
            <div class="panel-body">

              <!-- индикатор загрузки -->
              <div v-if="loading">
                <img src='/public/uploads/loading.gif'
                     style='width: 150px; position:fixed; margin:auto; top:0; bottom:0; left:0; right:0; z-index:9999;'/>
              </div>

              <div v-else-if="errored" class="alert alert-danger" role="alert">
                Запрос к серверу не прошел! Попробуйте, пожалуйста, позже!
                <img src="/public/uploads/icons/reload.svg" style="width:25px; margin-left: 5px;"
                     v-on:click="funLoad()"/>
              </div>

              <div class="example-wrap">
                <div class="nav-tabs-horizontal" data-plugin="tabs">
                  <ul class="nav nav-tabs" role="tablist">

                    <li class="nav-item" role="presentation">
                      <a class="nav-link active" data-toggle="tab" href="#tabMain" aria-controls="tabMain" role="tab"
                         aria-selected="true">
                        Основное
                      </a>
                    </li>
                    <li class="nav-item" role="presentation">
                      <a class="nav-link" data-toggle="tab" href="#tabComponents" aria-controls="tabComponents"
                         role="tab" aria-selected="true">
                        Компоненты
                      </a>
                    </li>
                    <li class="nav-item" role="presentation">
                      <a class="nav-link" data-toggle="tab" href="#tabComponentsItogo"
                         aria-controls="tabComponentsItogo" role="tab" aria-selected="true">
                        Спецификация
                      </a>
                    </li>

                  </ul>
                  <div class="tab-content pt-20">

                    <!-- вкладка Основное -->
                    <div class="tab-pane active" id="tabMain" role="tabpanel">
                      <div class="row">

                        <!-- левая половинка -->
                        <div class="col-md-6">

                          <!-- серия, ГОСТ, ТУ -->
                          <div class="form-field">
                            <div class="form-input-label">Серия, ГОСТ, ТУ</div>
                            <input type="text" class="text-field" name='series' v-model="modelData.series"
                                   placeholder="серия, ГОСТ, ТУ">
                          </div>

                          <!-- обозначение (альбом) -->
                          <div class="form-field">
                            <div class="form-input-label">Обозначение</div>
                            <input type="text" class="text-field" name='series' v-model="modelData.album"
                                   placeholder="обозначение">
                          </div>

                          <!-- наименование -->
                          <div class="form-field">
                            <div class="form-input-label">Наименование</div>
                            <input type="text" class="text-field" name='name' v-model="modelData.name"
                                   placeholder="наименование">
                          </div>

                          <!-- марка -->
                          <div class="form-field">
                            <div class="form-input-label">Марка</div>
                            <input type="text" class="text-field" name='mark' v-model="modelData.mark"
                                   placeholder="марка">
                          </div>

                          <!-- статус -->
                          <div class="checkbox mt-15">
                            <label>
                              <input type="checkbox" v-model="modelData.status">
                              <span class="box"></span>
                              <span>Статус</span>
                            </label>
                          </div>

                        </div>

                        <!-- правая половинка -->
                        <div class="col-md-6">

                          <div style="text-align: right; margin-bottom: 30px;">
                            <button type="button" class="button bordered" @click="funSave()">Сохранить</button>
                          </div>

                          <div v-if="!getModelId>0">
                            Чтобы прикреплять изображения, сохраните сперва, пожалуйста, основные данные!
                          </div>

                          <div v-if="getModelId>0">

                            <!-- процент загрузки изображения -->
                            <div v-if="uploading">{{ progressValue + '%...' }}</div>

                            <!--скрытая кнопка загрузить-->
                            <input type="file" id="bFileUpload" style="display: none;" @change="funUploadImg()">

                            <!-- эксиз -->
                            <div class="row">
                              <div class="form-group col text-center">

                                <div style="display: inline-flex;">
                                  <h4 class="example-title">Эскиз</h4>
                                  <button type="button" class="link-icon"
                                          @click="funSelectImg('img')">
                                    <span class="wb-download"></span>
                                  </button>
                                  <button type="button" class="link-icon"
                                          @click="funDeleteImg('img')">
                                    <span class="wb-close-mini"></span>
                                  </button>
                                </div>
                                <div>
                                  <a v-if="modelData.img !== '' && modelData.img != null"
                                     class='image'
                                     :href="('/public/uploads/models/towerconstructionaggregate/' + getModelId + '/' + modelData.img)"
                                     target="_blank">

                                    <img
                                      :src="('/public/uploads/models/towerconstructionaggregate/' + getModelId + '/' + modelData.img)"
                                      style="max-width:300px; max-height:200px;">
                                  </a>
                                  <img v-else :src="('/public/uploads/default/default_hd.png')"
                                       style="max-width:300px; max-height:200px;">
                                </div>


                              </div>
                            </div>

                          </div>
                        </div>

                      </div>
                    </div>

                    <!-- вкладка Компоненты -->
                    <div class="tab-pane" id="tabComponents" role="tabpanel">

                      <!-- выбор компонент в pivot -->
                      <tower-construction-master-pivots-component get-model-name="Towerconstructionaggregate"
                                                                  :get-model-id="getModelId"
                                                                  @updatePivots="funUpdateConstructionPivots">
                      </tower-construction-master-pivots-component>

                    </div>

                    <!-- вкладка Спецификация -->
                    <div class="tab-pane" id="tabComponentsItogo" role="tabpanel">

                      <tower-construction-master-itogo-component get-model-name="Towerconstructionaggregate"
                                                                 :get-model-id="getModelId"
                                                                 :get-last-update="lastUpdateDateTime">
                      </tower-construction-master-itogo-component>

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
          mark: null,
          series: null,
          album: null,
          strut: null,
          status: 1,
          img: null,
        },
        imageRegim: null,
        progressValue: 0, // процент загрузки
        lastUpdateDateTime: null, // время последнего обновления дочернего компонента pivot
      }
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
        form.append('modelName', 'Towerconstructionaggregate');
        form.append('modelID', this.getModelId);

        axios
          .post('/api/getModelRecords', form)
          .then(
            response => {
              // запрос прошел
              // для отладки
              console.log("Полученные данные:");
              console.log(response.data);

              // записать полученные данные в массив
              this.modelData = response.data;
              this.getModelId = response.data.id;

              // сообщение пользователю
              toastr.success('Данные успешно загружены...');
            },
          )
          .catch(error => {
            // ошибка
            this.errored = true;
            // для отладки
            console.log("Ошибка при загрузке данных");
            console.log(error);
            // сообщение пользователю
            toastr.error('Ошибка при загрузке данных...');
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
        toastr.info('Начался процесс сохранения данных...');

        // признаки
        this.loading = true;
        this.errored = false;

        // данные post-запроса
        let form = new FormData();
        form.append('modelID', this.getModelId);
        form.append('modelData', JSON.stringify(this.modelData));

        await axios
          .post('/api/towerconstructionaggregateVueSave', form)
          .then(
            response => {
              // запрос прошел

              // проверка на новую запись
              if (!this.getModelId > 0) {
                // записать присвоенное id
                this.getModelId = response.data.id;
                // дописать в url присвоенный id
                history.pushState(null, null, window.location.href + "/" + this.getModelId);
              }

              // сообщение пользователю
              toastr.success('Данные успешно сохранены...');
            },
          )
          .catch(error => {
            // ошибка
            this.errored = true;
            console.log(error);
            // сообщение пользователю
            toastr.error('Ошибка при сохранении данных...');
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
        $('#bFileUpload').trigger('click');
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
        form.append('image', myImg);
        form.append('getModelDir', 'towerconstructionaggregate');
        form.append('getModelId', this.getModelId);

        await axios
          .post('/api/uploadModelFiles', form, {
              onUploadProgress: (itemUpload) => {
                this.progressValue = Math.round((itemUpload.loaded / itemUpload.total) * 100);
              }
            }
          )
          .then(response => {
            // для отладки
            //console.log(response.data);
            // сгенерированнео имя файла с путем
            let newFullName = response.data;
            // оставить только сгениртрованное имя без пути
            newFullName = newFullName.replace('uploads/models/towerconstructionaggregate/' + this.getModelId + '/', '');
            //console.log("Присвоенное имя изображения после закачки: " + newFullName);
            // записать в массив присвоенное имя фото
            this.modelData[this.imageRegim] = newFullName;
          })
          .catch(error => {
            // ошибка
            console.log(error);
            // сообщение пользователю
            toastr.error('Ошибка при загрузке изображения...');
          });

        // очистить поле выбора загружаемого файла (чтоб тоже самое изображение можно было выбрать)
        $("#bFileUpload").val('');
        // признаки
        this.uploading = false;

        // сохранить
        this.funSave();
      },

      // ------------------------------------------------------------------
      // удаление изображения
      async funDeleteImg(getImgField) {
        // удалить с диска

        // сообщение пользователю
        toastr.info('Начался процесс удаления изображения с диска...');

        // признаки
        this.loading = true;

        // данные post-запроса
        let form = new FormData();
        form.append('getFile', 'towerconstructionaggregate\\' + this.getModelId + '\\' + this.modelData[getImgField]);
        form.append('modelImgField', getImgField);

        await axios
          .post('/api/deleteModelFile', form)
          .then(
            response => {
              // запрос прошел

              // сообщение пользователю
              toastr.success('Изображение с диска успешно удалено...');
            },
          )
          .catch(error => {
            // ошибка
            console.log(error);
            // сообщение пользователю
            toastr.error('Ошибка при удалении изображения с диска...');
          })
          .finally(() => {
            // финальная обработка

            // очистить переменную
            this.modelData[getImgField] = '';

            // признаки
            this.loading = false;
          });
      },

      // ------------------------------------------------------------------
      // событие при обновлении дочернего pivot
      funUpdateConstructionPivots(data) {
        console.log('Обновился дочерний pivot в: ' + data);
        this.lastUpdateDateTime = data;
      },
    }
  }
</script>