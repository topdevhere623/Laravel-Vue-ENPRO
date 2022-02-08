<template>
    <section>
        <div class="row">
            <div class="col-md-6"></div>
            <div class="col-md-6"></div>
        </div>
    </section>
</template>
<script>
export default {
    name: "asset_parameter",
    props: {
        getModelId: Number // id линии
    },
    data() {
        return {
            id: null,
            options_gost: null,
            options_manufacturer
        };
    }
};
</script>
<style></style>
