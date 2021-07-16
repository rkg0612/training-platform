const state = {
  //
};

const actions = {
  shareUnit(context, payload) {
    return this._vm.axios.post('/client/lms/shareunit', payload);
  },
  shareModule(context, payload) {
    return this._vm.axios.post('/client/lms/sharemodule', payload);
  },
};

const mutations = {
  //
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
};
