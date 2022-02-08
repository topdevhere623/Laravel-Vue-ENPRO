<template>
    <div class="row">
        <div class="col-12">
            <div class="row">
                <h4 class="text-left">Добавить новый тип конструкции</h4>
            </div>

            <div class="row py-10">
                <div class="col-12 mb-10 px-0">
                    <div class="form-input-label mb-3 text-left">
                        Наименование
                    </div>
                    <div class="form-field">
                    <input
                        ref="modelNameInputRef"
                        type="text"
                        :class="`text-field app-construction-kind__input ${ $v.modelData.ru_value.$error ? 'is-invalid-borders' : '' }`"
                        name="coreCoilsWeight"
                        v-model="modelData.ru_value"
                        placeholder="Введите наименование"
                        :disabled="isProcessing"
                        @blur="$v.modelData.ru_value.$touch()"
                    />

                    <div v-if="$v.modelData.ru_value.$error" class="form-text text-danger text-left">* Пожалуйста проверьте данные</div>
                    </div>
                </div>

                <div class="col-12 mb-10 px-0">
                    <div class="form-input-label mb-3 text-left">
                        Описание
                    </div>
                    <div class="form-field">
                        <textarea
                            v-model="modelData.description"
                            class="text-field form-control app-construction-kind__input"
                            rows="3"
                            name="coreCoilsWeight"
                            placeholder="Описание"
                        ></textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 px-0 pt-30 d-flex justify-content-end align-items-center">
                    <a
                        href="#"
                        class="button bordered d-flex justify-content-center align-items-center text-center"
                        style="min-width: 140px;"
                        @click.prevent="isProcessing ? null : saveModelData()"
                    >
                        <button-loading-spinner v-if="isProcessing"/>
                        <span v-else>
                            Сохранить
                        </span> 
                    </a>
                </div>
            </div>
        </div> 
    </div>
</template>

<script>
import axios from 'axios';
import { required, minLength } from 'vuelidate/lib/validators';

export default {
    data(){
        return {
            isProcessing: false,
            modelData: {
                value: null,
                ru_value: '',
                enpro_code: null,
                description: ''
            }
        }
    },
    validations: {
        modelData: {
            ru_value: {
                required,
                minLength: minLength(1)
            },
            description: {

            }
        },
    },
    mounted () {
        this.$nextTick(() => this.$refs.modelNameInputRef.focus());
    },
    methods: {
        saveModelData(){
            this.$v.$touch();

            if (!this.$v.$invalid) {
                this.isProcessing = true;

                axios.post(`/api/all_kind/model/TransformerConstructionKind`, this.modelData)
                    .then(res => {
                        if(res.status === 200){
                            toastr.success('Данные успешно добавлены!');
                            console.log(res);
                            
                            this.resetFormInputs();
                            this.$emit('onNewCreated');
                        } else {
                            toastr.error('Ошибка при обработке данных...');
                        }
                    })
                    .catch(err => { 
                        toastr.error('Ошибка при обработке данных...');
                        console.log(err);
                    })
                    .finally(() => {
                        this.isProcessing = false;
                    })
            } else {
                toastr.error('Ошибка валидации! Проверьте поля!');
            }
        },

        resetFormInputs(){
            this.modelData.value = null;
            this.modelData.ru_value = '';
            this.modelData.enpro_code = null;
            this.modelData.description = '';

            this.$v.$reset();
        }
    }
}
</script>

<style lang="scss">
.app-construction-kind {
  &__input {
    height: auto !important;
    padding: 13px 20px !important;
  }
}

.is-invalid-borders {
    border: 1px solid #dc3545 !important;
}
</style>