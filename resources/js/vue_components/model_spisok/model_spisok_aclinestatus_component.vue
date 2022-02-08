<template>
    <section>

        <!-- индикатор загрузки -->
        <div v-if="loading">
            <img src='/public/uploads/loading.gif' style='width: 150px; position:fixed; margin:auto; top:0; bottom:0; left:0; right:0; z-index:9999;'/>
        </div>

        <div v-else-if="errored" class="alert alert-danger" role="alert">
            Запрос к серверу не прошел! Попробуйте, пожалуйста, позже!
            <img src="/public/uploads/icons/reload.svg" style="width:25px; margin-left: 5px;" v-on:click="funLoadAll()"/>
        </div>

        <!-- поисковая строчка -->
        <div class="search-bar">
          <input
            class="form-control"
            type="text"
            v-model.trim="filterName"
            @input="funLoadContent()"
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
        <div class="table-wrapper table-auto-height">
            <table class="table custom-table" data-plugin="selectable" data-row-selectable="false">
                <thead>
                <tr>
                    <th class="w-50">
                        <label class="checkbox">
                            <input class="selectable-all" type="checkbox" v-model="selectedAll" @change="funSelectedAll()">
                            <span class="box"></span>
                        </label>
                    </th>
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
                <tr v-for="(item, index) in modelData.data">
                    <td>
                        <label v-if="item.id > 0" class="checkbox">
                            <input class="selectable-item" type="checkbox" :id="('check_' + item.id)" :value="(item.id)" v-model="selectedRows">
                            <span class="box"></span>
                        </label>
                    </td>
                    <td class="text-center">
                        {{ item.id }}
                    </td>
                    <td>
                        <div v-if="!item.regimEdit"
                             @dblclick="funEditBegin(index)">
                            {{ item.name }}
                        </div>
                        <input v-else type="text" class="form-control" autocomplete="off"
                               v-model="item['oldValue'].name">
                    </td>
                    <td class="text-center">
                        <div v-if="(item.id > 0)">
                            <small>
                                {{ item.updated_at != null ? item.updated_at.substring(0,10) : ''}}
                            </small>
                        </div>
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
                        <!-- кнопка копия -->
                        <button type="button" class="link-icon"
                                v-if="(item.id > 0)"
                                @click="funMakeCopy(index)">
                            <span class="wb-copy"></span>
                        </button>
                        <!-- кнопка удалить -->
                        <button type="button" class="link-icon"
                                v-if="(item.id > 0)"
                                @click="funDeleteOne(index)">
                            <span class="wb-trash"></span>
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <!-- нижняя панель -->
        <div class="table-bottom-bar">

            <!-- действия -->
            <div class="left">
                <a class="link-icon" @click="funLoadAll()">Обновить</a>
                <a class="link-icon" @click="funSelectedRows('delete')">Удалить выбранные</a>
            </div>

            <!-- пагинация -->
            <div class="right">
                <small class="mt-5 mr-15">Строк: {{ modelData.total }}</small>
                <pagination :limit=5 :data="modelData" @pagination-change-page="funLoadContent"></pagination>
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
                errored: false,
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
                        name: 'Наименование',
                        sortFieldName: 'name',
                        width: 'auto',
                    },
                    2: {
                        name: 'Обновлено',
                        sortFieldName: 'updated_at',
                        width: 75,
                    },
                    3: {
                        name: 'Действия',
                        sortFieldName: null,
                        width: 75,
                    },
                },
                selectedAll: false,
                selectedRows: [],
                filterName: '',
                sorting: {col: 'updated_at', direct: 'desc'},
                emptyRowN: 3, // кол-во пустых строк внизу
            }
        },
        mounted() {
            // общая загрузка
            this.funLoadAll()
        },
        methods: {

            // ------------------------------------------------------------------
            // общая загрузка
            funLoadAll() {
                // загрузка содержимого таблицы
                this.funLoadContent();
            },

            // ------------------------------------------------------------------
            // загрузка содержимого таблицы
            async funLoadContent(page = 1) {

                // сообщение пользователю
                toastr.info('Начался процесс загрузки данных...');

                // признаки
                this.loading = true;
                this.errored = false;

                // данные post-запроса
                let form = new FormData();
                form.append('page', page);
                form.append('filterName', this.filterName);
                form.append('sortCol', this.sorting.col);
                form.append('sortDirect', this.sorting.direct);

                await axios
                    .post('/api/aclineStatusVueIndex', form)
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

                        // сбросить флажок Выделить все
                        this.selectedAll = false;
                        this.selectedRows = [];
                        // функция проверки/добавления n-строк в таблицу
                        this.funAddEmptyRows();

                        // признаки
                        this.loading = false;
                    });
            },

            // ------------------------------------------------------------------
            // сброс фильтра
            funSearchClear() {

                this.filterName = '';
                // повторная загрузка
                this.funLoadContent();
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
            // удалить одну строчку
            funDeleteOne(getIndex) {
                // очистить выделенные и записать всем новый признак
                this.selectedRows = [];
                this.selectedRows.push(this.modelData.data[getIndex].id);
                // удалить выбранные
                this.funSelectedRows('delete');
            },

            // ------------------------------------------------------------------
            // групповая обработка строк (delete / status)
            async funSelectedRows(getRegim) {

                // вопрос Пользователю
                if (getRegim === 'delete') {
                    if (!confirm('Вы уверены, что хотите удалить выделенные записи?')) return;
                }

                // признаки
                this.loading = true;
                this.errored = false;

                // данные post-запроса
                let form = new FormData();
                form.append('selectedRows', this.selectedRows);
                form.append('regim', getRegim);

                await axios
                    .post('/api/aclineStatusVueSelectedRows', form)
                    .then(
                        response => {
                            // запрос прошел
                            // для отладки
                            //console.log("Групповая обработка строк завершена!");

                            // сообщение пользователю
                            toastr.success('Данные успешно обновлены...');
                        },
                    )
                    .catch(error => {
                        // ошибка
                        this.errored = true;
                        console.log(error);
                        // сообщение пользователю
                        toastr.error('Ошибка при обработке данных...');
                    })
                    .finally(() => {
                        // финальная обработка

                        // повторная общая загрузка
                        this.funLoadAll();
                        // функция проверки/добавления n-строк в таблицу
                        this.funAddEmptyRows();

                        // признаки
                        this.loading = false;
                    });
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
                this.funLoadContent();
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
                    this.funSave(getIndex)
                }
            },

            // ------------------------------------------------------------------
            // валидация после редактирования
            funEditValidate(getIndex) {
                let myValidate = true;

                if ((this.modelData.data[getIndex].oldValue.name).trim() === '') {
                    // сообщение пользователю
                    toastr.error('Укажите, пожалуйста, наименование...');
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
                form.append('spravRowContent', JSON.stringify(this.modelData.data[getIndex]));

                axios
                    .post('/api/aclineStatusVueSave', form)
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
                    name: null,
                    status: null,
                    update_at: null,
                    regimEdit: true,
                    oldValue: {name: ''},
                });
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
                this.modelData.data[myFindRowN].name = this.modelData.data[getIndex].name;
                this.modelData.data[myFindRowN].status = this.modelData.data[getIndex].status;
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
        }
    }
</script>
