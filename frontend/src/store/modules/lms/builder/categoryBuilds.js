const state = {
  listCategoryBuilds: [],
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
      `/lms-manager/builder/categories?page=${payload.current_page}&per_page=${payload.per_page}&type=${filter}&search=${search}`
    );
  },
  saveBuild(context, payload) {
    return this._vm.axios.post('/lms-manager/builder/categories', payload);
  },
  updateBuild(context, payload) {
    return this._vm.axios.patch(`/lms-manager/builder/categories/${payload.id}`, payload);
  },
  deleteBuild(context, payload) {
    return this._vm.axios.delete(`/lms-manager/builder/categories/${payload}`);
  },
  restoreBuild(context, payload) {
    return this._vm.axios.patch(`/lms-manager/builder/categories/restore/${payload}`);
  },
  fetchCategoriesByCourse(context, payload) {
    return this._vm.axios.get(
    `/lms-manager/builder/fetch/categories?course_id=${payload.course_id}`);
  },
  fetchModuleBuildsByCategory(context, payload) {
    return this._vm.axios.get(
      `/lms-manager/builder/fetch/module_builds?category_id=${payload.category_id}`
      );
  },
};

const mutations = {
  setBuild: (st, data) => {
    // eslint-disable-next-line no-param-reassign
    st.listCategoryBuilds = data;
  },
  addBuild: (st, data) => {
    st.listCategoryBuilds.push(data);
  },
  editBuild: (st, payload, index) => {
    st.listCategoryBuilds.splice(index, 1, payload);
  },
  deleteBuild: (st, index) => {
    st.listCategoryBuilds.splice(index, 1);
  },
};
export default {
  namespaced: true,
  getters,
  state,
  mutations,
  actions,
};
