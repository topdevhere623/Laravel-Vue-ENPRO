<template>
    <section>
        <div class="page-header">
            <!-- заголовок -->
            <h2 class="page-title">
                Общие данные
            </h2>

            <!-- хлебные крошки -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin">Главная</a></li>
                <li class="breadcrumb-item">
                    <a href="/admin/asset">Общие данные</a>
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
                                v-else-if="errored" class="alert alert-danger" role="alert"
                            >
                                Запрос к серверу не прошел! Попробуйте, пожалуйста, позже!
                                <img
                                    src="/public/uploads/icons/reload.svg" style="width:25px; margin-left: 5px;"
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
                                            class="nav-item" role="presentation"
                                        >
                                            <a
                                                class="nav-link active" data-toggle="tab" href="#tabMain"
                                                aria-controls="tabMain" role="tab" aria-selected="true"
                                            >
                                                Основное
                                            </a>
                                        </li>
                                        <li
                                            class="nav-item" role="presentation"
                                        >
                                            <a
                                                class="nav-link" data-toggle="tab" href="#tabParameters"
                                                aria-controls="tabParameters" role="tab" aria-selected="true"
                                            >
                                                Дополнительные параметры
                                            </a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link" data-toggle="tab" href="#tabOtherData"
                                               aria-controls="tabOtherData" role="tab" aria-selected="true">
                                                Другие данные
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content pt-20">
                                        <!-- вкладка Основное -->
                                        <div class="tab-pane active" id="tabMain" role="tabpanel">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <!-- Keylink -->
                                                    <div class="form-field">
                                                        <div class="form-input-label">
                                                            Keylink
                                                        </div>
                                                        <input type="text" class="text-field" name="name"
                                                               v-model="modelData.Keylink" placeholder="Keylink"/>
                                                    </div>
                                                    <!-- Corporatecode -->
                                                    <div class="form-field">
                                                        <div class="form-input-label">
                                                            Corporatecode
                                                        </div>
                                                        <input type="text" class="text-field" name="name"
                                                               v-model="modelData.corporatecode"
                                                               placeholder="Corporatecode"/>
                                                    </div>
                                                    <!-- serialnumber -->
                                                    <div class="form-field">
                                                        <div class="form-input-label">
                                                            Серийный номер
                                                            (serialnumber)
                                                        </div>
                                                        <input type="text" class="text-field" name="name"
                                                               v-model="modelData.serialnumber"
                                                               placeholder="Серийный номер (serialnumber)"/>
                                                    </div>
                                                    <!-- inventorynumber -->
                                                    <div class="form-field">
                                                        <div class="form-input-label">
                                                            Инвентарный номер
                                                            (inventorynumber)
                                                        </div>
                                                        <input type="text" class="text-field" name="name"
                                                               v-model="modelData.inventorynumber"
                                                               placeholder="Инвентарный номер (inventorynumber)"/>
                                                    </div>
                                                    <!-- Orgmanagerkey -->
                                                    <div class="form-field">
                                                        <div class="form-input-label">
                                                            Orgmanagerkey
                                                        </div>
                                                        <input type="text" class="text-field" name="name"
                                                               v-model="modelData.orgmanagerkey"
                                                               placeholder="Orgmanagerkey"/>
                                                    </div>
                                                    <!-- Fgc_parentkey -->
                                                    <div class="form-field">
                                                        <div class="form-input-label">
                                                            Fgc_parentkey
                                                        </div>
                                                        <input type="text" class="text-field" name="name"
                                                               v-model="modelData.fgc_parentkey"
                                                               placeholder="Fgc_parentkey"/>
                                                    </div>
                                                    <!-- Orgassetownerkey -->
                                                    <div class="form-field">
                                                        <div class="form-input-label">
                                                            Orgassetownerkey
                                                        </div>
                                                        <input type="text" class="text-field" name="name"
                                                               v-model="modelData.orgassetownerkey"
                                                               placeholder="Orgassetownerkey"/>
                                                    </div>
                                                    <!-- type -->
                                                    <div class="form-field">
                                                        <div class="form-input-label">
                                                            Тип (type)
                                                        </div>
                                                        <input type="text" class="text-field" name="name"
                                                               v-model="modelData.type" placeholder="Тип (type)"/>
                                                    </div>
                                                    <!-- Assetinfokey -->
                                                    <div class="form-field">
                                                        <div class="form-input-label">
                                                            Assetinfokey
                                                        </div>
                                                        <input type="text" class="text-field" name="name"
                                                               v-model="modelData.assetinfokey"
                                                               placeholder="Assetinfokey"/>
                                                    </div>
                                                    <!-- Manufactureddt -->
                                                    <div class="form-field">
                                                        <div class="form-input-label">
                                                            Manufactureddt
                                                        </div>
                                                        <input type="text" class="text-field" name="name"
                                                               v-model="modelData.manufactureddt"
                                                               placeholder="Manufactureddt"/>
                                                    </div>
                                                    <!-- Assetcol -->
                                                    <div class="form-field">
                                                        <div class="form-input-label">
                                                            Assetcol
                                                        </div>
                                                        <input type="text" class="text-field" name="name"
                                                               v-model="modelData.assetcol" placeholder="Assetcol"/>
                                                    </div>
                                                    <!-- (comment) -->
                                                    <div class="form-field">
                                                        <div class="form-input-label">
                                                            Комментарий (comment)
                                                        </div>
                                                        <input type="text" class="text-field" name="name"
                                                               v-model="modelData.comment" placeholder="comment"/>
                                                    </div>
                                                    <!-- (cadastralnumber) -->
                                                    <div class="form-field">
                                                        <div class="form-input-label">
                                                            Кадастровый номер
                                                            (cadastralnumber)
                                                        </div>
                                                        <input type="text" class="text-field" name="name"
                                                               v-model="modelData.cadastralnumber"
                                                               placeholder="Кадастровый номер (cadastralnumber)"/>
                                                    </div>
                                                    <!-- (Производитель) -->
                                                    <div class="form-field">
                                                        <div
                                                            class="form-input-label"
                                                        >
                                                            Производитель
                                                            (manufacturer)
                                                        </div>
                                                        <input type="text" class="text-field" name="name"
                                                               v-model="modelData.manufacturer"
                                                               placeholder="Производитель (manufacturer)"/>
                                                    </div>
                                                    <!-- Inventorynumbermp -->
                                                    <div class="form-field">
                                                        <div class="form-input-label">
                                                            Inventorynumbermp
                                                        </div>
                                                        <input type="text" class="text-field" name="name"
                                                               v-model="modelData.inventorynumbermp"
                                                               placeholder="inventorynumbermp"/>
                                                    </div>
                                                    <!-- Inventorynumberbp -->
                                                    <div class="form-field">
                                                        <div class="form-input-label">
                                                            Inventorynumberbp
                                                        </div>
                                                        <input type="text" class="text-field" name="name"
                                                               v-model="modelData.inventorynumberbp"
                                                               placeholder="inventorynumberbp"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div style="text-align: right; margin-bottom: 30px;">
                                                        <button type="button" class="button bordered"
                                                                @click="funSave()">
                                                            Сохранить
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- вкладка доп данные -->
                                        <div class="tab-pane" id="tabParameters" role="tabpanel">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <options-sprav-component get-sprav="Towermaterial"
                                                                             get-title="Материал опоры"
                                                                             get-field="towermaterial_id"
                                                                             :get-current-id="modelData.towermaterial_id"
                                                                             @spravSelect="funUpdateSpravTowermaterial">
                                                    </options-sprav-component>
                                                </div>
                                                <div class="col-md-6"></div>
                                            </div>
                                        </div>
                                        <!-- вкладка другие данные -->
                                        <div class="tab-pane" id="tabOtherData" role="tabpanel">

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
    name: "asset_edit",
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
                name: null,
                keylink: null,
                corporatecode: null,
                serialnumber: null,
                inventorynumber: null,
                orgmanagerkey: null,
                fgc_parentkey: null,
                orgassetownerkey: null,
                type: null,
                assetinfokey: null,
                manufactureddt: null,
                assetcol: null,
                comment: null,
                cadastralnumber: null,
                manufacturer: null,
                inventorynumbermp: null,
                inventorynumberbp: null,
                asset_id:null,
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
                .post("/api/assetVueLoad", form)
                .then(response => {
                    // запрос прошел
                    // для отладки
                    console.log("Полученные данные по всей линии:");
                    console.log(response.data);
                    // записать полученные данные в массив
                    this.modelData.id = response.data.id;
                    this.modelData.name = response.data.name;
                    this.modelData.keylink = response.data.keylink;
                    this.modelData.corporatecode = response.data.corporatecode;
                    this.modelData.serialnumber = response.data.serialnumber;
                    this.modelData.inventorynumber =
                        response.data.inventorynumber;
                    this.modelData.orgmanagerkey = response.data.orgmanagerkey;
                    this.modelData.fgc_parentkey = response.data.fgc_parentkey;
                    this.modelData.orgassetownerkey =
                        response.data.orgassetownerkey;
                    this.modelData.type = response.data.type;
                    this.modelData.assetinfokey = response.data.assetinfokey;
                    this.modelData.manufactureddt =
                        response.data.manufactureddt;
                    this.modelData.assetcol = response.data.assetcol;
                    this.modelData.comment = response.data.comment;
                    this.modelData.cadastralnumber =
                        response.data.cadastralnumber;
                    this.modelData.manufacturer = response.data.manufacturer;
                    this.modelData.inventorynumbermp =
                        response.data.inventorynumbermp;
                    this.modelData.inventorynumberbp =
                        response.data.inventorynumberbp;
                    this.modelData.asset_id=response.data.asset_id

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
            form.append("modelID", this.getModelId);
            form.append("modelData", JSON.stringify(this.modelData));

            await axios
                .post("/api/assetVueSave", form)
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
