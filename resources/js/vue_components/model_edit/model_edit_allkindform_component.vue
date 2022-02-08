<template>
    <div class="page-content">
        <div class="row">
            <div class="col-12 row">
                <div class="col-6">
                    <div class="form-field">
                        <input
                            :autofocus="isModal"
                            :disabled="loading"
                            v-model="modelData.ru_value"
                            class="text-field"
                            type="text"
                            required
                        />
                        <div class="form-input-label">Значение на русском</div>
                    </div>
                    <div class="form-field">
                        <input
                            :disabled="loading"
                            v-model="modelData.value"
                            class="text-field"
                            type="text"
                        />
                        <div class="form-input-label">Значение</div>
                    </div>
                    <div class="form-field">
                        <input
                            :disabled="loading"
                            v-model="modelData.enpro_code"
                            class="text-field"
                            type="text"
                        />
                        <div class="form-input-label">Enpro Code</div>
                    </div>
                    <div class="form-field">
                        <button :disabled="loading" @click="submit" class="button">
                            Сохранить
                        </button>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-field">
            <textarea
                style="resize: none; height: 293px; padding-top: 25px"
                :disabled="loading"
                v-model="modelData.description"
                class="text-field"
            ></textarea>
                        <div class="form-input-label">Описание</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'model_edit_allkindform_component',
    props: ['model', 'id', 'isModal'],
    data() {
        return {
            loading: false,
            errored: false,
            modelData: {
                value: '',
                description: '',
                ru_value: '',
                enpro_code: '',
            },
        };
    },
    mounted() {
        sessionStorage.setItem('selectedModel', this.model)
        if (this.id) {
            this.load();
        }
    },
    methods: {
        submit() {
            if (
                this.modelData.ru_value !== ''
            ) {
                // сообщение пользователю
                toastr.info('Начался процесс сохранения данных...');

                // признаки
                this.loading = true;
                this.errored = false;

                if (this.id) {
                    axios
                        .post(
                            `/api/all_kind/model/${this.model}/id/${this.id}`,
                            this.modelData,
                        )
                        .then((response) => {
                            if (this.isModal) {
                                this.$emit('onNewCreatedData', response?.data?.data || {});
                                this.$emit('save', true);
                                this.modelData = {
                                    value: '',
                                    description: '',
                                    ru_value: '',
                                    enpro_code: '',
                                };
                            } else {
                                const i = window.location.href.indexOf(`/${this.model}/edit`);
                                window.location.href = window.location.href.substr(0, i);
                            }
                            toastr.success('Данные успешно сохранены...');
                        })
                        .catch((error) => {
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
                } else {
                    axios
                        .post(`/api/all_kind/model/${this.model}`, this.modelData)
                        .then((response) => {
                            if (this.isModal) {
                                this.$emit('onNewCreatedData', response?.data?.data || {});
                                this.$emit('save', true);
                                this.modelData = {
                                    value: '',
                                    description: '',
                                    ru_value: '',
                                    enpro_code: '',
                                };
                            } else {
                                const i = window.location.href.indexOf(`/${this.model}/edit`);
                                window.location.href = window.location.href.substr(0, i);
                            }
                            toastr.success('Данные успешно сохранены...');
                        })
                        .catch((error) => {
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
                }
            }
        },
        load() {
            this.loading = true;
            this.errored = false;

            axios
                .get(`/api/all_kind/model/${this.model}/id/${this.id}`)
                .then((r) => {
                    this.modelData.value = r.data.data.value;
                    this.modelData.description = r.data.data.description;
                    this.modelData.ru_value = r.data.data.ru_value;
                    this.modelData.enpro_code = r.data.data.enpro_code;
                    console.log(r);

                    toastr.success('Данные успешно загружены...');
                })
                .catch((er) => {
                    this.errored = true;
                    console.log(er);

                    toastr.error('Ошибка при загрузке данных...');
                })
                .finally(() => {
                    this.loading = false;
                });
        },
    },
};
</script>

<style scoped>
</style>
