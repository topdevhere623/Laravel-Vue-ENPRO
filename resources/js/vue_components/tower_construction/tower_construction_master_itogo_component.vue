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
                <img src="/public/uploads/icons/reload.svg" style="width:25px; margin-left: 5px;" v-on:click="funLoad()"/>
            </div>

            <!-- справочник -->
            <div class="table-wrapper table-auto-height">
                <table class="table custom-table" data-plugin="selectable" data-row-selectable="false">
                    <thead>
                    <tr>
                        <th class="w-30 text-nowrap">
                            id
                        </th>
                        <!--<th>-->
                        <!--Тип-->
                        <!--</th>-->
                        <!--<th>-->
                        <!--Серия, ГОСТ, ТУ-->
                        <!--</th>-->
                        <th>
                            Обозначение
                        </th>
                        <th>
                            Наименование
                        </th>
                        <!--<th>-->
                        <!--Марка-->
                        <!--</th>-->
                        <th>
                            Масса, кг.
                        </th>
                        <th>
                            Кол-во
                        </th>
                        <th>
                            Эскиз
                        </th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr v-for="(item, index) in modelData" :key="item.index">
                        <td class="text-nowrap">
                            {{ item.id }}
                        </td>
                        <!--<td>-->
                        <!--{{ item.towerconstructionpivot_type }}-->
                        <!--</td>-->
                        <!--<td>-->
                        <!--{{ item.series }}-->
                        <!--</td>-->
                        <td>
                            {{ item.album }}
                        </td>
                        <td>
                            <p v-bind:class="{textUnderlineClass: item.isTitle}">
                                {{ item.name }}
                            </p>
                        </td>
                        <!--<td>-->
                        <!--{{ item.mark }}-->
                        <!--</td>-->
                        <td>
                            {{ item.weight }}
                        </td>
                        <td>
                            {{ item.kol }}
                        </td>
                        <td>

                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="row mt-10">
                <div class="col-md-7">

                    <!-- нижняя панель -->
                    <div class="table-bottom-bar">
                        <div class="left">
                            <a class="link-icon" @click="funLoad()">Обновить</a>
                        </div>
                    </div>

                </div>
                <div class="col-md-5">

                    <!-- итоговая рассчетная масса всех компонентов -->
                    <div class="form-field">
                        <div class="input-group-icon input-group">
                            <div class="form-input-label">Итоговая масса всех компонентов, кг.</div>
                            <input type="text" class="text-field text-right" disabled v-model="weightItogo">
                            <span class="input-group-addon">
                                <span class="icon fa-calculator" aria-hidden="true" @click="funLoad()"></span>
                            </span>
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
            'getModelName': String, // имя модели (марка опоры, сборный агрегат и пр.)
            'getModelId': Number, // id
            'getLastUpdate': String, // последнее обновление компонента pivot (чтоб сделать перерасчет)
        },
        data() {
            return {
                loading: false,
                uploading: false,
                errored: false,
                weightItogo: null,
                modelData: [],
                currentType: null,
            }
        },

        watch: {
            // слежение за переменной
            getLastUpdate: function (value) {
                console.log('Было обновление компонента pivot: ', value);
                // загрузить
                this.funLoad();
            }
        },

        mounted() {
            //console.log("ID для загрузки суммарной спецификации: " + this.getModelId);

            // загрузить
            this.funLoad();
        },

        // мои методы и функции
        methods: {

            // ------------------------------------------------------------------
            // функция загрузить
            funLoad() {

                // сообщение пользователю
                toastr.info('Начался процесс подготовки суммарной спецификации...');

                // признаки
                this.loading = true;
                this.errored = false;

                // данные post-запроса
                let form = new FormData();
                form.append('modelName', this.getModelName);
                form.append('modelID', this.getModelId);

                axios
                    .post('/api/towerConstructionMasterVueWeightItogo', form)
                    .then(
                        response => {
                            // запрос прошел
                            // для отладки
                            console.log("Полученные данные суммарной спецификации для модели = " + this.getModelName + ' с ID = ' + this.getModelId);
                            console.log(response.data);

                            // итоговая масса - обрезать до десятых
                            this.weightItogo = (response.data.weightItogo).toFixed(2);

                            // подготовить список для вывода - вставить строки с типом
                            this.modelData = [];
                            let myData = response.data.modelData;
                            if (typeof(myData) !== 'undefined' && myData.length > 0) {
                                for (let i = 0; i < myData.length; i++) {

                                    if (myData[i].towerconstructionpivot_type !== this.currentType) {
                                        // это строка разделитель

                                        // вставить название группы (справочника по русски)
                                        let myTitle = '';
                                        if (myData[i]['towerconstructionpivot_type'].includes('Towerconstructionbasic')) myTitle = 'Железобетонные элементы';
                                        if (myData[i]['towerconstructionpivot_type'].includes('Towerconstructionwood')) myTitle = 'Деревянные элементы';
                                        if (myData[i]['towerconstructionpivot_type'].includes('Towerconstructionmetal')) myTitle = 'Стальные конструкции';
                                        if (myData[i]['towerconstructionpivot_type'].includes('Towerconstructionaccessory')) myTitle = 'Арматура линейная';
                                        if (myData[i]['towerconstructionpivot_type'].includes('Towerconstructioninsulator')) myTitle = 'Изоляторы';
                                        if (myData[i]['towerconstructionpivot_type'].includes('Towerconstructionstandart')) myTitle = 'Изделия стандартные';
                                        if (myData[i]['towerconstructionpivot_type'].includes('Towerconstructionbase')) myTitle = 'Фундаменты';
                                        if (myData[i]['towerconstructionpivot_type'].includes('Towerconstructionaggregate')) myTitle = 'Сборные агрегаты';

                                        this.modelData.push({
                                            name: myTitle.toUpperCase(),
                                            isTitle: true,
                                        });
                                        this.currentType = myData[i].towerconstructionpivot_type;
                                    }

                                    // вставить данные компонента
                                    this.modelData.push({
                                        id: myData[i].towerconstructionpivot != null ? myData[i].towerconstructionpivot.id : 0,
                                        series: myData[i].towerconstructionpivot != null ? myData[i].towerconstructionpivot.series : '',
                                        album: myData[i].towerconstructionpivot != null ? myData[i].towerconstructionpivot.album : '',
                                        name: myData[i].towerconstructionpivot != null ? myData[i].towerconstructionpivot.name : '??? не найден компонент в справочнике',
                                        mark: myData[i].towerconstructionpivot != null ? myData[i].towerconstructionpivot.mark : '',
                                        weight: myData[i].towerconstructionpivot != null ? myData[i].towerconstructionpivot.weight : 0,
                                        kol: myData[i].kol,
                                        isTitle: false,
                                    });
                                }
                            }
                            // обнулить, чтоб заголовок не исчезал при повторном обновлении
                            this.currentType = null;

                            // сообщение пользователю
                            toastr.success('Суммарная спецификация успешно подготовлена...');
                        },
                    )
                    .catch(error => {
                        // ошибка
                        this.errored = true;
                        // сообщение пользователю
                        toastr.error('Ошибка при подготовке суммарной спецификации...');
                    })
                    .finally(() => {
                        // финальная обработка

                        // признаки
                        this.loading = false;
                    });
            },
        }
    }
</script>

<style>
    .textUnderlineClass {
        text-decoration: underline;
    }
</style>