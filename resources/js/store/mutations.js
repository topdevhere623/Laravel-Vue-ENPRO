let mutations = {
  CREATE_SUBSTATION_ID(state, id) {
    state.id = id;
  },
  MANUALLY_CREATING(state, data) {
    state.manuallyCreating = data;
  },
  APPLY_STATE(state, data) {
    Object.assign(state, data);
    state.manuallyCreating = false;
  },
  APPLY_TEMPLATES(state, templateList) {
    state.templatesList.push(...templateList)
  },
  ADD_ELEMENT(state, data) {
    if (data.type.includes('tire')) {
      let tires = state.elementsList.filter(el => el.type.includes('tire'))
      if (tires.length > 0) {
        let lastTireIndex = tires.indexOf(tires[tires.length - 1])
        state.elementsList.splice(lastTireIndex + 1, 0, data)
      } else {
        state.elementsList.unshift(data)
      }
    } else {
      state.elementsList.push(data)
    }
    return state.elementsList;
  },
  ADD_ACTIVE_TEMPLATE_ELEMENT(state, data) {
    state.activeTemplate.elements.push(data);
    return state.activeTemplate;
  },
  SELECT_ELEMENT(state, data) {
    state.selectedElement = data;
    return state.selectedElement;
  },
  DELETE_ELEMENT(state, id) {
    const i = state.elementsList.map(item => item.id).indexOf(id);
    i >= 0 && state.elementsList.splice(i, 1);
  },
  CHANGE_TEMPLATE_ELEMENT(state, data) {
    const i = state.templatesList.map(item => item.id).indexOf(data.id);
    Vue.set(state.templatesList, i, data);
  },
  CHANGE_ELEMENT(state, data) {
    const i = state.elementsList.map(item => item.id).indexOf(data.id);
    Vue.set(state.elementsList, i, data);
  },
  DELETE_ACTIVE_TEMPLATE_ELEMENT(state, id) {
    const i = state.activeTemplate.elements.map(item => item.id).indexOf(id);
    i >= 0 && state.activeTemplate.elements.splice(i, 1);
  },
  ADD_TEMPLATE_ELEMENT(state, data) {
    state.templatesList.push(data);
    return state.templatesList;
  },
  DELETE_TEMPLATE_ELEMENT(state, id) {
    const i = state.templatesList.map(item => item.id).indexOf(id);
    i >= 0 && state.templatesList.splice(i, 1);
  },
  CHANGE_DATA(state, data) {
    state.elementsList.find(el => el.id === data.id)[data.field] = data.value;
    return state.elementsList.find(el => el.id === data.id);
  },
  DEFINE_ACTIVE_TEMPLATE(state, data) {
    state.activeTemplate = data;
    return state.activeTemplate;
  },
  CHANGE_ACTIVE_TEMPLATE_ELEMENT(state, data) {
    state.activeTemplate.elements.find(el => el.id === data.id)[data.field] = data.value;
    return state.activeTemplate.elements.find(el => el.id === data.id);
  },
  CLEAR_TIRE_CONNECTION(state, id) {
    state.elementsList.forEach(el => {
      if (el.connection) {
        const i = el.connection.indexOf(id);
        i >= 0 && el.connection.splice(i, 1);
      }
      if (el.beforeTire === id) {
        state.elementsList.find(stEl => stEl.afterTire === el.beforeTire).afterTire = null;
        el.beforeTire = null;
      }
    });
  },
  WAIT_SELECT_UPDATE(state, data) {
    state.waitSelect = data;
  },
  UPDATE_MODELS_LIST(state, data) {
    if (data.modelsList.data) {
      state.modelsList = {...state.modelsList, [data.modelName]: data.modelsList.data};
    } else {
      state.modelsList = {...state.modelsList, [data.modelName]: data.modelsList};
    }
  },
}
export default mutations;
