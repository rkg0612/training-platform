const state = {
  list: [],
};

const mutations = {
  setSecretShops: (st, data) => {
    st.list =  data;
  }
};

const actions = {
  getSecretShops() {
    return this._vm.axios.get('/secret-shops-table');
  }
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
};
