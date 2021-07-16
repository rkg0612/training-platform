const state = {
  cities: [],
};

const getters = {
  getCities: st => st.cities,
};

const mutations = {
  setCities: (st, data) => {
    st.cities = data;
  },
};

const actions = {
  getCities({ commit }) {
    return this._vm.axios.get("/cities").then(({ data }) => {
      commit("setCities", data);
    });
  },
  setCities({ commit }, data) {
    commit('setCities', data);
  },
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
};
