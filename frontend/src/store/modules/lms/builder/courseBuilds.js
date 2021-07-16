const state = {
  listCourseBuilds: [],
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
      `/lms-manager/builder/courses?page=${payload.current_page}&per_page=${payload.per_page}&type=${filter}&search=${search}`
    );
  },
  saveBuild(context, payload) {
    return this._vm.axios.post('/lms-manager/builder/courses', payload);
  },
  updateBuild(context, payload) {
    return this._vm.axios.patch(`/lms-manager/builder/courses/${payload.id}`, payload);
  },
  deleteBuild(context, payload) {
    return this._vm.axios.delete(`/lms-manager/builder/courses/${payload}`);
  },
  restoreBuild(context, payload) {
    return this._vm.axios.patch(`/lms-manager/builder/courses/restore/${payload}`);
  },
  fetchCategoriesByDealer(context, payload) {
    return this._vm.axios.get(
      `/lms-manager/builder/fetch/category_builds?course_id=${payload.course_id}`);
  },
};

const mutations = {
  setBuild: (st, data) => {
    // eslint-disable-next-line no-param-reassign
    st.listCourseBuilds = data;
  },
  addBuild: (st, data) => {
    st.listCourseBuilds.push(data);
  },
  editBuild: (st, payload, index) => {
    st.listCourseBuilds.splice(index, 1, payload);
  },
  deleteBuild: (st, index) => {
    st.listCourseBuilds.splice(index, 1);
  },
};
export default {
  namespaced: true,
  getters,
  state,
  mutations,
  actions,
};
