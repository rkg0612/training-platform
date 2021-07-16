const state = {
  numbers: []
};

const getters = {
  getNumbers: st => st.numbers
};

const mutations = {
  setNumbers: (st, data) => {
    // eslint-disable-next-line no-param-reassign
    st.numbers = data;
  }
};

const actions = {
  getAvailableNumbers({ commit }, payload) {
    return this._vm.axios.post("/twilio/list-available-numbers", payload).then(({ data }) => {
      commit("setNumbers", data);
      console.log(data, payload);
      return data;
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
