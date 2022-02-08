<template>
    <section>

        <!-- индикатор загрузки -->
        <loading-component :get-loading="loading"></loading-component>

        <div class="m-30">
            <h4>Укажите, пожалуйста, ЛЭП для загрузки на карту:</h4>

            <div class="border p-20 ">

                <!-- крестик -->
                <div class="text-right">
                    <span class="wb-close" @click="funOpenAcline()"></span>
                </div>

                <!-- область в два столбца -->
                <div
                    v-if="(typeof(aclineNames.data) !== 'undefined' && (aclineNames.data).length > 0)"
                    class="textcols">

                    <!-- вывод в цикле -->
                    <p v-for="item in aclineNames.data"
                       :key="item.id"
                       class="pAclineName"
                       @click="funOpenAcline(item.id)"
                    >
                        {{ item.id }} - {{ item.name }}
                    </p>
                </div>
                <div v-else>
                    <p>
                        К сожалению, линий ЛЭП в базе еще нет!
                    </p>
                </div>
            </div>

            <!-- пагинация -->
            <div class="float-right">
                <small class="mt-15 mr-15">Строк: {{ aclineNames.total }}</small>
                <pagination :limit=5 :data="aclineNames" @pagination-change-page="funLoadContent"></pagination>
            </div>

        </div>

    </section>
</template>

<script>
import LoadingComponent from "../loading_component";

export default {
    components: {
        'loading-component': LoadingComponent,
    },
    props: {
        getNeedView: Boolean,
    },
    data() {
        return {
            loading: false,
            errored: false,

            aclineNames: {},
            sorting: {col: 'updated_at', direct: 'desc'},
        }
    },
    mounted() {

        // загрузка содержимого таблицы
        this.funLoadContent();
    },
    created() {
        // подписаться на получение с шины
        this.$eventBus.$on('fromControl', this.funFromControl)
    },
    beforeDestroy() {
        // отписаться на получение с шины
        this.$eventBus.$off('fromControl');
    },
    methods: {

        // ------------------------------------------------------------------
        // загрузка содержимого таблицы
        funLoadContent(page = 1) {

            // признаки
            this.loading = true;
            this.errored = false;

            // данные post-запроса
            let form = new FormData();
            form.append('page', page);
            form.append('sortCol', this.sorting.col);
            form.append('sortDirect', this.sorting.direct);

            axios
                .post('/api/aclineVueIndex', form)
                .then(
                    response => {
                        // для отладки
                        console.log("Загрузка списка всех ЛЭП успешно прошла!");
                        console.log(response.data);

                        // запрос прошел - записать полученные данные в массив
                        this.aclineNames = response.data;
                        // сообщение пользователю
                        toastr.success('Данные успешно загружены...');
                    },
                )
                .catch(error => {
                    // ошибка
                    this.errored = true;
                    // для отладки
                    console.log("Ошибка!");
                    console.log(error);
                    // сообщение пользователю
                    toastr.error('Ошибка при загрузке данных...');
                })
                .finally(() => {

                    // финальная обработка
                    this.loading = false;
                });
        },

        // ------------------------------------------------------------------
        // получение события с панели управления картой
        funFromControl() {

        },

        // ------------------------------------------------------------------
        // открыть указанную ЛЭП
        funOpenAcline(getAclineID = null) {

            // сообщить шине
            this.$eventBus.$emit('fromOpenAcline', getAclineID);
        },
    }
}
</script>

<style scoped>
.pAclineName {
    text-decoration: none;
    cursor: pointer;
}

.pAclineName:hover {
    text-decoration: underline;
}

.textcols {
    column-width: 48%;
    column-count: 2;
    column-gap: 4%;
}
</style>
