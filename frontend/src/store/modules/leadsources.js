const state = {
  leadsources: []
};

const getters = {
  getLeadSource: st => st.leadsources
};

const mutations = {
  setLeadSource: (st, data) => {
    st.leadsources = data;
  }
};

const actions = {
  getLeadSource({ commit }) {
    return this._vm.axios.get("/leadsources").then(({ data }) => {
      commit("setLeadSource", data);
    });
  }
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
};
