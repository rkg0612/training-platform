const state = {
  listModuleBuilds: [],
  listUnits: [],
  listCourses: [],
};

const getters = {};

const actions = {
  getBuilds(context, {
    payload,
    filter,
    search,
  }) {
    return this._vm.axios.get(
      // eslint-disable-next-line
      `/lms-manager/builder/modules?page=${payload.current_page}&per_page=${payload.per_page}&type=${filter}&search=${search}`
    );
  },
  saveBuild(context, payload) {
    return this._vm.axios.post('/lms-manager/builder/modules', payload);
  },
  updateBuild(context, payload) {
    return this._vm.axios.patch(`/lms-manager/builder/modules/${payload.id}`, payload);
  },
  deleteBuild(context, payload) {
    return this._vm.axios.delete(`/lms-manager/builder/modules/${payload}`);
  },
  restoreBuild(context, payload) {
    return this._vm.axios.patch(`/lms-manager/builder/modules/restore/${payload}`);
  },
  fetchModulesByCourse(context, payload) {
    return this._vm.axios.get(`/lms-manager/builder/fetch/modules?course_id=${payload.course_id}`);
  },
  fetchUnitsByModules(context, payload) {
    return this._vm.axios.get(`/lms-manager/builder/fetch/units?course_id=${payload.course_id}&module_id=${payload.module_id}`);
  },
  fetchCourses(context, payload) {
    return this._vm.axios.get(`/lms-manager/builder/fetch/courses`);
  },
};

const mutations = {
  setCourses: (st, data) => {
    // eslint-disable-next-line no-param-reassign
    st.listCourses = data;
  },
  setUnit: (st, data) => {
    // eslint-disable-next-line no-param-reassign
    st.listUnits = data;
  },
  addUnit: (st, data) => {
    st.listUnits.push(data);
  },
  setBuild: (st, data) => {
    // eslint-disable-next-line no-param-reassign
    st.listModuleBuilds = data;
  },
  addBuild: (st, data) => {
    st.listModuleBuilds.push(data);
  },
  editBuild: (st, data, index) => {
    st.listModuleBuilds.splice(index, 1, data);
  },
  deleteBuild: (st, index) => {
    st.listModuleBuilds.splice(index, 1);
  },
};
export default {
  namespaced: true,
  getters,
  state,
  mutations,
  actions,
};
