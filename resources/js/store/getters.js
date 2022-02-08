let getters = {
    id: state => state.id,
    manuallyCreating: state => state.manuallyCreating,
    elementsList: state => state.elementsList,
    selectedElement: state => state.selectedElement,
    templatesList: state => state.templatesList,
    selectedElementData: state => state.elementsList.find(el => el.id === state.selectedElement.id),
    findById: state => (id) => state.elementsList.find(el => el.id === id),
    cellSize: state => state.cellSize,
    waitSelect: state =>  state.waitSelect,
    activeTemplate: state => state.activeTemplate,
    modelsList: state => state.modelsList,
    allState: state => state,
};

export default getters;
