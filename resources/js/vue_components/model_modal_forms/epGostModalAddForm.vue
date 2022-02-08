<template>
    <div class="row">
        <div class="col-12">
            <div class="row">
                <h4 class="text-left">Добавить новый гост</h4>
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
                        :class="`text-field app-construction-kind__input ${ $v.modelData.name.$error ? 'is-invalid-borders' : '' }`"
                        name="name"
                        v-model="modelData.name"
                        placeholder="Введите наименование"
                        :disabled="isProcessing"
                        @blur="$v.modelData.name.$touch()"
                    />

                    <div v-if="$v.modelData.name.$error" class="form-text text-danger text-left">* Пожалуйста проверьте данные</div>
                    </div>
                </div>

                <div class="col-12 mb-10 px-0">
                    <div class="form-input-label mb-3 text-left">
                        Ссылка на ключ
                    </div>
                    <div class="form-field">
                        <input
                        type="text"
                        :class="`text-field app-construction-kind__input ${ $v.modelData.keylink.$error ? 'is-invalid-borders' : '' }`"
                        name="keylink"
                        v-model="modelData.keylink"
                        placeholder="Ссылка"
                        :disabled="isProcessing"
                        @blur="$v.modelData.keylink.$touch()"
                    />
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
                name: '',
                keylink: null,
            }
        }
    },
    validations: {
        modelData: {
            name: {
                required,
                minLength: minLength(1)
            },
            keylink: {

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

                axios.post(`/api/gost`, this.modelData)
                    .then(res => {
                        if(res.status === 200){
                            toastr.success('Данные успешно добавлены!');
                            console.log(res);
                            
                            this.resetFormInputs();
                            this.$emit('onNewCreated');
                            this.$emit('onNewCreatedData', res?.data?.data || []);
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
            this.modelData.name = '';
            this.modelData.keylink = null;

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