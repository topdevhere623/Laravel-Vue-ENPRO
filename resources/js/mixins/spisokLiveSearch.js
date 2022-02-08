const spisokLiveSearch = {
	created: function () {
		this.debouncedLiveSeacrh = _.debounce(this.funLoadContent, 1000);
	},
	watch: {
		filterName: function () {
			this.debouncedLiveSeacrh();
		},
	},
}
export default spisokLiveSearch