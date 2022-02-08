<template>
    <section>

        <div v-if="!getModelId>0">
            Чтобы продолжить работу, сохраните сперва, пожалуйста, основные данные!
        </div>

        <div v-if="getModelId>0">

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
                <input class="form-control" type="text" v-model="filterLBName" @keyup.enter="funSearch()" placeholder="Поиск" ref="filterLBName">

                <!--<label style="margin-top: 10px;">-->
                <!--<input v-model="globalSearch" type="checkbox" style="margin-left: 20px;"/>-->
                <!--<span class="box"></span>-->
                <!--<span>Глобальный</span>-->
                <!--</label>-->

                <button type="button" class="button bordered" @click="funSearch()">Найти</button>
                <button type="button" class="button bordered" @click="funGlobalSearch()">Глобально</button>
                <button type="button" class="button bordered" @click="funLBSearchClear()">Сбросить</button>
            </div>

            <!-- результаты поиска по всем справочникам -->
            <div v-if="globalSearch" class="row">
                <div class="col-12">
                    <div>
                        <table class="table custom-table" data-plugin="selectable" data-row-selectable="false">
                            <thead>
                            <tr>
                                <th class="w-30 text-nowrap">
                                    ID
                                </th>
                                <th>
                                    Справочник
                                </th>
                                <th>
                                    Серия, ГОСТ, ТУ
                                </th>
                                <th>
                                    Обозначение
                                </th>
                                <th>
                                    Наименование
                                </th>
                                <th>
                                    Марка
                                </th>
                                <th class="w-120">
                                    Действия
                                </th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr v-for="(item, index) in globalSearchData" :key="item.index">
                                <td class="text-nowrap">
                                    {{ item.id }}
                                </td>
                                <td>
                                    <!-- справочник -->
                                    <span @dblclick="funLBSearchSelect(index)">
                                        {{ item.spravRu }}
                                    </span>
                                </td>
                                <td>
                                    <!-- серия -->
                                    <span @dblclick="funLBSearchSelect(index)">
                                        {{ item.series }}
                                    </span>
                                </td>
                                <td>
                                    <!-- обозначение -->
                                    <span @dblclick="funLBSearchSelect(index)">
                                        {{ item.album }}
                                    </span>
                                </td>
                                <td>
                                    <!-- наименование -->
                                    <span @dblclick="funLBSearchSelect(index)">
                                        {{ item.name }}
                                    </span>
                                </td>
                                <td>
                                    <!-- марка -->
                                    <span @dblclick="funLBSearchSelect(index)">
                                        {{ item.mark }}
                                    </span>
                                </td>
                                <td class="text-nowrap">
                                    <!-- кнопки -->
                                    <button type="button" class="link-icon" @click="funLBSearchSelect(index)">
                                        <span class="wb-arrow-right"></span>
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- выбор справочника + левая и правая колонка -->
            <div v-if="!globalSearch">

                <!-- выбор справочника -->
                <div class="search-bar">

                    <!-- список справочников -->
                    <select class="form-control" v-model="currentSprav" @change="funLoadAll()">
                        <option v-for="item in spravData" v-bind:value="item.name">{{item.ru}}</option>
                    </select>
                    <!-- ссылка на справочник -->
                    <div v-if="currentSpravLink !== ''" style="margin: 5px 10px 0 10px; text-decoration: underline;">
                        <a :href="currentSpravLink" target="_blank">Открыть в отдельном окне</a>
                    </div>

                    <!-- кнопка сохранить -->
                    <button type="button" class="button bordered" @click="funLBSave()">Сохранить</button>

                </div>

                <div class="row">

                    <div class="col-6">
                        <!-- левая колонка - справочник -->

                        <div v-if="currentSprav !== 'Towerinfo' && !globalSearch">

                            <!-- справочник -->
                            <div class="table-wrapper table-auto-height">
                                <table class="table custom-table" data-plugin="selectable" data-row-selectable="false">
                                    <thead>
                                    <tr>
                                        <th class="w-30 text-nowrap">
                                            ID
                                        </th>
                                        <th>
                                            Наименование
                                        </th>
                                        <th class="w-120">
                                            Действия
                                        </th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <tr v-for="(item, index) in contentLBData.data" :key="item.index">
                                        <td class="text-nowrap">
                                            {{ item.id }}
                                        </td>
                                        <td>
                                            <!-- наименование -->
                                            <span @dblclick="funLBViewDetails(index)">
                                    {{ item.name }}
                                </span>

                                            <!-- подробности -->
                                            <div v-if="item.details" style="margin: 15px 10px; padding: 10px; border: solid 1px #ccc;">
                                                Серия, ГОСТ, ТУ: {{ item.series }} <br>
                                                Обозначение: {{ item.album }} <br>
                                                Марка: {{ item.mark }} <br>
                                                Масса, кг.: {{ item.weight }} <br>
                                                <a
                                                        v-if="item.img !== '' && item.img != null"
                                                        :href="('/public/uploads/models/' + currentSprav.toLowerCase() + '/' + item.id + '/' + item.img)"
                                                        target="_blank">
                                                    <img
                                                            v-if="item.img !== '' && item.img != null"
                                                            :src="('/public/uploads/models/' + currentSprav.toLowerCase() + '/' + item.id + '/' + item.img)"
                                                            style="width:90%; margin:10px;">
                                                </a>
                                            </div>
                                        </td>
                                        <td class="text-nowrap">
                                            <!-- кнопка подробности -->
                                            <button type="button" class="link-icon"
                                                    @click="funLBViewDetails(index)">
                                                <span v-if="!item.details" class="wb-arrow-expand"></span>
                                                <span v-else class="wb-arrow-shrink"></span>
                                            </button>
                                            <!-- кнопки -->
                                            <button v-if="!item.alreadyAdd" type="button" class="link-icon" @click="funAddToPivot(index)">
                                                <span class="wb-arrow-right"></span>
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- нижняя панель -->
                            <div class="table-bottom-bar">
                                <div class="left">
                                    <a class="link-icon" @click="funLBLoad()">Обновить</a>
                                </div>
                                <!-- пагинация -->
                                <div class="right">
                                    <pagination :limit=5 :data="contentLBData" @pagination-change-page="funLBLoad"></pagination>
                                </div>
                            </div>

                        </div>

                        <div v-if="currentSprav === 'Towerinfo'">
                            <button type="button" class="button ml-20" @click="funLBCopyFromModel()">Скопировать из модели</button>
                        </div>

                    </div>
                    <div class="col-6">
                        <!-- правая колонка - сводная -->

                        <!-- сводная -->
                        <div class="table-wrapper table-auto-height">
                            <table class="table custom-table" data-plugin="selectable" data-row-selectable="false">
                                <thead>
                                <tr>
                                    <th class="w-30 text-nowrap">
                                        ID
                                    </th>
                                    <th>
                                        Наименование
                                    </th>
                                    <th class="w-120">
                                        Кол-во
                                    </th>
                                    <th class="w-120">
                                        Действия
                                    </th>
                                </tr>
                                </thead>

                                <tbody>
                                <tr v-for="(item, index) in contentRBData" :key="item.index">
                                    <td class="text-nowrap">
                                        {{ item.id }}
                                        <input type="hidden"
                                               :value="(item.id)">
                                    </td>
                                    <td>
                                        <!-- наименование -->
                                        <span @dblclick="funRBViewDetails(index)">
                                    {{ item.name }}
                                </span>

                                        <!-- подробности -->
                                        <div v-if="item.details" style="margin: 15px 10px; padding: 10px; border: solid 1px #ccc;">
                                            Серия, ГОСТ, ТУ: {{ item.series }} <br>
                                            Обозначение: {{ item.album }} <br>
                                            Марка: {{ item.mark }} <br>
                                            Масса, кг.: {{ item.weight }} <br>
                                            <a
                                                    v-if="item.img !== '' && item.img != null"
                                                    :href="('/public/uploads/models/' + currentSprav.toLowerCase() + '/' + item.id + '/' + item.img)"
                                                    target="_blank">
                                                <img
                                                        v-if="item.img !== '' && item.img != null"
                                                        :src="('/public/uploads/models/' + currentSprav.toLowerCase() + '/' + item.id + '/' + item.img)"
                                                        style="width:90%; margin:10px;">
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <!-- кол-во -->
                                        <input type="number" step="0.01" class="form-control" autocomplete="off"
                                               v-model="item.kol">
                                    </td>
                                    <td class="no-wrap">
                                        <!-- кнопка подробности -->
                                        <button type="button" class="link-icon"
                                                @click="funRBViewDetails(index)">
                                            <span v-if="!item.details" class="wb-arrow-expand"></span>
                                            <span v-else class="wb-arrow-shrink"></span>
                                        </button>
                                        <!-- кнопка удалить -->
                                        <button type="button" class="link-icon"
                                                @click="funRBDelete(index)">
                                            <span class="wb-trash"></span>
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- нижняя панель -->
                        <div class="table-bottom-bar">
                            <div class="left">
                                <a class="link-icon" @click="funRBLoad()">Загрузить повторно</a>
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
            'getModelName': String, // имя модели (Towerinfo, Towerconstructionaggregate или Tower)
            'getModelId': Number, // id
        },
        data() {
            return {
                loading: false,
                uploading: false,
                errored: false,
                spravData: [], // справочники
                currentSprav: '', // текущий справочник
                currentSpravLink: '',
                contentLBData: {
                    data: {}
                },
                contentRBData: {},
                globalSearch: false, // режим глобального поиска
                globalSearchData: [], // результаты глобального поиска
                filterLBName: '', // поисковое значение
            }
        },

        watch: {
            // слежение за переменной
            currentSprav: function (getNewValue) {
                console.log("Был выбран справочник: " + getNewValue);
                this.currentSpravLink = '';
                if (getNewValue !== '') {
                    // найти данные о выбранном справочнике
                    let item = this.spravData.find(item => item.name === getNewValue);
                    if (typeof(item.link) !== 'undefined' && item.link) {
                        // да, url нужен, его адррес, как имя модели
                        this.currentSpravLink = 'http://' + document.location.hostname + '/admin/' + getNewValue.toLowerCase();
                    }
                }
            }
        },

        mounted() {
            //console.log("ID для загрузки pivot: " + this.getModelId);

            // необходимые справочники
            this.spravData.push(
                {
                    name: 'Towerconstructionbasic',
                    ru: 'Железобетонные элементы',
                    link: false,
                    globalSearch: true,
                }, {
                    name: 'Towerconstructionwood',
                    ru: 'Деревянные элементы',
                    link: false,
                    globalSearch: true,
                }, {
                    name: 'Towerconstructionmetal',
                    ru: 'Стальные конструкции',
                    link: false,
                    globalSearch: true,
                }, {
                    name: 'Towerconstructionaccessory',
                    ru: 'Арматура линейная',
                    link: false,
                    globalSearch: true,
                }, {
                    name: 'Towerconstructioninsulator',
                    ru: 'Изоляторы',
                    link: false,
                    globalSearch: true,
                }, {
                    name: 'Towerconstructionstandart',
                    ru: 'Изделия стандартные',
                    link: false,
                    globalSearch: true,
                }, {
                    name: 'Towerconstructionbase',
                    ru: 'Фундаменты',
                    link: false,
                    globalSearch: true,
                });
            if (this.getModelName === 'Towerinfo' || this.getModelName === 'Tower') {
                this.spravData.push({
                    name: 'Towerconstructionaggregate',
                    ru: 'Сборные агрегаты',
                    link: true,
                    globalSearch: true,
                });
            }
            if (this.getModelName === 'Tower') {
                this.spravData.push(
                    {
                        name: 'Towerinfo',
                        ru: 'Марки опор',
                        link: true,
                        globalSearch: false,
                    });
            }

            // текущий справочник
            this.currentSprav = this.spravData[0].name; // первый, который откроется
            // загрузить все
            this.funLoadAll();
        },

        // мои методы и функции
        methods: {

            // ------------------------------------------------------------------
            // загрузить все
            async funLoadAll() {
                // функция загрузки справочника
                await this.funLBLoad(); // если не ставить await, то ошибка загрузки чаще
                // функция загрузки pivot
                await this.funRBLoad();
            },

            // ------------------------------------------------------------------
            // функция загрузки справочника (в параметрах ничего не должно быть! А то пагинация не работает)
            async funLBLoad(page = 1) {

                // признаки
                this.loading = true;
                this.errored = false;

                // данные post-запроса
                let form = new FormData();
                form.append('page', page);
                form.append('spravName', this.currentSprav);
                form.append('filterName', this.filterLBName);

                await axios
                    .post('/api/towerConstructionMasterVueSpravLoad', form)
                    .then(
                        response => {
                            // запрос прошел
                            // для отладки
                            console.log("Полученные данные справочника:");
                            console.log(response.data);

                            // записать полученные данные в массив
                            this.contentLBData = response.data;
                            // добавить свои поля
                            this.contentLBData.data.forEach(function (item) {
                                item.alreadyAdd = false;
                                item.details = false;
                            });

                            // пометить в справочнике, что уже выбрано в pivot
                            this.funLBSelectAdd();

                            // сообщение пользователю
                            toastr.success('Данные справочника успешно загружены...');
                        },
                    )
                    .catch(error => {
                        // ошибка
                        this.errored = true;
                        // для отладки
                        console.log("Ошибка при загрузке справочника: " + this.currentSprav);
                        console.log(error);
                        // сообщение пользователю
                        toastr.error('Ошибка при загрузке справочника...');
                    })
                    .finally(() => {
                        // финальная обработка

                        // признаки
                        this.loading = false;
                    });
            },

            // ------------------------------------------------------------------
            // функция загрузки pivot
            async funRBLoad() {

                // признаки
                this.loading = true;
                this.errored = false;

                // данные post-запроса
                let form = new FormData();
                form.append('modelName', this.getModelName);
                form.append('modelID', this.getModelId);
                form.append('spravName', this.currentSprav);

                await axios
                    .post('/api/towerConstructionMasterVuePivotLoad', form)
                    .then(
                        response => {
                            // запрос прошел
                            // для отладки
                            console.log("Полученные данные pivot:");
                            console.log(response.data);

                            // очистить массив pivot
                            this.contentRBData = [];
                            // записать все полученные данные в массив
                            let myGetData = response.data;
                            if (typeof(myGetData) !== 'undefined' && myGetData.length > 0) {
                                for (let i = 0; i < myGetData.length; i++) {
                                    this.contentRBData.push({
                                        id: myGetData[i].towerconstructionpivot_id,
                                        album: myGetData[i].towerconstructionpivot != null ? myGetData[i].towerconstructionpivot.album : '',
                                        name: myGetData[i].towerconstructionpivot != null ? myGetData[i].towerconstructionpivot.name : '??? не найден компонент в справочнике',
                                        mark: myGetData[i].towerconstructionpivot != null ? myGetData[i].towerconstructionpivot.mark : '',
                                        series: myGetData[i].towerconstructionpivot != null ? myGetData[i].towerconstructionpivot.series : '',
                                        weight: myGetData[i].towerconstructionpivot != null ? myGetData[i].towerconstructionpivot.weight : 0,
                                        img: myGetData[i].towerconstructionpivot != null ? myGetData[i].towerconstructionpivot.img : '',
                                        kol: myGetData[i].kol,
                                        details: false,
                                        towerconstructionpivot_type: myGetData[i].towerconstructionpivot_type,
                                    });
                                }
                            }

                            // пометить в справочнике, что уже выбрано в pivot
                            this.funLBSelectAdd();

                            // сообщение пользователю
                            toastr.success('Данные успешно загружены...');
                        },
                    )
                    .catch(error => {
                        // ошибка
                        this.errored = true;
                        // для отладки
                        console.log("Ошибка при загрузке pivot: ");
                        console.log(error);
                        // сообщение пользователю
                        toastr.error('Ошибка при загрузке pivot...'); // ошибка может из-за того, что в справочнике строчку удалил, а в модели есть
                    })
                    .finally(() => {
                        // финальная обработка

                        // признаки
                        this.loading = false;
                    });
            },

            // ------------------------------------------------------------------
            // функция сохранения
            async funLBSave() {

                // сообщение пользователю
                toastr.info('Начался процесс сохранения данных...');

                // признаки
                this.loading = true;
                this.errored = false;

                // данные post-запроса
                let form = new FormData();
                form.append('modelName', this.getModelName);
                form.append('modelID', this.getModelId);
                form.append('spravName', this.currentSprav);
                form.append('spravData', JSON.stringify(this.contentRBData));

                await axios
                    .post('/api/towerConstructionMasterVuePivotSave', form)
                    .then(
                        response => {
                            // запрос прошел

                            // сообщить об изменениях родителю
                            this.$emit('updatePivots', this.funGetNow());

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
            // функция добавления строк
            funAddToPivot(getIndex) {

                if (this.contentLBData.data[getIndex].alreadyAdd === false) {
                    // нет, еще не добавляли

                    // записать в массив pivot
                    this.contentRBData.unshift(
                        {
                            id: this.contentLBData.data[getIndex].id,
                            album: this.contentLBData.data[getIndex].album,
                            name: this.contentLBData.data[getIndex].name,
                            mark: this.contentLBData.data[getIndex].mark,
                            series: this.contentLBData.data[getIndex].series,
                            weight: this.contentLBData.data[getIndex].weight,
                            img: this.contentLBData.data[getIndex].img,
                            details: false,
                            kol: 1,
                        });
                    // пометить в справочнике, что уже добавляли
                    this.contentLBData.data[getIndex].alreadyAdd = true;
                }
                else {
                    // сообщене Полоьзователю
                    toastr.warning('Извините, повторно элемент добавить нельзя...');
                }
            },

            // ------------------------------------------------------------------
            // функция удаления строк из pivot
            funRBDelete(getIndex) {

                // вопрос Пользователю
                if (!confirm('Вы уверены?')) return;

                // выбранное id элемента в массиве pivot
                let myID = this.contentRBData[getIndex].id;
                // удаление из массива pivot
                this.contentRBData.splice(getIndex, 1);

                // пометить в справочнике, что уже выбрано в pivot
                this.funLBSelectAdd();
            },

            // ------------------------------------------------------------------
            // пометить в справочнике, что уже выбрано в pivot
            funLBSelectAdd() {

                // записать всем признак false
                this.contentLBData.data.forEach(function (item) {
                    item.alreadyAdd = false;
                });

                // сканировать pivot
                if (this.contentRBData.length > 0) {
                    for (let myRB = 0; myRB < this.contentRBData.length; myRB++) {
                        // найти эту строчку в справочнике
                        for (let myLB = 0; myLB < this.contentLBData.data.length; myLB++) {
                            if (this.contentLBData.data[myLB].id === this.contentRBData[myRB].id) {
                                // записать признак
                                this.contentLBData.data[myLB].alreadyAdd = true;
                            }
                        }
                    }
                }

                // принудительный рендеринг
                //this.$forceUpdate();
            },

            // ------------------------------------------------------------------
            // подробности (справочник)
            funLBViewDetails(getIndex) {
                this.contentLBData.data[getIndex].details = this.contentLBData.data[getIndex].details === true ? false : true;
                // принудительный рендеринг
                this.$forceUpdate();
            },

            // ------------------------------------------------------------------
            // подробности (pivot)
            funRBViewDetails(getIndex) {
                this.contentRBData[getIndex].details = this.contentRBData[getIndex].details === true ? false : true;
                // принудительный рендеринг
                this.$forceUpdate();
            },

            // ------------------------------------------------------------------
            // функция обработки клика на поиске
            funSearch() {

                // признак
                this.globalSearch = false;

                // функция проверки длины поискового выражения
                if (this.funSearchValidate) {
                    // загрузка справочника с фильтром
                    this.funLoadAll();
                }
            },

            // ------------------------------------------------------------------
            // функция проверки длины поискового выражения
            funSearchValidate() {

                // признак
                this.globalSearchData = [];

                // проверка на длину поискового выражения
                if (this.filterLBName.length > 2) {
                    // проверка прошла
                    return true;
                }
                else {
                    alert('Укажите, пожалуйста, поисковое выражение от 3-и и более символов!');
                    return false;
                }
            },

            // ------------------------------------------------------------------
            // поиск по всем справочникам (глобальный)
            async funGlobalSearch() {

                // признак
                this.globalSearch = true;

                // функция проверки длины поискового выражения
                if (!this.funSearchValidate) {
                    return;
                }

                // признаки
                this.loading = true;
                this.errored = false;

                // данные post-запроса
                let form = new FormData();
                form.append('spravNames', JSON.stringify(this.spravData));
                form.append('filterName', this.filterLBName);

                await axios
                    .post('/api/towerConstructionMasterVueSpravlGlobalSearch', form)
                    .then(
                        response => {
                            // запрос прошел
                            // для отладки
                            console.log("Полученные данные глобального поиска:");
                            console.log(response.data);

                            // записать полученные данные в массив
                            this.globalSearchData = response.data;

                            // сообщение пользователю
                            toastr.success('Результаты глобального поиска успешно получены...');
                        },
                    )
                    .catch(error => {
                        // ошибка
                        this.errored = true;
                        // для отладки
                        console.log("Ошибка при глобальном поиске: " + this.currentSprav);
                        console.log(error);
                        // сообщение пользователю
                        toastr.error('Ошибка при глобальном поиске...');
                    })
                    .finally(() => {
                        // финальная обработка

                        // признаки
                        this.loading = false;
                    });
            },

            // ------------------------------------------------------------------
            // сброс фильтра
            funLBSearchClear() {
                this.filterLBName = '';
                this.globalSearch = false;
                this.globalSearchData = [];
                // повторная загрузка
                this.funLBLoad();
            },

            // ------------------------------------------------------------------
            // после глобального поиска перейти в выбранный справочник
            funLBSearchSelect(getIndex) {
                // был выбран справочник
                this.currentSprav = this.globalSearchData[getIndex].spravName;
                this.globalSearch = false;
                this.globalSearchData = [];
                // повторная загрузка
                this.funLBLoad();
            },

            // ------------------------------------------------------------------
            // скопировать из марки
            async funLBCopyFromModel() {

                // признаки
                this.loading = true;
                this.errored = false;

                // данные post-запроса
                let form = new FormData();
                form.append('modelID', this.getModelId);

                await axios
                    .post('/api/towerConstructionMasterVueCopyFromTowerinfo', form)
                    .then(
                        response => {
                            // запрос прошел
                            // для отладки
                            console.log("Копирование из марки:");
                            console.log(response.data);

                            // очистить массив pivot
                            this.contentRBData = [];
                            // записать все полученные данные в массив
                            let myGetData = response.data;
                            if (typeof(myGetData) !== 'undefined' && myGetData.length > 0) {
                                for (let i = 0; i < myGetData.length; i++) {
                                    this.contentRBData.push({
                                        id: myGetData[i].towerconstructionpivot_id,
                                        album: myGetData[i].towerconstructionpivot.album,
                                        name: myGetData[i].towerconstructionpivot.name,
                                        mark: myGetData[i].towerconstructionpivot.mark,
                                        series: myGetData[i].towerconstructionpivot.series,
                                        weight: myGetData[i].towerconstructionpivot.weight,
                                        img: myGetData[i].towerconstructionpivot.img,
                                        kol: myGetData[i].kol,
                                        details: false,
                                        towerconstructionpivot_type: myGetData[i].towerconstructionpivot_type,
                                    });
                                }
                            }

                            // сообщение пользователю
                            toastr.success('Копирование марки успешно завершено...');
                        },
                    )
                    .catch(error => {
                        // ошибка
                        this.errored = true;
                        // для отладки
                        console.log("Ошибка при загрузке справочника: " + this.currentSprav);
                        console.log(error);
                        // сообщение пользователю
                        toastr.error('Ошибка при загрузке справочника...');
                    })
                    .finally(() => {
                        // финальная обработка

                        // признаки
                        this.loading = false;
                    });
            },

            // ------------------------------------------------------------------
            // текущее время
            funGetNow() {
                const today = new Date();
                const date = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
                const time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
                const myDateTime = date + ' ' + time;
                console.log("Сгенерировано время: " + myDateTime);
                return myDateTime;
            },
        }
    }
</script>