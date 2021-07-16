const state = {
  list: [],
};

const mutations = {
  setSpecificDealers: (state, data) => {
    state.list = data;
  }
};

const actions = {
  getSpecificDealers(context) {
    return this._vm.axios.get('dealers/specific');
  }
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
};
