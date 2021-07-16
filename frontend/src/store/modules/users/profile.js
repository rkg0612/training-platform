const state = {
  value: {},
  company: {},
};

const mutations = {
  setProfile: (st, data) => {
    st.value = data;
  },
  setCompany: (st, data) => {
    st.company = data;
  }
};

export default {
  namespaced: true,
  state,
  mutations,
};
