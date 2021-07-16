const state = {
  options: [],
};

const mutations = {
  setOptions: (st, data) => {
    st.options = data;
  },
};

const actions = {
  getOptions(context, payload) {
    return this._vm.axios('/options/dealer', {
      params: {
        email: payload
      }
    });
  },
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
};
