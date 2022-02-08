<template>
    <section>
        <div class="row">
            <div class="col-md-6">
                <!-- диспетчерский номер -->
                <div
                    v-for="(input, index) in modelInput"
                    :class="classObject(input.type)"
                    :key="index"
                >
                    <div
                        v-if="input.type !== 'boolean'"
                        class="form-input-label"
                    >
                        {{ input.label }}
                    </div>
                    <input
                        v-if="input.type === 'text' || input.type === 'email'"
                        :type="input.type"
                        class="text-field"
                        name="name"
                        v-model="input.value"
                        :placeholder="input.label"
                    />
                    <input
                        v-else-if="input.type === 'float'"
                        type="number"
                        step="0.1"
                        class="text-field"
                        name="name"
                        v-model="input.value"
                        :placeholder="input.label"
                    />
                    <div v-else-if="input.type === 'boolean'">
                        <label
                            ><input type="checkbox" v-model="input.value" />
                            <span class="box"></span>
                            <span>{{ input.label }}</span></label
                        >
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div style="text-align: right; margin-bottom: 30px;">
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
    </section>
</template>

<script>
export default {
    props: {
        modelName: {
            required: true,
            type: String
        },
        modelSavePath: {
            required: true,
            type: String
        },
        getModelId,
        modelInput: {
            type: Object,
            default: function() {
                return {
                    type: {
                        value: "",
                        type: "text",
                        label: "type"
                    },
                    utcNumber: {
                        value: "",
                        type: "text",
                        label: "utcNumber"
                    },
                    serialNumber: {
                        value: "",
                        type: "text",
                        label: "serialNumber"
                    },
                    lotNumber: {
                        value: "",
                        type: "text",
                        label: "lotNumber"
                    },
                    purchasePrice: {
                        value: "",
                        type: "float",
                        label: "purchasePrice"
                    },
                    electronicAddress: {
                        value: "",
                        type: "email",
                        label: "email"
                    },
                    initialCondition: {
                        value: "",
                        type: "text",
                        label: "initialCondition"
                    },
                    initialLossOfLife: {
                        value: "",
                        type: "float",
                        label: "initialLossOfLife"
                    },
                    aliasName: {
                        value: "",
                        type: "text",
                        label: "aliasName"
                    },
                    name: {
                        value: "",
                        type: "text",
                        label: "name"
                    },
                    critical: {
                        value: true,
                        type: "boolean",
                        label: "critical"
                    }
                };
            }
        }
    },
    data() {
        return {
            loading: false,
            errored: false,
            currentModel: "", // текущий модель
            currentModelSave: ""
        };
    },
    computed: {},
    mounted() {
        // функция загрузки данных
        this.funLoad();
    },

    // мои методы и функции
    methods: {
        modelInputData() {
            let data = {};
            for (const item in this.modelInput) {
                data[item] = this.modelInput[item].value;
            }
            console.log(data);
            return data;
        },
        // ------------------------------------------------------------------
        // функция загрузки
        funLoad() {
            // признаки
            this.loading = true;
            this.errored = false;

            // данные post-запроса
            let form = new FormData();
            form.append("modelName", "Towerinfo");
            form.append("modelID", this.getModelId);

            axios
                .post("/api/getModelRecords", form)
                .then(response => {
                    // запрос прошел
                    // для отладки
                    //console.log("Полученные данные:");
                    //console.log(response.data);

                    // записать полученные данные в массив
                    this.modelData = response.data;

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
        // функция загрузки данных

        classObject(type) {
            return type === "boolean" ? "checkbox mt-15" : "form-field";
        },

        // функция сохранения
        async funSave() {
            // сообщение пользователю
            toastr.info("Начался процесс сохранения данных...");
            // данные post-запроса
            let form = new FormData();
            form.append("modelName", this.currentModel);
            form.append(
                "spravRowContent",
                JSON.stringify(this.modelInputData())
            );
            console.log(form.get("spravRowContent"));
            await axios
                .post(`/api/${this.currentModelSave}`, form)
                .then(response => {
                    // запрос прошел
                    // для отладки
                    console.log("Полученные данные:");
                    console.log(response.data);

                    // записать обновленную строчку в массив

                    // сообщение пользователю
                    toastr.success("Данные успешно сохранены...");
                })
                .catch(error => {
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

<style></style>
