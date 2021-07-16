const state = {
  listCourses: [],
};

const getters = {};

const actions = {
  getCourses(context, {
    pagination,
    filters
  }) {
    return this._vm.axios.get(
      // eslint-disable-next-line
      `/lms-manager/courses?page=${pagination.current_page}&per_page=${pagination.per_page}&type=${filters.status}&search=${filters.search}`
    );
  },
  editCourse({ commit }, payload) {
    commit('editCourse', payload);
  },
  deleteCourse(context, payload) {
    return this._vm.axios.delete(`/lms-manager/courses/${payload}`);
  },
  restoreCourse(context, payload) {
    return this._vm.axios.patch(`/lms-manager/courses/restore/${payload}`);
  },
  addCourse({ commit }, payload) {
    commit('addCourse', payload);
  }
};

const mutations = {
  setCourses: (st, data) => {
    // eslint-disable-next-line no-param-reassign
    st.listCourses = data;
  },
  addCourse: (st, data) => {
    st.listCourses.push(data);
  },
  editCourse: (st, { index, result }) => {
    st.listCourses.splice(index, 1, result);
  },
  deleteCourse: (st, index) => {
    st.listCourses.splice(index, 1);
  },
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};
