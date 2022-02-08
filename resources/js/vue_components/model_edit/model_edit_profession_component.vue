<template>
    <section>
        <div class="page-header">
            <!-- заголовок -->
            <h2 class="page-title">
                Справочник профессии
            </h2>

            <!-- хлебные крошки -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin">Главная</a></li>
                <li class="breadcrumb-item">
                    <a href="##">Справочник профессии</a>
                </li>
                <li class="breadcrumb-item active">Редактирование</li>
            </ol>

            <!-- действия на странице -->
            <div class="page-header-actions">
                <!-- кнопка выход -->
                <a href="/admin/asset" class="button">
                    Выход
                </a>
            </div>
        </div>

        <!-- содержимое страницы-->
        <div class="page-content main-content">
            <div class="row row-lg">
                <div class="col-lg-12">
                    <div class="panel panel-bordered form-icons">
                        <div class="panel-body">
                            <!-- индикатор загрузки -->
                            <div v-if="loading">
                                <img
                                    src="/public/uploads/loading.gif"
                                    style="width: 150px; position:fixed; margin:auto; top:0; bottom:0; left:0; right:0; z-index:9999;"
                                />
                            </div>
                            <div
                                v-else-if="errored"
                                class="alert alert-danger"
                                role="alert"
                            >
                                Запрос к серверу не прошел! Попробуйте,
                                пожалуйста, позже!
                                <img
                                    src="/public/uploads/icons/reload.svg"
                                    style="width:25px; margin-left: 5px;"
                                    v-on:click="funLoad()"
                                />
                            </div>

                            <div class="example-wrap">
                                <div
                                    class="nav-tabs-horizontal"
                                    data-plugin="tabs"
                                >
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li
                                            class="nav-item"
                                            role="presentation"
                                        >
                                            <a
                                                class="nav-link active"
                                                data-toggle="tab"
                                                href="#tabMain"
                                                aria-controls="tabMain"
                                                role="tab"
                                                aria-selected="true"
                                            >
                                                Основное
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content pt-20">
                                        <!-- вкладка Основное -->
                                        <div
                                            class="tab-pane active"
                                            id="tabMain"
                                            role="tabpanel"
                                        >
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <!-- Keylink -->
                                                    <div class="form-field">
                                                        <div
                                                            class="form-input-label"
                                                        >
                                                            Код должности
                                                        </div>
                                                        <input
                                                            type="text"
                                                            class="text-field"
                                                            name="name"
                                                            v-model="
                                                                modelData.codeProfession
                                                            "
                                                            placeholder="Код должности"
                                                        />
                                                    </div>
                                                    <!-- Corporatecode -->
                                                    <div class="form-field">
                                                        <div
                                                            class="form-input-label"
                                                        >
                                                            Сокращенное
                                                            наименование
                                                        </div>
                                                        <input
                                                            type="text"
                                                            class="text-field"
                                                            name="name"
                                                            v-model="
                                                                modelData.corporatecode
                                                            "
                                                            placeholder="Сокращенное наименование"
                                                        />
                                                    </div>
                                                    <!-- serialnumber -->
                                                    <div class="form-field">
                                                        <div
                                                            class="form-input-label"
                                                        >
                                                            Полное наименование
                                                            профессии
                                                        </div>
                                                        <input
                                                            type="text"
                                                            class="text-field"
                                                            name="name"
                                                            v-model="
                                                                modelData.serialnumber
                                                            "
                                                            placeholder="Полное наименование профессии"
                                                        />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div
                                                        style="text-align: right; margin-bottom: 30px;"
                                                    >
                                                        <button
                                                            type="button"
                                                            class="button bordered"
                                                            @click="funSave()"
                                                        >
                                                            Сохранить
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
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
    name: "profession_edit",
    props: {
        getModelId: Number // id линии
    },
    data() {
        return {
            loading: false,
            uploading: false,
            errored: false,
            modelData: {
                id: null,
                //код должности
                codeProfession: null,
                // короткое имя
                shortName: null,
                // полное имя
                fullName: null
            },

            progressValue: 0, // процент загрузки
            lastUpdateDateTime: null // время последнего обновления дочернего компонента pivot
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
            form.append("modelID", this.getModelId);

            axios
                .post("##", form)
                .then(response => {
                    // запрос прошел
                    // для отладки
                    console.log("Полученные данные по всей линии:");
                    console.log(response.data);
                    // записать полученные данные в массив
                    this.modelData.id = response.data.id;
                    this.modelData.codeProfession =
                        response.data.codeProfession;
                    this.modelData.shortName = response.data.shortName;
                    this.modelData.fullName = response.data.fullName;

                    // сообщение пользователю
                    toastr.success("Данные успешно загружены...");
                })
                .catch(error => {
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

        // ------------------------------------------------------------------
        // функция сохранения
        async funSave() {
            // сообщение пользователю
            toastr.info("Начался процесс сохранения данных...");

            // признаки
            this.loading = true;
            this.errored = false;

            // данные post-запроса
            let form = new FormData();
            // form.append("modelID", this.getModelId);
            form.append("modelData", JSON.stringify(this.modelData));

            await axios
                .post("#", form)
                .then(response => {
                    // запрос прошел

                    // проверка на новую запись
                    if (!this.getModelId > 0) {
                        // записать присвоенное id
                        this.getModelId = response.data.acline.id;
                        // дописать в url присвоенный id
                        history.pushState(
                            null,
                            null,
                            window.location.href + "/" + this.getModelId
                        );
                    }

                    // сообщение пользователю
                    toastr.success("Данные успешно сохранены...");
                })
                .catch(error => {
                    // ошибка
                    this.errored = true;
                    console.log(error);
                    // сообщение пользователю
                    toastr.error("Ошибка при сохранении данных...");
                })
                .finally(() => {
                    // финальная обработка

                    // признаки
                    this.loading = false;
                });
        }
    }
};
</script>
