let actions = {
  createSubstationID({commit}, data) {
    commit('CREATE_SUBSTATION_ID', data);
  },
  manuallyCreating({commit}, data) {
    commit('MANUALLY_CREATING', data);
  },
  applyState({commit}, data) {
    commit('APPLY_STATE', data);
  },
  applyTemplates({commit}, data) {
    commit('APPLY_TEMPLATES', data);
  },
  addElement({commit}, data) {
    commit('ADD_ELEMENT', data);
  },
  addActiveTemplateElement({commit}, data) {
    commit('ADD_ACTIVE_TEMPLATE_ELEMENT', data);
  },
  selectElement({commit}, data) {
    commit('SELECT_ELEMENT', data);
  },
  deleteElement({commit}, id) {
    commit('DELETE_ELEMENT', id);
  },
  deleteActiveTemplateElement({commit}, id) {
    commit('DELETE_ACTIVE_TEMPLATE_ELEMENT', id);
  },
  addTemplateElement({commit}, data) {
    commit('ADD_TEMPLATE_ELEMENT', data);
  },
  deleteTemplateElement({commit}, data) {
    commit('DELETE_TEMPLATE_ELEMENT', data);
  },
  changeData({commit}, data) {
    commit('CHANGE_DATA', data);
  },
  changeActiveTemplateData({commit}, data) {
    commit('CHANGE_ACTIVE_TEMPLATE_ELEMENT', data);
  },
  changeTemplateElement({commit}, data) {
    commit('CHANGE_TEMPLATE_ELEMENT', data);
  },
  changeElement({commit}, data) {
    commit('CHANGE_ELEMENT', data);
  },
  defineActiveTemplate({commit}, data) {
    commit('DEFINE_ACTIVE_TEMPLATE', data);
  },
  clearTireConnection({commit}, id) {
    commit('CLEAR_TIRE_CONNECTION', id);
  },
  waitSelectUpdate({commit}, data) {
    commit('WAIT_SELECT_UPDATE', data);
  },
  async fetchModelsList({commit}, name) {
    const options = {
      method: 'POST',
      url: '/api/getModelRecords'
    };
    let form = new FormData();

    if (name && !name.includes('/')) {
      form.append('modelName', name);
      options.data = form;
    } else {
      options.url = `/api${name}`;
      options.method = 'GET'
    }

    await axios(options)
      .then(
        response => {
          console.log(response)
          commit('UPDATE_MODELS_LIST', {modelName: name, modelsList: response.data});
        },
      )
      .catch(error => {
        toastr.error('Ошибка при загрузке данных...');
      })
  },
};

export default actions;
