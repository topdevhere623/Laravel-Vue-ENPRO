<template>
    <section>

        <!-- индикатор загрузки -->
        <div v-if="loading">
            <img src='/public/uploads/loading.gif' style='width: 150px; position:fixed; margin:auto; top:0; bottom:0; left:0; right:0; z-index:9999;'/>
        </div>

        <div v-else-if="errored" class="alert alert-danger" role="alert">
            Запрос к серверу не прошел! Попробуйте, пожалуйста, позже!
            <img src="/public/uploads/icons/reload.svg" style="width:25px; margin-left: 5px;" v-on:click="funLoad()"/>
        </div>

        <div class="row">
            <div class="col-12">

                <div>
                    <table class="table custom-table" data-plugin="selectable" data-row-selectable="false">

                        <thead>
                        <tr>
                            <th>
                                {{ this.getTitle }}
                            </th>
                            <th class="text-nowrap">
                                <div class="flex-row text-right">
                                    <button type="button" class="button" @click="funUpdate('audit')">
                                        Анализ
                                    </button>
                                    <button type="button" class="button bordered" @click="funUpdate('update')">
                                        Правка
                                    </button>
                                </div>
                            </th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr>
                            <td colspan="2">
                                <textarea v-html="myComment" style="width: 100%; height: 550px; padding: 15px 20px;" wrap="hard" readonly></textarea>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </section>
</template>

<script>
export default {
    props: {
        getMode: String, // модуль, что будет делать этот компонент
        getTitle: String, // название модуля
    },
    data() {
        return {
            loading: false,
            errored: false,
            myComment: null,
        }
    },

    mounted() {
    },

    // мои методы и функции
    methods: {

        // ------------------------------------------------------------------
        // функция обновления значения в базе длин всех пролетов ЛЭП
        async funUpdate(getRegim) {

            // вопрос Пользователю
            if (getRegim === 'update') {
                if (!confirm('Вы уверены, что хотите внести правки в базу? \n После этого будет уже невозможно вернуть прежние значения! \nЭто может занять некоторое время! \nДождитесь, пожалуйста, обязательно завершения процесса! \n Все равно продолжить?')) return;
            }

            // сообщение пользователю
            toastr.info('Запрос отправлен...');

            // признаки
            this.loading = true;
            this.errored = false;

            // данные post-запроса
            let form = new FormData();
            form.append('mode', this.getMode);
            form.append('regim', getRegim);

            await axios
                .post('/api/bazaVueRepair', form)
                .then(
                    response => {
                        // запрос прошел
                        // для отладки
                        console.log(response.data);

                        // в полученном комментарии заменить символ переноса строки
                        let myComment = response.data;
                        myComment = myComment.replaceAll('<br>', '&#13;&#10;');
                        // записать в поле
                        this.myComment = myComment;

                        // сообщение пользователю
                        toastr.success('Запрос обработан...');
                    },
                )
                .catch(error => {
                    console.log(error);
                    // ошибка
                    this.errored = true;
                    // сообщение пользователю
                    toastr.error('Ошибка при обработке запроса..');
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
