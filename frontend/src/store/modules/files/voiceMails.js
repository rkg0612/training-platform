const state = {
  voiceMails: []
};

const getters = {
  getVoiceMails: st => st.voiceMails
};

const mutations = {
  // eslint-disable-next-line no-param-reassign,no-return-assign
  setVoiceMails: (st, data) => (st.voiceMails = data)
};

const actions = {
  getVoiceMails({ commit }) {
    return this._vm.axios
      .get("/files/voice-mails")
      .then(({ data }) => commit("setVoiceMails", data));
  }
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
};
