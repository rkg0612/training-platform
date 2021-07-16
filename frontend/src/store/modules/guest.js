const state = {
  asideBanner: '',
};

const mutations = {
  setAsideBanner: (st, data) => {
    st.asideBanner = data;
  }
};

const actions = {
  setAsideBanner({ commit }, payload) {
    commit('setAisdeBanner', payload);
  }
};

export default {
  namespaced: true,
  state,
  mutations,
  actions
};
