const state = {
  states: [],
};

const getters = {
  getStates: st => st.states,
};

const mutations = {
  // eslint-disable-next-line no-param-reassign,no-return-assign
  setStates: (st, data) => (st.states = data),
  getStates: st => st.states
};

const actions = {
  getStates({ commit }) {
    return this._vm.axios.get('/states').then(({ data }) => {
      commit('setStates', data);
    });
  },
  setStates({ commit }, data) {
    commit('setStates', data);
  },
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
};
