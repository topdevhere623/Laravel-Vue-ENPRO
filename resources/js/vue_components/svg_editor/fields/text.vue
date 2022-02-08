<template>
    <div class="form-field coords">
        <div>{{ label }}: <span>{{ value }}</span></div>
    </div>
</template>
<script>

export default {
    name: 'text-readonly',
    data() {
        return ({});
    },
    watch: {},
    props: ['value', 'label', 'id'],
    computed: {},
    created() {

    },
    mounted() {

    },
};
</script>
