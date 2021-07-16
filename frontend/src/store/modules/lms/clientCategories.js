const state = {
  items: [],
  selectedCategory: {
    modules: [
      {
        name: '',
        thumbnail: '',
        description: '',
        units: [
          {
            id: '',
            description: '',
            thumbnail: '',
            name: '',
            video_url: '',
          }
        ]
      }
    ]
  },
};

const mutations = {
  setItems: (st, data) => {
    st.items = data;
  },
  setCategory: (st, data) => {
    st.selectedCategory = data;
  }
};

const actions = {
  getCategories(context) {
    return this._vm.axios.get('/client/lms/slider/categories');
  },
  showCategory(context, payload) {
    return this._vm.axios.get(`/client/lms/slider/categories/${payload.id}`);
  }
};

export default {
  namespaced: true,
  state,
  mutations,
  actions
};
