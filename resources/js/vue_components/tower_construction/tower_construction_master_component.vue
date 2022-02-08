<template>
    <section>
        <!-- индикатор загрузки -->
        <div v-if="loading">
            <img src='/public/uploads/loading.gif' style='width: 150px; position:fixed; margin:auto; top:0; bottom:0; left:0; right:0; z-index:9999;'/>
        </div>

        <div v-else-if="errored" class="alert alert-danger" role="alert">
            Запрос к серверу не прошел! Попробуйте, пожалуйста, позже!
            <img src="/public/uploads/icons/reload.svg" style="width:25px; margin-left: 5px;" v-on:click="funLoad()"/>
        </div>

        <!-- процент загрузки изображения -->
        <div v-if="uploading">{{ progressValue + '%...' }}</div>

        <!--скрытая кнопка загрузить-->
        <input type="file" id="importUpload" style="display: none;" @change="funImportExport('import')">

      <!-- поисковая строчка -->
      <div class="search-bar">
        <input
          class="form-control"
          type="text"
          v-model.trim="filterName"
          @input="funLoad()"
          placeholder="Поиск по наименованию"
          ref="search"
        />
        <button
          v-if="filterName.length > 0"
          type="button"
          class="button position-absolute search-bar-reset-btn"
          @click="funSearchClear()"
        >
          <span class="icon icon-close mr-0"> </span>
        </button>
      </div>

        <!-- выбор справочника -->
        <div class="search-bar">
            <!-- список справочников -->
            <select class="form-control" v-model="currentSprav" @change="funLoad()">
                <option v-for="item in spravData" v-bind:value="item.name">{{item.ru}}</option>
            </select>
        </div>

        <!-- содержимое справочника -->
        <div class="table-wrapper table-auto-height">
            <table class="table custom-table" data-plugin="selectable" data-row-selectable="false">
                <thead>
                <tr>
                    <!-- шапка в цикле -->
                    <th v-for="item in contentTh" class="no-wrap text-center" :style="('width:' + item.width + 'px;')">
                        <span v-if="item.sortFieldName != null" @click="funSorting(item.sortFieldName)" style="text-decoration: underline;">
                            {{ item.name }}
                        </span>
                        <span v-else>
                            {{ item.name }}
                        </span>
                        <span v-if="sorting.col === item.sortFieldName && sorting.direct === 'asc'" class="wb-triangle-up"></span>
                        <span v-if="sorting.col === item.sortFieldName && sorting.direct === 'desc'" class="wb-triangle-down"></span>
                    </th>
                </tr>
                </thead>

                <tbody>
                <tr v-for="(item, index) in modelData.data" :key="item.index">
                    <td class="text-nowrap text-center">
                        {{ item.id }}
                    </td>
                    <td>
                        <div v-if="!item.regimEdit"
                             @dblclick="funEditBegin(index)">
                            {{ item.series }}
                        </div>
                        <input v-else type="text" class="form-control" autocomplete="off"
                               v-model="item['oldValue'].series">
                    </td>
                    <td>
                        <div v-if="!item.regimEdit"
                             @dblclick="funEditBegin(index)">
                            {{ item.album }}
                        </div>
                        <input v-else type="text" class="form-control" autocomplete="off"
                               v-model="item['oldValue'].album">
                    </td>
                    <td>
                        <div v-if="!item.regimEdit"
                             @dblclick="funEditBegin(index)">
                            {{ item.name }}
                        </div>
                        <input v-else type="text" class="form-control" autocomplete="off"
                               v-model="item['oldValue'].name">
                    </td>
                    <td>
                        <div v-if="!item.regimEdit"
                             @dblclick="funEditBegin(index)">
                            {{ item.mark }}
                        </div>
                        <input v-else type="text" class="form-control" autocomplete="off"
                               v-model="item['oldValue'].mark">
                    </td>
                    <td>
                        <div v-if="!item.regimEdit"
                             @dblclick="funEditBegin(index)">
                            {{ item.weight }}
                        </div>
                        <input v-else type="number" step="0.000001" class="form-control" autocomplete="off"
                               v-model="item['oldValue'].weight">
                    </td>
                    <td>
                        <a
                                v-if="item.img !== '' && item.img != null"
                                :href="('/public/uploads/models/' + currentSprav.toLowerCase() + '/' + item.id + '/' + item.img)"
                                target="_blank">
                            <img
                                    v-if="item.img !== '' && item.img != null"
                                    :src="('/public/uploads/models/' + currentSprav.toLowerCase() + '/' + item.id + '/' + item.img)"
                                    style="width:90px;">
                        </a>
                    </td>
                    <td class="text-nowrap">
                        <!-- кнопка начать редактировать -->
                        <button type="button" class="link-icon"
                                v-if="!item.regimEdit"
                                @click="funEditBegin(index)">
                            <span class="wb-pencil"></span>
                        </button>
                        <!-- кнопка сохранить после редактирования -->
                        <button type="button" class="link-icon"
                                v-if="item.regimEdit"
                                @click="funEditSave(index)">
                            <span class="md-thumb-up"></span>
                        </button>
                        <!-- кнопка отменить редактирование -->
                        <button type="button" class="link-icon"
                                v-if="item.regimEdit && item.id > 0"
                                @click="funEditEnd(index)">
                            <span class="wb-thumb-down"></span>
                        </button>
                        <!-- кнопка выбрать изображение -->
                        <button type="button" class="link-icon"
                                v-if="(item.id > 0)"
                                @click="funSelectImg(index)">
                            <span class="wb-image"></span>
                        </button>
                        <!-- кнопка копия -->
                        <button type="button" class="link-icon"
                                v-if="(item.id > 0)"
                                @click="funMakeCopy(index)">
                            <span class="wb-copy"></span>
                        </button>
                        <!-- кнопка удалить -->
                        <button type="button" class="link-icon"
                                v-if="(item.id > 0)"
                                @click="funDelete(index)">
                            <span class="wb-trash"></span>
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>

            <!--скрытая кнопка загрузить-->
            <input type="file" id="bFileUpload" style="display: none;" @change="funUploadImg()">

        </div>

        <!-- нижняя панель -->
        <div class="table-bottom-bar">

            <!-- действия -->
            <div class="left">
                <a class="link-icon" @click="funLoad()">Обновить</a>
                <!--<a class="link-icon" @click="funSelectedRows('export')">Экспорт</a>-->
                <a class="link-icon" @click="funSelectImportFile()">Импорт с файла Excel</a>
            </div>

            <!-- пагинация -->
            <div class="right">
                <small class="mt-5 mr-15">Строк: {{ modelData.total }}</small>
                <pagination :limit=5 :data="modelData" @pagination-change-page="funLoad"></pagination>
            </div>

        </div>

    </section>
