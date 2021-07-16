const state = {
  dealers: []
};

const getters = {
  getDealers: st => st.dealers
};

const mutations = {
  // eslint-disable-next-line no-param-reassign,no-return-assign
  setDealers: (st, data) => (st.dealers = data)
};

const actions = {
  getDealers({ commit }) {
    return this._vm.axios.get("/dealers").then(({ data }) => commit("setDealers", data));
  }
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
};
