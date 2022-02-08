<template>

    <!-- индикатор загрузки -->
    <div v-if="loading">
        <img src='/public/uploads/loading.gif'
             class="img_loading"
        />
    </div>

    <!-- индикатор ошибки загрузки -->
    <div v-else-if="errored" class="alert alert-danger" role="alert">
        Запрос к серверу не прошел! Попробуйте, пожалуйста, позже!

        <!--        <img src="/public/uploads/icons/reload.svg"-->
        <!--             class="img_reload"-->
        <!--             :click="funLoadAll()"-->
        <!--        />-->

    </div>

</template>

<script>
export default {
    props: {
        loading: Boolean,
        errored: Boolean,
    },
}
</script>

<!-- стили -->
<style scoped>

.img_loading {
    width: 150px;
    position: fixed;
    margin: auto;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 9999;
}

.img_reload {
    width: 25px;
    margin-left: 5px;
    z-index: 9999;
}
</style>