</template>

<script>
    export default {
        props: {},
        data() {
            return {
                loading: false,
                uploading: false,
                errored: false,
                spravData: [ // справочники
                    {
                        name: 'Towerconstructionbasic',
                        ru: 'Железобетонные элементы',
                    },
                    {
                        name: 'Towerconstructionwood',
                        ru: 'Деревянные элементы',
                    },
                    {
                        name: 'Towerconstructionmetal',
                        ru: 'Стальные конструкции',
                    },
                    {
                        name: 'Towerconstructionaccessory',
                        ru: 'Арматура линейная',
                    },
                    {
                        name: 'Towerconstructioninsulator',
                        ru: 'Изоляторы',
                    },
                    {
                        name: 'Towerconstructionstandart',
                        ru: 'Изделия стандартные',
                    },
                    {
                        name: 'Towerconstructionbase',
                        ru: 'Фундаменты',
                    },
                ],
                modelData: {
                    data: {}
                },
                contentTh: {
                    0: {
                        name: 'ID',
                        sortFieldName: 'id',
                        width: 50,
                    },
                    1: {
                        name: 'Серия, ГОСТ, ТУ',
                        sortFieldName: 'series',
                        width: 150,
                    },
                    2: {
                        name: 'Обозначение',
                        sortFieldName: 'album',
                        width: 250,
                    },
                    3: {
                        name: 'Наименование',
                        sortFieldName: 'name',
                        width: 'auto',
                    },
                    4: {
                        name: 'Марка',
                        sortFieldName: 'mark',
                        width: 150,
                    },
                    5: {
                        name: 'Масса, кг.',
                        sortFieldName: 'weight',
                        width: 150,
                    },
                    6: {
                        name: 'Эскиз',
                        sortFieldName: null,
                        width: 120,
                    },
                    7: {
                        name: 'Действия',
                        sortFieldName: null,
                        width: 75,
                    },
                },
                filterName: '', // поисковое значение,
                sorting: {col: 'id', direct: 'asc'},
                emptyRowN: 3, // кол-во пустых строк внизу
                currentRowIndexN: 0, // текущая строка (нужно для upload)
                currentSprav: '', // текущий справочник
                progressValue: 0, // процент загрузки
            }
        },

        mounted() {

            // текущий справочник
            this.currentSprav = this.spravData[0].name; // первый, который откроется
            // функция загрузки данных
            this.funLoad();
        },

        // мои методы и функции
        methods: {

            // ------------------------------------------------------------------
            // функция загрузки данных
            async funLoad(page = 1) {

                if (this.currentSprav !== '') {

                    // сообщение пользователю
                    toastr.info('Начался процесс загрузки данных...');

                    // признаки
                    this.loading = true;
                    this.errored = false;

                    // данные post-запроса
                    let form = new FormData();
                    form.append('page', page);
                    form.append('spravName', this.currentSprav);
                    form.append('filterName', this.filterName);
                    form.append('sortCol', this.sorting.col);
                    form.append('sortDirect', this.sorting.direct);

                    axios
                        .post('/api/towerConstructionMasterVueSpravLoad', form)
                        .then(
                            response => {
                                // запрос прошел
                                // для отладки
                                //console.log("Загрузка успешно прошла!");
                                //console.log(response.data);

                                // записать полученные данные в массив
                                this.modelData = response.data;
                                // добавить свои поля
                                this.modelData.data.forEach(function (item) {
                                    item.regimEdit = false;
                                    item.oldValue = {};
                                });

                                // сообщение пользователю
                                toastr.success('Данные успешно загружены...');
                            },
                        )
                        .catch(error => {
                            // ошибка
                            this.errored = true;
                            console.log(error);
                            // сообщение пользователю
                            toastr.error('Ошибка при загрузке данных...');
                        })
                        .finally(() => {
                            // финальная обработка

                            // функция проверки/добавления n-строк в таблицу
                            this.funAddEmptyRows();

                            // признаки
                            this.loading = false;
                        });
                }
            },

            // ------------------------------------------------------------------
            // сброс фильтра
            funSearchClear() {
                this.filterName = '';
                // повторная загрузка
                this.funLoad();
            },

            // ------------------------------------------------------------------
            // функция удаления строки
            async funDelete(getIndex = null) {

                // вопрос Пользователю
                if (!confirm('Вы уверены, что хотите удалить строчку?')) return;

                // выбранное id элемента
                let myID = null;
                if (getIndex !== null) {
                    myID = this.modelData.data[getIndex].id;
                }

                console.log("Удаление строки справочника: " + this.currentSprav + " номер строки: " + getIndex + " ID = " + myID);

                if (myID != null) {

                    // сообщение пользователю
                    toastr.info('Начался процесс удаления...');

                    // признаки
                    this.loading = true;
                    this.errored = false;

                    // данные post-запроса
                    let form = new FormData();
                    form.append('spravName', this.currentSprav);
                    form.append('spravRowID', myID);

                    // удалить из базы
                    await axios
                        .post('/api/towerConstructionMasterVueSpravDelete', form)
                        .then(
                            response => {
                                // запрос прошел

                                // сообщение пользователю
                                toastr.success('Данные успешно удалены...');
                            },
                        )
                        .catch(error => {
                            // ошибка
                            this.errored = true;
                            console.log(error);
                            // сообщение пользователю
                            toastr.error('Ошибка при удалении данных...');
                        })
                        .finally(() => {
                            // финальная обработка

                            // повторная общая загрузка
                            this.funLoad();
                            // функция проверки/добавления n-строк в таблицу
                            this.funAddEmptyRows();
                        });

                    // данные post-запроса
                    form.append('getFile', '/' + this.currentSprav.toLowerCase() + '/' + myID + '/' + this.modelData.data[getIndex].img);

                    // удалить изображение с диска
                    axios
                        .post('/api/deleteModelFile', form)
                        .then(response => {
                        })
                        .catch(error => {
                            console.log(error);
                        });

                    // признаки
                    this.loading = false;
                }
            },

            // ------------------------------------------------------------------
            // сортировка по столбцу
            funSorting(getCol) {

                // новые значения
                // сортировка
                if (this.sorting.col !== getCol) {
                    this.sorting.direct = 'asc';
                }
                else {
                    this.sorting.direct = this.sorting.direct === 'asc' ? 'desc' : 'asc';
                }
                // столбец
                this.sorting.col = getCol;

                //console.log("Выбрана сортировка по столбцу: " + this.sorting.col + " направление: " + this.sorting.direct);

                // повторная загрузка
                this.funLoad();
            },

            // ------------------------------------------------------------------
            // начало редактирования
            funEditBegin(getIndex) {

                // запомнить значения до
                this.modelData.data[getIndex].oldValue = {};
                for (let key in this.modelData.data[getIndex]) {
                    this.modelData.data[getIndex].oldValue[key] = this.modelData.data[getIndex][key];
                }

                // переключить режим редактирования
                this.modelData.data[getIndex].regimEdit = true;
                // принудительный рендеринг
                this.$forceUpdate();
            },

            // ------------------------------------------------------------------
            // после редактирования - сохранить
            funEditSave(getIndex) {

                // валидация после редактирования
                if (this.funEditValidate(getIndex)) {
                    // валидацяи прошла

                    // записать значения до в основные
                    for (let key in this.modelData.data[getIndex]) {
                        this.modelData.data[getIndex][key] = this.modelData.data[getIndex].oldValue[key];
                    }

                    // завершение редактирования
                    this.funEditEnd(getIndex);
                    // сохранить
                    this.funSave(getIndex);
                }
            },

            // ------------------------------------------------------------------
            // валидация после редактирования
            funEditValidate(getIndex) {
                let myValidate = true;

                if (this.modelData.data[getIndex].oldValue.album == null || (this.modelData.data[getIndex].oldValue.album).trim() === '') {
                    // сообщение пользователю
                    toastr.error('Укажите, пожалуйста, обозначение...');
                    myValidate = false;
                }

                // возвращаемый параметр
                return myValidate;
            },

            // ------------------------------------------------------------------
            // завершение редактирования
            funEditEnd(getIndex) {

                // переключить режим редактирования
                this.modelData.data[getIndex].regimEdit = false;
                // удалить значения до
                delete this.modelData.data[getIndex].oldValue;
                // принудительный рендеринг
                this.$forceUpdate();
            },

            // ------------------------------------------------------------------
            // функция сохранения строки
            async funSave(getIndex = null) {

                // выбранное id элемента
                let myID = null;
                if (getIndex !== null) {
                    myID = this.modelData.data[getIndex].id;
                }

                console.clear();
                console.log("Сохранение строки: " + getIndex + " с ID = " + myID);
                console.log("Содержимое сохраняемой строки:");
                console.log(this.modelData.data[getIndex]);

                // сообщение пользователю
                toastr.info('Начался процесс сохранения данных...');

                // признаки
                this.loading = true;
                this.errored = false;

                // данные post-запроса
                let form = new FormData();
                form.append('spravName', this.currentSprav);
                form.append('spravRowContent', JSON.stringify(this.modelData.data[getIndex]));

                await axios
                    .post('/api/towerConstructionMasterVueSpravSave', form)
                    .then(
                        response => {
                            // запрос прошел
                            // для отладки
                            console.log("Полученные данные:");
                            console.log(response.data);

                            // записать обновленную строчку в массив
                            this.modelData.data[getIndex] = response.data;

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

                        // функция проверки/добавления n-строк в таблицу
                        this.funAddEmptyRows();

                        // признаки
                        this.loading = false;
                    });
            },

            // ------------------------------------------------------------------
            // функция проверки/добавления n-строк в таблицу
            funAddEmptyRows() {

                // подсчитать, сколько строк без id (новых)
                let myEmptyRowN = 0;
                this.modelData.data.forEach(function (item) {
                    if (!item.id > 0) {
                        myEmptyRowN++;
                    }
                });

                // вставить пустые строчки
                for (let i = 1; i <= this.emptyRowN - myEmptyRowN; i++) {
                    // функция добавления одной пустой строки в таблицу
                    this.funAddEmptyRowOne();
                }
            },

            // ------------------------------------------------------------------
            // функция добавления одной пустой строки в таблицу (так же и наверху! массивом не получается - идет по ссылке сразу всем)
            funAddEmptyRowOne() {
                this.modelData.data.push({
                    id: null,
                    album: null,
                    name: null,
                    series: null,
                    mark: null,
                    weight: null,
                    img: null,
                    update_at: null,
                    regimEdit: true,
                    oldValue: {name: ''},
                });
            },

            // ------------------------------------------------------------------
            // клик на выбрать изображение
            funSelectImg(getIndex) {
                $('#bFileUpload').trigger('click');
                this.currentRowIndexN = getIndex;
            },

            // ------------------------------------------------------------------
            // закачка изображения
            async funUploadImg() {

                // параметры текущей строки
                let myIndex = this.currentRowIndexN;
                let myID = this.modelData.data[myIndex].id;

                // выбранное изображение через обзор
                let myImg = Array.from(event.target.files);
                if (myImg.length === 0) return;
                myImg = myImg[0];
                console.log("Загрузка изображения для строки index = : " + myIndex + " с ID = " + myID);
                console.log("Выбран файл:");
                console.log(myImg);

                // признаки
                this.uploading = true;

                // данные post-запроса
                let form = new FormData();
                form.append('image', myImg);
                form.append('getModelDir', this.currentSprav.toLowerCase());
                form.append('getModelId', myID);

                await axios
                    .post('/api/uploadModelFiles', form, {
                            onUploadProgress: (itemUpload) => {
                                this.progressValue = Math.round((itemUpload.loaded / itemUpload.total) * 100);
                            }
                        }
                    )
                    .then(response => {
                        // для отладки
                        console.log(response.data);
                        // сгенерированнео имя файла с путем
                        let newFullName = response.data;
                        // оставить только сгениртрованное имя без пути
                        newFullName = newFullName.replace('uploads/models/' + this.currentSprav.toLowerCase() + '/' + myID + '/', '');
                        console.log("Присвоенное имя изображения после закачки: " + newFullName);
                        // записать в массив присвоенное имя фото
                        this.modelData.data[myIndex].img = newFullName;
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
                this.funSave(myIndex);
            },

            // ------------------------------------------------------------------
            funMakeCopy(getIndex) {

                // найти первую новую пустую строчку
                let myFindRowN = null;
                for (let i = 0; i < this.modelData.data.length; i++) {

                    // проверка строки на пустоту
                    if (this.funRowIsEmpty(i)) {
                        myFindRowN = i;
                        break;
                    }
                }
                console.log("Номер свободной строки: " + myFindRowN);

                if (myFindRowN == null) {
                    // не нашлось новой пустой - вставить в конец новую
                    this.funAddEmptyRowOne();
                    myFindRowN = this.modelData.data.length - 1;
                }

                // вставить в пустую строчку
                this.modelData.data[myFindRowN].album = this.modelData.data[getIndex].album;
                this.modelData.data[myFindRowN].name = this.modelData.data[getIndex].name;
                this.modelData.data[myFindRowN].series = this.modelData.data[getIndex].series;
                this.modelData.data[myFindRowN].mark = this.modelData.data[getIndex].mark;
                this.modelData.data[myFindRowN].weight = this.modelData.data[getIndex].weight;
                this.modelData.data[myFindRowN].regimEdit = true;

                // начало редактирования
                this.funEditBegin(myFindRowN);
                // сообщение пользователю
                toastr.success('Строка скопирована...');
            },

            // ------------------------------------------------------------------
            // проверка строки на пустоту
            funRowIsEmpty(getIndex) {

                let myReturn = false;

                if (this.modelData.data[getIndex].id == null &&
                    this.modelData.data[getIndex].name == null &&
                    this.modelData.data[getIndex].status == null) {
                    myReturn = true;
                }

                // возвращаемый параметр
                return myReturn;
            },

            // ------------------------------------------------------------------
            // клик на выбрать файл
            funSelectImportFile() {
                if (!confirm('Для импорта потребуется таблица с 5-ю полями: серия, обозначение, наименование, марка, вес')) return;

                $('#importUpload').trigger('click');
            },

            // ------------------------------------------------------------------
            // импорт / экспорт
            async funImportExport(getRegim) {

                // признаки
                this.loading = true;
                this.errored = false;

                // данные post-запроса
                let form = new FormData();
                form.append('regim', getRegim);
                form.append('spravName', this.currentSprav);

                // выбранный файл через обзор
                if (getRegim === 'import') {
                    // импорт

                    let myFile = Array.from(event.target.files);
                    if (myFile.length === 0) return;
                    myFile = myFile[0];
                    // добавить к post-данным
                    form.append('file', myFile);

                    console.log("Выбран файл:");
                    console.log(myFile);
                }

                await axios
                    .post('/api/towerConstructionMasterVueImportExport', form)
                    .then(
                        response => {
                            // запрос прошел
                            // для отладки
                            //console.log("Удаление успешно завершено!");

                            if (getRegim === 'import') {
                                // очистить поле выбора импортируемого файла
                                $("#importUpload").val('');
                            }

                            if (getRegim === 'export') {

                                let myData = response.data;
                                //let myData = JSON.parse(response.data);
                                let myName = 'towerinfo.xlsx';

                                console.log(myData.file);

                                var a = document.createElement("a");
                                a.href = myData.file;
                                a.download = myData.name;
                                document.body.appendChild(a);
                                a.click();
                                a.remove();

                                // let myData = response.data;
                                // //let myData = JSON.parse(response.data);
                                // let myName = 'towerinfo.xlsx';
                                //
                                // console.log(myData);
                                //
                                // var blob = new Blob([myData], {
                                //     type: 'data:application/vnd.ms-excel;base64'
                                // });
                                // var link = document.createElement('a');
                                // link.href = window.URL.createObjectURL(blob);
                                // link.download = myName;
                                //
                                // document.body.appendChild(link);
                                // link.click();
                                // document.body.removeChild(link);


                                // let text = response.data;
                                //let myData = 'data:application/txt;charset=utf-8,' + encodeURIComponent(text);
                                //let myData = 'text/csv,' + text;

                                // var a = document.createElement("a");
                                // a.href = myData;
                                // a.download = 'data.csv';
                                // document.body.appendChild(a);
                                // a.click();
                                // a.remove();
                            }

                            // сообщение пользователю
                            toastr.success('Данные успешно обновлены...');
                        },
                    )
                    .catch(error => {
                        // ошибка
                        this.errored = true;
                        console.log(error);
                        // сообщение пользователю
                        toastr.error('Ошибка при импорт/экспорте...');
                    })
                    .finally(() => {
                        // финальная обработка

                        // повторная загрузка
                        this.funLoad();

                        // признаки
                        this.loading = false;
                    });
            },
        }
    }
</script>

<style>
</style>
