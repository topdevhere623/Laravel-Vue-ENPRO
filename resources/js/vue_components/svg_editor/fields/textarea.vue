<template>
    <div class="form-field">
        <textarea :type="inputType ? inputType : 'text'" @input="onInput" class="text-field text-area"
                  v-model="text"></textarea>
        <div class="form-input-label">{{ label }}</div>
    </div>
</template>
<script>

export default {
    name: 'textArea',
    data() {
        return ({
            text: this.value,
        });
    },
    watch: {},
    props: ['value', 'label', 'id', 'inputType'],
    computed: {},
    methods: {
        onInput(e) {
            this.$emit('change', this.text, this.id);
            e.target.style.height = 'auto';
            e.target.style.height = `${e.target.scrollHeight}px`;
        },
    },
    mounted() {
        this.$nextTick(() => {
            this.$el.setAttribute('style', 'height',
                `${this.$el.scrollHeight}px`);
        });
    },
    created() {
    },
};
</script>

<style scoped>
.text-area {
    resize: none;
}
</style>
