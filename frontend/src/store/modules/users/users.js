const state = {
  users: [],
};

const getters = {};

const mutations = {};

const actions = {
  getUsers(context, { payload } ) {
    return this._vm.axios.get('/users', {
      params: payload,
    });
  },
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
};
