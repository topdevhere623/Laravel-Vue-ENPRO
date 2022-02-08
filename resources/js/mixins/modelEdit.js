export default {
  methods: {
    filterCopiedRecord(data) {
      return JSON.parse(JSON.stringify(data), (k, v) => {
        return (k === 'id') ? undefined : v
      })
    },
  },
};
