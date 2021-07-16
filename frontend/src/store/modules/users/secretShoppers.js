const state = {
  secretShoppers: []
};

const getters = {
  getSecretShoppers: st => st.secretShoppers
};

const mutations = {
  // eslint-disable-next-line no-return-assign,no-param-reassign
  setSecretShoppers: (st, data) => (st.secretShoppers = data)
};

const actions = {
  getSecretShoppers({ commit }) {
    return this._vm.axios
      .get("/users/secret-shoppers")
      .then(({ data }) => commit("setSecretShoppers", data));
  }
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
};
