import 'svg.select.js';

export default {
    methods: {
        triggerDelete(id) {
            this.$store.dispatch('clearTireConnection', id);
            this.$store.dispatch('selectElement', {}).then(res => {
                this.$store.dispatch('deleteElement', id);
            });
        },
    },
};
