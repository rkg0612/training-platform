const state = {
  modulesList: [],
};

const actions = {
  getModules(context, {
    pagination,
    filters
  }) {
    return this._vm.axios.get(
      // eslint-disable-next-line
      `/lms-manager/modules?page=${pagination.current_page}&per_page=${pagination.per_page}&type=${filters.status}&search=${filters.search}`
    );
  },
  saveModule(context, payload) {
    return this._vm.axios.post('/lms-manager/modules', payload);
  },
  editModule(context, {
    payload, id
  }) {
    return this._vm.axios.post(`/lms-manager/modules/${id}`, payload);
  },
  deleteModule(context, payload) {
    return this._vm.axios.delete(`/lms-manager/modules/${payload}`);
  },
  restoreModule(context, payload) {
    return this._vm.axios.patch(`/lms-manager/modules/restore/${payload}`);
  },
  fetchBasicModules(context, payload) {
    return this._vm.axios.get(`/lms-manager/fetch/modules`);
  },
};

const mutations = {
  setModules: (st, data) => {
    // eslint-disable-next-line no-param-reassign
    st.modulesList = data;
  },
  addModule: (st, data) => {
    st.modulesList.push(data);
  },
  editModule: (st, payload) => {
    st.modulesList.splice(payload.index, 1, payload.result);
  },
  deleteModule: (st, index) => {
    st.modulesList.splice(index, 1);
  },
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
};
