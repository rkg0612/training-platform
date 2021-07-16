const state = {
  listSalespersonAndManager: [],
};

const actions = {
  getSalespersonsAndManagers(context, payload) {
    return this._vm.axios.post('/get-users-by-roles', payload);
  },
};

const mutations = {
  setSalespersonsAndManagers: (st, data) => {
    // eslint-disable-next-line no-param-reassign
    st.listSalespersonAndManager = data;
  },
};

export default {
  namespaced: true,
  state,
  actions,
  mutations,
};
