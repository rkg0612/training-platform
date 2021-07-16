const state = {
  selectedItem: {
    unit: {
      name: '',
    },
    videos: [],
  },
  quizResult: '',
};

const mutations = {
  setSelectedItem: (st, data) => {
    st.selectedItem = data;
  },
  setQuizResult: (st, data) => {
    st.quizResult = data;
  },
  setCompleted: (st, data) => {
    st.selectedItem.is_completed = data;
  }
};

const actions = {
  getClientUnit(context, payload) {
    return this._vm.axios.get(`/client/lms/unit/${payload}`);
  }
};

export default {
  namespaced: true,
  state,
  mutations,
  actions
};

