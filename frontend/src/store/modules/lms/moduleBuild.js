const state = {
  selectedModuleBuild: {
    module: {
      name: '',
      description: '',
    },
    series: [
      {
        name: '',
        units: [
          {
            unit: {
              thumbnail: '',
              description: '',
              video_duration: '',
            }
          }
        ]
      }
    ]
  }
};

const actions = {
  getModuleBuild(context, payload) {
    return this._vm.axios.get(`/client/lms/module/${payload}`);
  },
};

const mutations = {
  setSelectedModuleBuild: (st, data) => {
    st.selectedModuleBuild = data;
  },
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
};
