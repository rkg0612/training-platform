const state = {
  unitsList: [],
};

const actions = {
  getUnits(context, {
    pagination,
    filter
  }) {
    return this._vm.axios.get(
      // eslint-disable-next-line
      `/lms-manager/units?page=${pagination.current_page}&per_page=${pagination.per_page}&type=${filter.status}&desc=${filter.sortDesc}&sortBy=${filter.sortBy}&search=${filter.search}`
    );
  },
  saveUnit(context, payload) {
    return this._vm.axios.post('/lms-manager/units', payload);
  },
  editUnit(context, {
    payload, id
  }) {
    return this._vm.axios.post(`/lms-manager/units/${id}`, payload);
  },
  deleteUnit(context, payload) {
    return this._vm.axios.delete(`/lms-manager/units/${payload}`);
  },
  restoreUnit(context, payload) {
    return this._vm.axios.patch(`/lms-manager/units/restore/${payload}`);
  },
  fetchTags(context, payload) {
    return this._vm.axios.get(`/lms-manager/tags`);
  },
};

const mutations = {
  setUnits: (st, data) => {
    // eslint-disable-next-line no-param-reassign
    st.unitsList = data;
  },
  addUnit: (st, data) => {
    st.unitsList.push(data);
  },
  editUnit: (st, payload) => {
    st.unitsList.splice(payload.index, 1, payload.result);
  },
  deleteUnit: (st, index) => {
    st.unitsList.splice(index, 1);
  },
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
};
