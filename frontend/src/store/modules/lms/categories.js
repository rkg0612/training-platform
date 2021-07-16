const state = {
  listCategories: [],
};

const actions = {
  getCategories(context, {
    pagination,
    filters
  }) {
    return this._vm.axios.get(
      `/lms-manager/categories?page=${pagination.current_page}&per_page=${pagination.per_page}&type=${filters.status}&search=${filters.search}`
    );
  },
  saveCategory(context, payload) {
    return this._vm.axios.post('/lms-manager/categories', payload);
  },
  editCategory(context, {
    payload, id
  }) {
    return this._vm.axios.post(`/lms-manager/categories/${id}`, payload);
  },
  deleteCategory(context, payload) {
    return this._vm.axios.delete(`/lms-manager/categories/${payload}`);
  },
  restoreCategory(context, payload) {
    return this._vm.axios.patch(`/lms-manager/categories/restore/${payload}`);
  },
  fetchBasicCategories(context, payload) {
    return this._vm.axios.get(`/lms-manager/fetch/categories`);
  },
};

const mutations = {
  setCategories: (st, data) => {
    // eslint-disable-next-line no-param-reassign
    st.listCategories = data;
  },
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
};
