const state = {
  report: '',
};

const mutations = {
  setReport: (st, data) => {
    st.report = data;
  }
};

const actions = {
  getReport(context, payload) {
    return this._vm.axios.get(`/preview-reports/phone-shop/${payload}`);
  },
  sendReport(context, payload) {
    return this._vm.axios.post('/send-reports/phone-shop', payload);
  }
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
};

